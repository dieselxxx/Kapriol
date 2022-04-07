<?php declare(strict_types = 1);

/**
 * Datoteka za enumerator za dostupne vrste slika
 * @since 0.6.1.alpha.M6
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\Slika\Enumeratori;

/**
 * ### Enumerator za dostupne verzije vrste slika
 * @since 0.6.1.alpha.M6
 *
 * @package Sustav\Jezgra
 */
enum Vrsta:string {

    case JPEG = 'jpeg';
    case PNG = 'png';
    case GIF = 'gif';
    case AVIF = 'avif';

}