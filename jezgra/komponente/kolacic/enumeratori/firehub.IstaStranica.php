<?php declare(strict_types = 1);

/**
 * Datoteka za enumerator za dostupne verzije iste stranice kolačića
 * @since 0.5.2.pre-alpha.M5
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\Kolacic\Enumeratori;

/**
 * ### Enumerator za dostupne verzije iste stranice kolačića
 * @since 0.5.2.pre-alpha.M5
 *
 * @package Sustav\Jezgra
 */
enum IstaStranica:string {

    case LAX = 'Lax';
    case STRICT = 'Strict';

}