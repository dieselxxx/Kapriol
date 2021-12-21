<?php declare(strict_types = 1);

/**
 * Datoteka interface-a za log servise
 * @since 0.3.1.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\Log;

use FireHub\Jezgra\Komponente\Servis_Interface;

/**
 * ### Interface-a za log servise
 * @since 0.3.1.pre-alpha.M3
 *
 * @package Sustav\Jezgra
 */
interface Log_Interface extends Servis_Interface {

    /**
     * ### Pošalji log dostavljačima
     * @since 0.3.1.pre-alpha.M3
     *
     * @return bool True ukoliko je zapisan log, u suprotnome False.
     */
    public function posalji ():bool;

}