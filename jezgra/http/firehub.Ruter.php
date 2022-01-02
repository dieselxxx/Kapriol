<?php declare(strict_types = 1);

/**
 * Datoteka za rutiranje HTTP zahtjeva i odgovora aplikacije
 * @since 0.4.1.pre-alpha.M4
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\HTTP
 */

namespace FireHub\Jezgra\HTTP;

use FireHub\Jezgra\HTTP\Zahtjev as HTTP_Zahtjev;
use FireHub\Jezgra\Komponente\Rute\Rute;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;

/**
 * ### Klasa Ruter za rutiranje HTTP zahtjeva i odgovora aplikacije
 *
 * @since 0.4.1.pre-alpha.M4
 * @package Sustav\HTTP
 */
final class Ruter {

    /**
     * ### Trenutna ruta
     * @var array<string, string>|false
     */
    private array|false $ruta;

    /**
     * ### Konstruktor
     * @since 0.4.1.pre-alpha.M4
     *
     * @param HTTP_Zahtjev $http_zahtjev <p>
     * HTTP Zahtjev.
     * </p>
     * @param Rute $rute <p>
     * Poslužitelj HTTP ruta.
     * </p>
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Rute.
     */
    public function __construct (
        private HTTP_Zahtjev $http_zahtjev,
        private Rute $rute
    ) {

        // trenutna ruta
        $this->ruta = $this->ruta();

    }

    /**
     * ### Provjeri rutu iz trenutnog URL-a kod poslužitelja
     * @since 0.4.1.pre-alpha.M4
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Rute.
     *
     * @return array<string, string>|false Niz podataka rute.
     */
    private function ruta ():array|false {

        // filtrirani url bez aplikacije u nazivu
        $url = $this->url();

        // vrati podatke rute
        return $this->rute->napravi()->provjeri(
            $this->http_zahtjev->metoda(),
            implode('/', array_splice($url, 0, 2))
        );

    }

    /**
     *
     * ### Filtiraj URL bez aplikacije u nazivu
     *
     * Ruter treba čisti URL bez aplikacije u nazivu kako bi mogao
     * proslijediti ispravnu informaciju poslužitelju za rute, te
     * registraciju komponenti za kontroler.
     * @since 0.4.1.pre-alpha.M4
     *
     * @return array<int, string>
     */
    private function url ():array {

        $url_komponente = $this->http_zahtjev->urlKomponente();

        if ($url_komponente[0] === APLIKACIJA) {

            return array_splice($url_komponente,1);

        }

        return array_splice($url_komponente,0);

    }

}