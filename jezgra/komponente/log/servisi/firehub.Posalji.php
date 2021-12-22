<?php declare(strict_types = 1);

/**
 * Datoteka za ručno slanje logova
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
 * ### Servis za ručno slanje logova
 * @since 0.3.1.pre-alpha.M3
 *
 * @package Sustav\Jezgra
 */
final class Posalji implements Log_Interface {

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

        return array_walk(
            $this->posluzitelj->dostavljaci,
            function (Dostavljac $dostavljac):Dostavljac {

                return (new $dostavljac)
                    ->otvori()
                    ->zapisi(
                        'RučniLog',
                        $this->posluzitelj->level->value,
                        $this->posluzitelj->level->name,
                        $this->posluzitelj->kod,
                        debug_backtrace()[2]['file'],
                        debug_backtrace()[2]['line'],
                        $this->posluzitelj->poruka,
                        debug_backtrace()
                    )
                    ->zatvori();

            }
        );

    }

}