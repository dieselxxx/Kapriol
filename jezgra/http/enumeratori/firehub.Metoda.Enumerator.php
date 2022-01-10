<?php declare(strict_types = 1);

/**
 * Datoteka za enumerator za dostupne HTTP metode
 * @since 0.6.0.alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\HTTP
 */

namespace FireHub\Jezgra\HTTP\Enumeratori;

use FireHub\Jezgra\Enumerator;

/**
 * ### Enumerator za dostupne HTTP metode
 *
 * @since 0.6.0.alpha.M1
 *
 * @method static self GET ()
 * @method static self POST ()
 * @method static self PUT ()
 * @method static self DELETE ()
 *
 * @package Sustav\HTTP
 */
final class Metoda_Enumerator extends Enumerator {

    protected const GET = 'GET';
    protected const POST = 'POST';
    protected const PUT = 'PUT';
    protected const DELETE = 'DELETE';

}