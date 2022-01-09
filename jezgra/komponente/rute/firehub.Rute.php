<?php declare(strict_types = 1);

/**
 * Datoteka za dohvaćanje HTTP ruta
 * @since 0.4.1.pre-alpha.M4
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\HTTP
 *
 * @todo Posrednici
 */

namespace FireHub\Jezgra\Komponente\Rute;

use FireHub\Jezgra\Komponente\Servis_Kontejner;
use FireHub\Jezgra\Komponente\Servis_Posluzitelj;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;

/**
 * ### Poslužitelj za dohvaćanje HTTP ruta
 * @since 0.4.1.pre-alpha.M4
 *
 * @package Sustav\HTTP
 */
final class Rute extends Servis_Posluzitelj {

    /**
     * ### Sve HTTP metode
     * @since 0.4.1.pre-alpha.M4
     *
     * @param string $url <p>
     * URL rute.
     * </p>
     * @param array<string, string> $podatci <p>
     * Kontroler i metoda rute.
     * </p>
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Rute.
     *
     * @return bool Da li je dodana ruta.
     */
    public static function sve (string $url, array $podatci):bool {

        return (new self())->napravi()->dodaj('SVE', $url, $podatci);

    }

    /**
     * ### GET metoda
     * @since 0.4.1.pre-alpha.M4
     *
     * @param string $url <p>
     * URL rute.
     * </p>
     * @param array<string, string> $podatci <p>
     * Kontroler i metoda rute.
     * </p>
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Rute.
     *
     * @return bool Da li je dodana ruta.
     */
    public static function get (string $url, array $podatci):bool {

        return (new self())->napravi()->dodaj('GET', $url, $podatci);

    }

    /**
     * ### POST metoda
     * @since 0.4.1.pre-alpha.M4
     *
     * @param string $url <p>
     * URL rute.
     * </p>
     * @param array<string, string> $podatci <p>
     * Kontroler i metoda rute.
     * </p>
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Rute.
     *
     * @return bool Da li je dodana ruta.
     */
    public static function post (string $url, array $podatci):bool {

        return (new self())->napravi()->dodaj('POST', $url, $podatci);

    }

    /**
     * ### PUT metoda
     * @since 0.4.1.pre-alpha.M4
     *
     * @param string $url <p>
     * URL rute.
     * </p>
     * @param array<string, string> $podatci <p>
     * Kontroler i metoda rute.
     * </p>
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Rute.
     *
     * @return bool Da li je dodana ruta.
     */
    public static function put (string $url, array $podatci):bool {

        return (new self())->napravi()->dodaj('PUT', $url, $podatci);

    }

    /**
     * ### DELETE metoda
     * @since 0.4.1.pre-alpha.M4
     *
     * @param string $url <p>
     * URL rute.
     * </p>
     * @param array<string, string> $podatci <p>
     * Kontroler i metoda rute.
     * </p>
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Rute.
     *
     * @return bool Da li je dodana ruta.
     */
    public static function delete (string $url, array $podatci):bool {

        return (new self())->napravi()->dodaj('DELETE', $url, $podatci);

    }

    /**
     * {@inheritDoc}
     *
     * @return Rute_Interface Objekt Ruta servisa.
     */
    public function napravi ():object {

        return (new Servis_Kontejner($this))->singleton();

    }

}