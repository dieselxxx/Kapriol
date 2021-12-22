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
use FireHub\Jezgra\Komponente\Log\Enumeratori\Level;
use FireHub\Jezgra\Greske\Greska;
use Throwable;

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

        return array_walk(
            $this->posluzitelj->dostavljaci,
            function (Dostavljac $dostavljac):Dostavljac {

                return (new $dostavljac)
                    ->otvori()
                    ->zapisi(
                        get_class($this->posluzitelj->greska),
                        $this->level($this->posluzitelj->greska)->value,
                        $this->level($this->posluzitelj->greska)->name,
                        $this->posluzitelj->greska->getCode(),
                        $this->posluzitelj->greska->getFile(),
                        $this->posluzitelj->greska->getLine(),
                        $this->posluzitelj->greska->getMessage(),
                        debug_backtrace()
                    )
                    ->zatvori();

            }
        );

    }

    /**
     * ### Level objekta u ovisnosti od vrste Throwable greške
     * @since 0.3.1.pre-alpha.M3
     *
     * @param Throwable $objekt <p>
     * Objekt koji je stigao preko Throwable interace-a.
     * </p>
     *
     * @return Level Level greške.
     */
    private function level (Throwable $objekt):Level {

        return match (true) {

            $objekt instanceof Greska => Level::INFO,

            $objekt instanceof \ArithmeticError,
                $objekt instanceof \AssertionError,
                $objekt instanceof \ClosedGeneratorException,
                $objekt instanceof \ValueError => Level::GRESKA,

            $objekt instanceof \CompileError,
                $objekt instanceof \TypeError,
                $objekt instanceof \UnhandledMatchError,
                $objekt instanceof \DOMException,
                $objekt instanceof \ErrorException,
                $objekt instanceof \JsonException,
                $objekt instanceof \LogicException,
                $objekt instanceof \PharException,
                $objekt instanceof \ReflectionException,
                $objekt instanceof \RuntimeException,
                $objekt instanceof \SoapFault => Level::KRITICNO,

            default => Level::KRITICNO

        };

    }

}