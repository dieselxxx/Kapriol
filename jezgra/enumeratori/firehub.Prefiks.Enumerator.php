<?php declare(strict_types = 1);

/**
 * Datoteka za enumerator za dostupne vrste prefiksa datoteka
 * @since 0.6.0.alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Enumeratori;

use FireHub\Jezgra\Enumerator;

/**
 * ### Enumerator za dostupne vrste prefiksa datoteka
 * @since 0.6.0.alpha.M1
 *
 * @method static self FIREHUB ()
 *
 * @package Sustav\Jezgra
 */
final class Prefiks_Enumerator extends Enumerator {

    protected const FIREHUB = 'firehub';

}