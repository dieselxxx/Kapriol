<?php declare(strict_types = 1);

/**
 * Datoteka za enumerator za dostupne HTTP metode
 * @since 0.6.0.alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2021 Grafotisak d.o.o.
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\HTTP
 */

namespace FireHub\Jezgra\Enumeratori;

use FireHub\Jezgra\Enumerator;
use FireHub\Jezgra\HTTP\Kernel as HTTP_Kernel;
use FireHub\Jezgra\Konzola\Kernel as HTTP_Konzola;
use FireHub\Jezgra\HTTP\Zahtjev as HTTP_Zahtjev;
use FireHub\Jezgra\Konzola\Zahtjev as Konzola_Zahtjev;

/**
 * Enumerator za dostupne HTTP metode
 * @since 0.6.0.alpha.M1
 *
 * @method static self HTTP ()
 * @method static self KONZOLA ()
 *
 * @package Sustav\HTTP
 */
final class Kernel_Enumerator extends Enumerator {

    protected const HTTP = 'HTTP';
    protected const KONZOLA = 'KONZOLA';

    /**
     * ### Klasa za pokt.
     * @since 0.2.5.pre-alpha.M2
     *
     * @return string FQN Kernel.
     */
    public function kernel ():string {

        return match ($this->name) {
            self::HTTP => HTTP_Kernel::class,
            self::KONZOLA => HTTP_Konzola::class
        };

    }

    /**
     * ### Klasa za obradu zahtjeva Kernela.
     * @since 0.2.5.pre-alpha.M2
     *
     * @return string FQN Zahtjev.
     */
    public function zahtjev ():string {

        return match ($this->name) {
            self::HTTP => HTTP_Zahtjev::class,
            self::KONZOLA => Konzola_Zahtjev::class
        };

    }

}