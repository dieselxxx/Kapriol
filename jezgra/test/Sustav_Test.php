<?php declare(strict_types = 1);

/**
 * Datoteka za testiranje pokretanja sustava
 * @since 0.2.4.pre-alpha.M2
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Test
 */

namespace FireHub\Jezgra\Test;

use PHPUnit\Framework\TestCase;
use FireHub\Jezgra\Sustav;
use FireHub\Jezgra\Kernel;
use FireHub\Jezgra\Enumeratori\Kernel as Kernel_Enumerator;

/**
 * ### Klasa za testiranje pokretanja sustava
 * @since 0.2.4.pre-alpha.M2
 *
 * @package Sustav\Test
 */
class Sustav_Test extends TestCase {

    /**
     * ### Sustav
     * @var Sustav
     */
    private Sustav $sustav;

    /**
     * Kernel
     * @var Kernel
     */
    private Kernel $kernel;

    /**
     * @inheritDoc
     */
    public function SetUp ():void {

        $this->sustav = new Sustav();

    }

    /**
     * ### Test postavljanja status koda HTTP odgovora
     * @since 0.2.4.pre-alpha.M2
     *
     * @return void
     */
    public function testPokreniSustav () {

    }

}