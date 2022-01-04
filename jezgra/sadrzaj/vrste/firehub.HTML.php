<?php declare(strict_types = 1);

/**
 * Datoteka za HTML sadržaj
 * @since 0.4.4.pre-alpha.M4
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Sadrzaj
 */

namespace FireHub\Jezgra\Sadrzaj\Vrste;

use FireHub\Jezgra\Komponente\Log\Enumeratori\Level;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;
use FireHub\Jezgra\Sadrzaj\Greske\Sadrzaj_Greska;
use FireHub\Jezgra\Sadrzaj\Vrsta_Interface;

/**
 * ### Klasa za HTML sadržaj
 * @since 0.4.4.pre-alpha.M4
 *
 * @package Sustav\Sadrzaj
 */
final class HTML implements Vrsta_Interface {

    /**
     * ### Sadržaj za ispis
     * @var string
     */
    private string $sadrzaj = '';

    /**
     * {@inheritDoc}
     *
     * @param string $datoteka [optional] <p>
     * Datoteka za učitavanja.
     * </p>
     */
    public function __construct (
        private array $podatci,
        private string $datoteka = ''
    ) {

    }

    /**
     * {@inheritDoc}
     *
     * @todo Dodati predmemoriju
     */
    public function ispisi ():string {

        //return '<br><b>'.round(memory_get_peak_usage()/1048576, 2) . ' mb</b>';

        // učitaj statički i dinamički sadržaj iz datoteka
        return $this->sadrzajSve();

    }

    /**
     * ### Učitaj dinamički sadržaj stranice
     * @since 0.4.4.pre-alpha.M4
     *
     * @return string Sadržaj.
     */
    private function sadrzajDinamicki ():string {

        return $this->sadrzaj;

    }

    /**
     * ### Učitaj statički i dinamički sadržaj stranice
     * @since 0.4.4.pre-alpha.M4
     *
     * @throws Sadrzaj_Greska Ukoliko se ne mogu obraditi podatci na datoteci.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     *
     * @return string Sadržaj.
     */
    private function sadrzajSve ():string {

        $this->dodajBazu();

        return $this->sadrzaj;

    }

    /**
     * ### Dodaj baznu HTML datoteku
     * @since 0.4.4.pre-alpha.M4
     *
     * @throws Sadrzaj_Greska Ukoliko se ne može učitati bazni HTML sustava ili aplikacije.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     *
     * @return string Sadržaj.
     */
    private function dodajBazu ():string {

        // bazni HTML aplikacije
        $baza_app = FIREHUB_ROOT . konfiguracija('sustav.sistemskePutanje.web') . APLIKACIJA . RAZDJELNIK_MAPE . 'resursi' . RAZDJELNIK_MAPE . 'sadrzaj' . RAZDJELNIK_MAPE . 'baza.html';

        // bazni HTML sustava
        $baza_sustav = FIREHUB_ROOT . konfiguracija('sustav.sistemskePutanje.web') . 'resursi' . RAZDJELNIK_MAPE . 'sadrzaj' . RAZDJELNIK_MAPE . 'baza.html';

        switch (true) {

            // pokušaj učitati bazni HTML aplikacije
            case file_exists($baza_app) && $baza_app = file_get_contents($baza_app) :

                // dodaj sadrzaj aplikcije u bazu
                return $this->sadrzaj = $baza_app;

            // pokušaj učitati bazni HTML sustava
            case file_exists($baza_sustav) && $baza_sustav = file_get_contents($baza_sustav) :

                // dodaj sadrzaj sustava u bazu
                return $this->sadrzaj = $baza_sustav;

            // nedostaje bazni HTML sustava i aplikacije
            default :

                zapisnik(Level::KRITICNO, sprintf(_('Ne mogu učitati baznu datoteku sustava ili aplikacije: %s!'), $baza_sustav));
                throw new Sadrzaj_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru.'));

        }

    }

}