<?php declare(strict_types = 1);

/**
 * Datoteka za enumerator za dostupne HTTP metode
 * @since 0.2.2.pre-alpha.M2
 *
 * @author Danijel Galić
 * @copyright 2021 Grafotisak d.o.o.
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\HTTP
 */

namespace FireHub\Jezgra\Enumeratori;

use FireHub\Jezgra\Enumerator;

/**
 * Enumerator za dostupne HTTP metode
 * @since 0.2.2.pre-alpha.M2
 *
 * @method static self HTTP ()
 * @method static self KONZOLA ()
 *
 * @package Sustav\HTTP
 */
class Kernel_Enumerator extends Enumerator {

    protected const HTTP = 'HTTP';
    protected const KONZOLA = 'KONZOLA';

}