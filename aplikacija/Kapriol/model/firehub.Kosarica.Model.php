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
use FireHub\Aplikacija\Kapriol\Jezgra\Validacija;
use FireHub\Aplikacija\Kapriol\Jezgra\Email;
use FireHub\Jezgra\Greske\Greska;
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

    public function naruci () {

        $ime = $_POST['ime'];
        $prezime = $_POST['prezime'];
        $email = $_POST['email'];
        $grad = $_POST['grad'];
        $telefon = $_POST['telefon'];
        $adresa = $_POST['adresa'];
        $zip = $_POST['zip'];
        $tvrtka = $_POST['tvrtka'];
        $oib = $_POST['oib'];
        $napomena = $_POST['napomena'];
        $broj_1 = $_POST['broj_1'];
        $broj_2 = $_POST['broj_2'];
        $zastita = $_POST['zastita'];

        $ime = Validacija::Prilagodjen('/^[a-zšđčćžA-ZŠĐČĆŽ -]+$/i', "Vaše ime", $ime, 2, 20);
        $prezime = Validacija::Prilagodjen('/^[a-zšđčćžA-ZŠĐČĆŽ -]+$/i', "Vaše prezime", $prezime, 2, 20);
        $email = Validacija::Email("Vaš email", $email, 5, 100);
        $grad = Validacija::Prilagodjen('/^[a-zšđčćžA-ZŠĐČĆŽ -]+$/i', "Vaše ime", $grad,  3, 50);
        $telefon = Validacija::Telefon(_('Vaš broj telefona'), $telefon, 9, 15);
        $adresa = Validacija::String(_('Vaša adresa'), $adresa, 5, 300);
        $zip = Validacija::Broj(_('Vaš poštanski broj'), $zip, 5, 5);
        $tvrtka = Validacija::Prilagodjen('/^[a-zšđčćžA-ZŠĐČĆŽ0-9-. ]+$/i', _('Vaša tvrtka'), $tvrtka, 0, 100);
        $oib = Validacija::Broj(_('Vaš OIB \ PDV \ ID tvrtke'), $oib, 0, 20);
        $napomena = Validacija::String("Vaša napomena", $napomena, 0, 1000);
        $broj_1 = Validacija::Broj("Broj 1", $broj_1, 1, 2);
        $broj_2 = Validacija::Broj("Broj 2", $broj_2, 1, 2);
        $zastita = Validacija::Broj("Zbroj", $zastita, 1, 2);

        if (($broj_1 + $broj_2) <> $zastita) {

            // logger
            throw new Greska(sprintf(_('Zbroj %d i %d nije točan, provjerite vaš odgovor. (kod: %d)'), $broj_1, $broj_2, 1), 1);

        }

        // pošalji email
        $email_artikli_korisnik = '';
        $artikli = $this->artikli();
        $total_kolicina = 0;
        $total_cijena = 0;
        foreach ($artikli as $artikal) {

            // cijene
            if ($artikal['CijenaAkcija'] > 0) {

                $artikl_cijena = number_format((float)$artikal['CijenaAkcija'], 2, ',', '.');

            } else {

                $artikl_cijena = number_format((float)$artikal['Cijena'], 2, ',', '.');

            }

            // ukupno
            $total_kolicina += $artikal['Kolicina'];
            $total_cijena += $artikal['CijenaUkupno'];

            $email_artikli_korisnik .= "
            <tr>
                <td>{$artikal['Sifra']}</td>
                <td>{$artikal['Naziv']}</td>
                <td>{$artikal['Velicina']}</td>
                <td>{$artikal['Kolicina']} kom</td>
                <td>$artikl_cijena KM</td>
                <td>{$artikal['CijenaUkupno']} KM</td>
            </tr>";
        }

        $total_cijena = number_format((float)$total_cijena, 2, ',', '.');

        $email_slanje = new Email('narudzba_korisnik.html');
        $email_slanje->Naslov('Vaša narudžba je zaprimljena');
        $email_slanje->Adresa(array(
            array("adresa" => $email, "ime" => $ime . ' ' . $prezime)
        ));
        $email_slanje->PredlozakKomponente(array(
            "ime" => $ime,
            "prezime" => $prezime,
            "email" => $email,
            "grad" => $grad,
            "telefon" => $telefon,
            "adresa" => $adresa,
            "zip" => $zip,
            "tvrtka" => $tvrtka,
            "oib" => $oib,
            "napomena" => $napomena,
            "artikli" => $email_artikli_korisnik,
            "total_kolicina" => $total_kolicina . ' kom',
            "total_cijena" => $total_cijena . ' KM'
        ));
        $email_slanje->Posalji();

        $email_slanje_tvrtka = new Email('narudzba_tvrtka.html');
        $email_slanje_tvrtka->Naslov('Vaša narudžba je zaprimljena');
        $email_slanje_tvrtka->Adresa(array(
            array("adresa" => 'danijel.galic@outlook.com', "ime" => 'Kapriol')
        ));
        $email_slanje_tvrtka->PredlozakKomponente(array(
            "ime" => $ime,
            "prezime" => $prezime,
            "email" => $email,
            "grad" => $grad,
            "telefon" => $telefon,
            "adresa" => $adresa,
            "zip" => $zip,
            "tvrtka" => $tvrtka,
            "oib" => $oib,
            "napomena" => $napomena,
            "artikli" => $email_artikli_korisnik,
            "total_kolicina" => $total_kolicina . ' kom',
            "total_cijena" => $total_cijena . ' KM'
        ));
        $email_slanje_tvrtka->Posalji();

        $this->sesija->unisti();

    }

    /**
     * ### Kreira prvi broj.
     * @since 0.1.2.pre-alpha.M1
     *
     * @return int
     */
    public static function RandomBroj1 ():int {

        return random_int(0, 10);

    }

    /**
     * Kreira drugi broj.
     * @since 0.1.2.pre-alpha.M1
     *
     * @return int
     */
    public static function RandomBroj2 ():int {

        return random_int(0, 10);

    }

}