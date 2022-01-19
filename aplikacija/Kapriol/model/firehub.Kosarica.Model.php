<?php declare(strict_types = 1);

/**
 * Košarica model
 * @since 0.1.2.pre-alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2022 Kapriol Web Trgovina
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Aplikacija\Model
 */

namespace FireHub\Aplikacija\Kapriol\Model;

use FireHub\Jezgra\Komponente\Log\Enumeratori\Level;
use FireHub\Jezgra\Komponente\BazaPodataka\BazaPodataka;
use FireHub\Jezgra\Kontroler\Greske\Kontroler_Greska;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;

/**
 * ### Kategorije model
 * @since 0.1.2.pre-alpha.M1
 *
 * @package Aplikacija\Model
 */
final class Kosarica_Model extends Master_Model {

    /**
     * ### Konstruktor
     * @since 0.1.2.pre-alpha.M1
     *
     * @param BazaPodataka $bazaPodataka <p>
     * Baza podataka.
     * </p>
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Sesije.
     */
    public function __construct (
        private BazaPodataka $bazaPodataka
    ) {

        parent::__construct();

    }

    /**
     * ### Artikli iz košarice
     * @since 0.1.2.pre-alpha.M1
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca objekta.
     *
     * @return array Niz artikala.
     */
    public function artikli ():array {

        if ($this->sesija->procitaj('kosarica')) {

            $id = array_keys($this->sesija->procitaj('kosarica'));

            $sifra_array = '';
            foreach ($id as $kljuc => $vrijednost) {

                if ($kljuc === array_key_first($id)) {

                    $sifra_array .= "
                        artiklikarakteristike.Sifra = $vrijednost
                    ";

                } else {

                    $sifra_array .= "
                        OR artiklikarakteristike.Sifra = $vrijednost
                    ";

                }

            }

            $artikli = $this->bazaPodataka->tabela('artikliview')
                ->sirovi("
                    SELECT
                        ROW_NUMBER() OVER (ORDER BY Naziv ASC) AS RedBroj,
                           artikliview.ID, artikliview.Naziv, artikliview.Link, artikliview.Cijena, artikliview.CijenaAkcija, slikeartikal.Slika,
                           artiklikarakteristike.Sifra, artiklikarakteristike.Velicina
                    FROM artikliview
                    LEFT JOIN slikeartikal ON slikeartikal.ClanakID = artikliview.ID
                    LEFT JOIN artiklikarakteristike ON artiklikarakteristike.ArtikalID = artikliview.ID
                    WHERE artikliview.Aktivan = 1 AND artikliview.Ba = 1 AND slikeartikal.Zadana = 1
                    AND ($sifra_array)
                ")
                ->napravi();

            $rezultat = $artikli->niz();

            $kosarica = $this->sesija->procitaj('kosarica');

            foreach ($kosarica as $stavka => $vrijednost) {

                $kljuc = array_search($stavka, array_column($rezultat, 'Sifra'));

                // ukupno cijena
                if ($rezultat[$kljuc]['CijenaAkcija'] > 0) {
                    $rezultat[$kljuc]['CijenaUkupno'] = $vrijednost * $rezultat[$kljuc]['CijenaAkcija'];
                } else {
                    $rezultat[$kljuc]['CijenaUkupno'] = $vrijednost * $rezultat[$kljuc]['Cijena'];
                }

                // kolicina
                $rezultat[$kljuc]['Kolicina'] = $vrijednost;

            }

            return $rezultat ?: [];

        }

        return [];

    }

    /**
     * ### Dodaj artikl u košaricu
     * @since 0.1.2.pre-alpha.M1
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Baze podataka Log-a.
     * @throws Kontroler_Greska Ukoliko objekt nije validan model.
     *
     * @return bool Da li je artikl dodan u košaricu.
     */
    public function dodaj (string $velicina = '', int $vrijednost = 0):bool {

        $velicina_baza = $this->bazaPodataka->tabela('artiklikarakteristike')
            ->sirovi("
                SELECT
                    SUM(StanjeSkladiste) AS StanjeSkladiste
                FROM 00_kapriol.artiklikarakteristike
                LEFT JOIN 00_kapriol.stanjeskladista ON stanjeskladista.Sifra = artiklikarakteristike.Sifra
                WHERE artiklikarakteristike.Sifra = $velicina
                GROUP BY Velicina
            ")
            ->napravi();

        if (!$velicina_baza->redak()['StanjeSkladiste'] > 1) {

            zapisnik(Level::KRITICNO, sprintf(_('Šifre artikla: "%s" nema na stanju!'), $velicina_baza));
            throw new Kontroler_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru.'));

        }

        if (!$vrijednost > 0) {

            return false;

        }

        if (isset($this->sesija->procitaj('kosarica')[$velicina])) {

            $this->sesija->dodaj('kosarica', $velicina, $vrijednost + $this->sesija->procitaj('kosarica')[$velicina]);

        } else {

            $this->sesija->dodaj('kosarica', $velicina, $vrijednost);

        }

        return true;

    }

    /**
     * ### Dodaj artikl u košaricu
     * @since 0.1.2.pre-alpha.M1
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Baze podataka Log-a.
     * @throws Kontroler_Greska Ukoliko objekt nije validan model.
     *
     * @return bool Da li je artikl dodan u košaricu.
     */
    public function izmijeni (string $velicina = '', int $vrijednost = 0):bool {

        $velicina_baza = $this->bazaPodataka->tabela('artiklikarakteristike')
            ->sirovi("
                SELECT
                    SUM(StanjeSkladiste) AS StanjeSkladiste
                FROM 00_kapriol.artiklikarakteristike
                LEFT JOIN 00_kapriol.stanjeskladista ON stanjeskladista.Sifra = artiklikarakteristike.Sifra
                WHERE artiklikarakteristike.Sifra = $velicina
                GROUP BY Velicina
            ")
            ->napravi();

        if (!$velicina_baza->redak()['StanjeSkladiste'] > 1) {

            zapisnik(Level::KRITICNO, sprintf(_('Šifre artikla: "%s" nema na stanju!'), $velicina_baza));
            throw new Kontroler_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru.'));

        }

        if (!$vrijednost > 0) {

            return false;

        }

        if (!isset($this->sesija->procitaj('kosarica')[$velicina])) {

            return false;

        }

        $this->sesija->dodaj('kosarica', $velicina, $vrijednost);

        return true;

    }

    /**
     * ### Dodaj artikl u košaricu
     * @since 0.1.2.pre-alpha.M1
     *
     * @return bool Da li je artikl izbrisan iz košarice.
     */
    public function izbrisi (string $velicina = ''):bool {

        if (!isset($this->sesija->procitaj('kosarica')[$velicina])) {

            return false;

        }

        $this->sesija->izbrisiNiz('kosarica', $velicina);

        return true;

    }

}