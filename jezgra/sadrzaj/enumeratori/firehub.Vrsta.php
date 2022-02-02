<?php declare(strict_types = 1);

/**
 * Datoteka za enumerator za dostupne vrste ispisa sadržaja u datoteke
 * @since 0.4.4.pre-alpha.M4
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Sadrzaj
 */

namespace FireHub\Jezgra\Sadrzaj\Enumeratori;

/**
 * ### Enumerator za dostupne vrste ispisa sadržaja u datoteke
 * @since 0.4.4.pre-alpha.M4
 *
 * @package Sustav\Sadrzaj
 */
enum Vrsta:string {

    case HTML = 'HTML';
    case JSON = 'JSON';
    case SLIKA = 'SLIKA';
    case HTMLP = 'HTMLP';

}