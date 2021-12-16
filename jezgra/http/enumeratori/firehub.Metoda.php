<?php declare(strict_types = 1);

/**
 * Datoteka za enumerator za dostupne HTTP metode
 * @since 0.2.5.pre-alpha.M2
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\HTTP
 */

namespace FireHub\Jezgra\HTTP\Enumeratori;

/**
 * ### Enumerator za dostupne HTTP metode
 * @since 0.2.5.pre-alpha.M2
 *
 * @package Sustav\HTTP
 */
enum Metoda:string {

    case GET = 'GET';
    case POST = 'POST';
    case PUT = 'PUT';
    case DELETE = 'DELETE';

}