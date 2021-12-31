<?php declare(strict_types = 1);

/**
 * Datoteka za interface za sve posrednike
 * @since 0.4.0.pre-alpha.M4
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Posrednici
 */

namespace FireHub\Jezgra\Posrednici;

/**
 * ### Interface za sve posrednike
 * @since 0.4.0.pre-alpha.M4
 *
 * @package Sustav\Posrednici
 */
interface Posrednik {

    /**
     * ### Obradi zahtjev posrednika
     * @since 0.4.0.pre-alpha.M4
     *
     * @return bool Da li je uspješno obrađen posrednik.
     */
    public function obradi ():bool;

}