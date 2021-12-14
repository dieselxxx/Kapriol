<?php declare(strict_types = 1);

/**
 * Datoteka za enumerator za dostupne vrste Kernel-a
 * @since 0.2.3.pre-alpha.M2
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Enumeratori;

use FireHub\Jezgra\HTTP\Zahtjev as HTTP_Zahtjev;
use FireHub\Jezgra\Konzola\Zahtjev as Konzola_Zahtjev;

/**
 * ### Enumerator za dostupne vrste Kernel-a
 * @since 0.2.3.pre-alpha.M2
 *
 * @package Sustav\Jezgra
 */
enum Kernel:string {

    case HTTP = '\\FireHub\\Jezgra\\HTTP\\Kernel';
    case KONZOLA = '\\FireHub\\Jezgra\\Konzola\\Kernel';

    /**
     * ### Klasa za obradu zahtjeva Kernela.
     * @since 0.2.3.pre-alpha.M2
     *
     * @return string FQN FireHub\Jezgra\Zahtjev::class.
     */
    public function zahtjev ():string {

        return match ($this) {
            self::HTTP => HTTP_Zahtjev::class,
            self::KONZOLA => Konzola_Zahtjev::class
        };

    }

}