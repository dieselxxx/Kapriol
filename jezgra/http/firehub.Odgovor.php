<?php declare(strict_types = 1);

/**
 * Datoteka za HTTP odgovor
 *
 * Ova datoteka prikuplja i obrađuje sve informacije o HTTP odgovoru.
 * @since 0.2.6.pre-alpha.M2
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\HTTP
 */

namespace FireHub\Jezgra\HTTP;

use FireHub\Jezgra\Odgovor as Odgovor_Interface;

/**
 * ### HTTP odgovor
 *
 * Klasa namjenjana za upravljenje i obrađivanje svih
 * HTTP odgovora od sustava i aplikacije.
 * @since 0.2.6.pre-alpha.M2
 *
 * @package Sustav\HTTP
 */
final class Odgovor implements Odgovor_Interface {

    /**
     * @inheritDoc
     */
    public function sadrzaj ():string {

        return round(memory_get_peak_usage()/1048576, 2) . ' mb';

    }

}