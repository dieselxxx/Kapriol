<?php declare(strict_types = 1);

/**
 * Datoteka za poslužitelja baze podataka
 * @since 0.5.1.pre-alpha.M5
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\BazaPodataka;

/**
 * ### Interface za vrstu kursora
 * @since 0.5.1.pre-alpha.M5
 */
interface Kursor_Interface {

    /**
     * ### Vrijednost trenutnog kursora
     * @since 0.5.1.pre-alpha.M5
     *
     * @return string
     */
    public function vrijednost ():string;

}