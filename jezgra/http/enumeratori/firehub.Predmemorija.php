<?php declare(strict_types = 1);

/**
 * Datoteka za enumerator za dostupne kontrole predmemorije
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
 * ### Enumerator za dostupne kontrole predmemorije
 * @since 0.2.5.pre-alpha.M2
 *
 * @package Sustav\HTTP
 */
enum Predmemorija:string {

    case JAVNO = 'public';
    case PRIVATNO = 'private';
    case BEZ_PREDMEMORIJE = 'no-cache';
    case BEZ_SPREMANJA = 'no-store';
    case MORA_PONOVNO_POTVRDITI = 'must-revalidate';
    case VRIJEDI_DO = 'max-age';

}