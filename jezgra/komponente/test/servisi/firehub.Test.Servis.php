<?php declare(strict_types = 1);

/**
 * Datoteka za Servis za testiranje
 * @since 0.3.0.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\Test\Servisi;

use FireHub\Jezgra\Komponente\Test\Test;
use FireHub\Jezgra\Komponente\Test\Test_Interface;

/**
 * ### Servis za testiranje
 * @since 0.3.0.pre-alpha.M3
 *
 * @package Sustav\Jezgra
 */
final class Test_Servis implements Test_Interface {

    public function __construct (private Test $test) {
        //var_dump($this);
    }

}