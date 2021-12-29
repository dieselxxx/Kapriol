<?php declare(strict_types = 1);

/**
 * Datoteka interface-a za sve atribute
 * @since 0.3.0.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Atributi;

/**
 * ### Interface za sve atribute
 * @since 0.3.0.pre-alpha.M3
 *
 * @package Sustav\Jezgra
 */
interface Atribut {

    /**
     * ### Obradi atribut
     * @since 0.3.0.pre-alpha.M3
     *
     * @return bool Da li je uspješno obrađen atribut.
     */
    public function obradi ():bool;

}