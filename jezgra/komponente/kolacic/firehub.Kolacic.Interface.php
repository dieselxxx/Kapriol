<?php declare(strict_types = 1);

/**
 * Datoteka interface-a za kolačić servise
 * @since 0.5.2.pre-alpha.M5
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\Kolacic;

use FireHub\Jezgra\Komponente\Servis_Interface;

/**
 * ### Interface-a za kolačić servise
 * @since 0.5.2.pre-alpha.M5
 *
 * @package Sustav\Jezgra
 */
interface Kolacic_Interface extends Servis_Interface {

    /**
     * ### Spremi kolačić
     * @since 0.5.2.pre-alpha.M5
     *
     * @return bool Da li je spremljen kolacic.
     */
    public function spremi ():bool;

    /**
     * ### Pročitaj vrijednost kolačića
     * @since 0.5.2.pre-alpha.M5
     *
     * @param $naziv <p>
     * Naziv kolačića.
     * </p>
     *
     * @return string|false Vrijednost kolačića.
     */
    public function procitaj ($naziv):string|false;

    /**
     * ### Izbriši kolačić
     * @since 0.5.2.pre-alpha.M5
     *
     * @param $naziv <p>
     * Naziv kolačića.
     * </p>
     *
     * @return bool True ako je kolačić izbrisan, False ako nije.
     */
    public function izbrisi ($naziv):bool;

}