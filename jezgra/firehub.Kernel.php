<?php declare(strict_types = 1);

/**
 * Osnovna datoteka za pokretanje upita
 * @since 0.2.3.pre-alpha.M2
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra;

/**
 * ### Osnovna klasa Kernel za pokretanje upita
 * @since 0.2.3.pre-alpha.M2
 *
 * @package Sustav\Jezgra
 */
abstract class Kernel {

    /**
     * ### Pokreni Kernel
     *
     * Ova metoda služi za pokretanje sustava i jedina je
     * metoda izložena datotekama koje pokreću sustav.
     * @since 0.2.3.pre-alpha.M2
     *
     * @return self Instanca objekta.
     */
    abstract public function pokreni ():self;

}