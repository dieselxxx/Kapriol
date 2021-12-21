<?php declare(strict_types = 1);

/**
 * Datoteka sa pomoćnim funkcijama potrebnim
 * za više mjesta u sustavu i aplikacijama
 * @since 0.3.1.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

use FireHub\Jezgra\Komponente\Log\Log;
use FireHub\Jezgra\Komponente\Log\Enumeratori\Level;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;

if (!function_exists('zapisnik')) {
    /**
     * ### Pošalji log zapis
     * @since 0.3.1.pre-alpha.M3
     *
     * @param Level $level <p>
     * Level loga prema kojem će se pokrenuti dostavljači za log.
     * </p>
     * @param string $poruka <p>
     * Poruka koja će se zabilježiti u log.
     * </p>
     *
     * @throws Kontejner_Greska Ako ne postoji objekt sa nazivom klase ili ukoliko nije uspješno obrađen atribut.
     *
     * @return bool True ukoliko je zapisan log, u suprotnome False.
     */
    function zapisnik (Level $level, string $poruka):bool {

        return (new Log)->level($level)->poruka($poruka)->napravi()->posalji();

    }
}