<?php declare(strict_types = 1);

/**
 * Datoteka za HTTP zahtjev
 *
 * Ova datoteka prikuplja i obrađuje sve informacije o HTTP zahjevu.
 * @since 0.2.5.pre-alpha.M2
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\HTTP
 */

namespace FireHub\Jezgra\HTTP;

use FireHub\Jezgra\HTTP\Enumeratori\Metoda as HTTP_Metoda;

/**
 * ### HTTP zahtjev
 *
 * Klasa namjenjana za upravljenje i obrađivanje svih
 * HTTP zahtjeva prema sustavu i aplikacijama.
 * @since 0.2.5.pre-alpha.M2
 *
 * @package Sustav\HTTP
 */
final class Zahtjev {

    /**
     * ### Trenutni URL
     * @since 0.2.5.pre-alpha.M2
     *
     * @return string trenutni URI.
     */
    public function url ():string {

        return isset($_SERVER['REQUEST_URI']) ? strtolower(parse_url(rawurldecode($_SERVER['REQUEST_URI']), PHP_URL_PATH)) : '/';

    }

    /**
     * ### Razbijanje URL-a u niz odvojen karakterom {/}
     * @since 0.2.5.pre-alpha.M2
     *
     * @return string[] Niz komponenti odvojenih karakterom {/}.
     */
    public function urlKomponente ():array {

        return explode('/', trim(preg_replace('/\?.*/', '', $this->url()), '/'));

    }

    /**
     * ### Dohvati HTTP metodu zahtjeva
     * @since 0.2.5.pre-alpha.M2
     *
     * @return string Vrsta tražene metode.
     */
    public function metoda ():string {

        if (isset($_SERVER['REQUEST_METHOD']) && HTTP_Metoda::tryFrom($_SERVER['REQUEST_METHOD'])) {

            return HTTP_Metoda::from($_SERVER['REQUEST_METHOD'])->value;

        }

        return HTTP_Metoda::GET->value;

    }

}