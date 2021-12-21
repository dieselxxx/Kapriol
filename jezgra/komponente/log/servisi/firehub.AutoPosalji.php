<?php declare(strict_types = 1);

/**
 * Datoteka za automatsko slanje logova preko Throwable interface-a
 * @since 0.3.1.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\Log\Servisi;

use FireHub\Jezgra\Komponente\Log\Log;
use FireHub\Jezgra\Komponente\Log\Log_Interface;

/**
 * ### Servis za automatsko slanje logova preko Throwable interface-a
 * @since 0.3.1.pre-alpha.M3
 *
 * @package Sustav\Jezgra
 */
final class AutoPosalji implements Log_Interface {

    /**
     * ### Konstruktor
     * @since 0.3.1.pre-alpha.M3
     *
     * @param Log $posluzitelj <p>
     * Poslužitelj servisa.
     * </p>
     */
    public function __construct (
        private Log $posluzitelj
    ) {}

    /**
     * @inheritDoc
     */
    public function posalji ():bool {

        var_dump($this);

        return true;

    }

}