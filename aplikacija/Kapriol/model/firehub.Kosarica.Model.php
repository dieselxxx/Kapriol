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

use FireHub\Jezgra\Model\Model;
use FireHub\Jezgra\Komponente\Sesija\Sesija;
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
final class Kosarica_Model extends Model {

    /**
     * ### Konstruktor
     * @since 0.1.2.pre-alpha.M1
     */
    public function __construct (
        private BazaPodataka $bazaPodataka,
        private Sesija $sesija
    ) {}

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

        // napravi sesiju
        $sesija = $this->sesija->naziv('Kapriol')->napravi();

        $sesija->zapisi('velicina_'.$velicina, $vrijednost);

        return true;

    }

    public function artikli ():array {

        var_dump($_SESSION);

        return [];

    }

}