<?php declare(strict_types = 1);

/**
 * Datoteka interface-a za ruta servise
 * @since 0.4.1.pre-alpha.M4
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\HTTP
 */

namespace FireHub\Jezgra\Komponente\Rute;

use FireHub\Jezgra\Komponente\Servis_Interface;

/**
 * ### Interface-a za ruta servise
 * @since 0.4.1.pre-alpha.M4
 *
 * @package Sustav\HTTP
 */
interface Rute_Interface extends Servis_Interface {

    /**
     * ### Provjeri podatke rute
     * @since 0.4.1.pre-alpha.M4
     *
     * @param string $metoda <p>
     * HTTP metoda rute.
     * </p>
     * @param string $url <p>
     * URL rute.
     * </p>
     *
     * @return array<string, string>|false Niz podataka rute.
     */
    public function provjeri (string $metoda, string $url):array|false;

    /**
     * ### Dodaj novu rutu u niz
     * @since 0.4.1.pre-alpha.M4
     *
     * @param string $metoda <p>
     * HTTP metoda rute.
     * </p>
     * @param string $url <p>
     * URL rute.
     * </p>
     * @param array<int, array<string, string, array>> $podatci <p>
     * Podatci rute.
     * </p>
     *
     * @return bool Da li je ruta dodana.
     */
    public function dodaj (string $metoda, string $url, array $podatci):bool;

}