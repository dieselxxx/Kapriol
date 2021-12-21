<?php declare(strict_types = 1);

/**
 * Datoteka za Konzola odgovor
 *
 * Ova datoteka prikuplja i obrađuje sve informacije o HTTP odgovoru.
 * @since 0.2.6.pre-alpha.M2
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Konzola
 */

namespace FireHub\Jezgra\Konzola;

use FireHub\Jezgra\Odgovor as Odgovor_Interface;
use FireHub\Jezgra\Komponente\Log\Log;
use FireHub\Jezgra\Komponente\Log\Servisi\AutoPosalji;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;
use Throwable;

/**
 * ### Konzola odgovor
 *
 * Klasa namjenjana za upravljenje i obrađivanje svih
 * Konzola odgovora od sustava i aplikacije.
 * @since 0.2.6.pre-alpha.M2
 *
 * @package Sustav\Konzola
 */
final class Odgovor implements Odgovor_Interface {

    /**
     * {@inheritDoc}
     *
     * @throws Kontejner_Greska Ako ne postoji objekt sa nazivom klase ili ukoliko nije uspješno obrađen atribut.
     */
    public function sadrzaj ():string {

        ob_start('ob_gzhandler');

        try {

            return round(memory_get_peak_usage()/1048576, 2) . ' mb';

        } catch (Throwable $objekt) {

            (new Log)->servis(AutoPosalji::class)->greska($objekt)->napravi()->posalji();

            return '';

        }

    }

}