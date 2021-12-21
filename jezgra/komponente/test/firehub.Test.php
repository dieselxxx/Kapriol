<?php declare(strict_types = 1);

/**
 * Datoteka za poslužitelj za testiranje
 * @since 0.3.0.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\Test;

use FireHub\Jezgra\Komponente\Servis_Kontejner;
use FireHub\Jezgra\Komponente\Servis_Posluzitelj;

/**
 * ### Poslužitelj za testiranje
 * @since 0.3.0.pre-alpha.M3
 *
 * @property-read string $test_atribut
 *
 * @method $this test_atribut(string $test_atribut)
 *
 * @package Sustav\Jezgra
 */
final class Test extends Servis_Posluzitelj {

    protected string $test_atribut;

    public function napravi ():object {

        return (new Servis_Kontejner($this))->dohvati();

    }

}