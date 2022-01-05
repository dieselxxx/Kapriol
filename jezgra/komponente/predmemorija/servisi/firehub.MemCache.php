<?php declare(strict_types = 1);

/**
 * Datoteka za MemCache predmemoriju
 * @since 0.5.0.pre-alpha.M5
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\Predmemorija\Servisi;

use FireHub\Jezgra\Komponente\Predmemorija\Predmemorija;
use FireHub\Jezgra\Komponente\Predmemorija\Predmemorija_Interface;

/**
 * ### Servis Memcahe predmemorije
 * @since 0.5.0.pre-alpha.M5
 *
 * @package Sustav\Jezgra
 */
final class MemCache implements Predmemorija_Interface {

    public function __construct (private Predmemorija $posluzitelj) {
    }

}