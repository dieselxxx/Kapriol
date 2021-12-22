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

use FireHub\Jezgra\Komponente\Log\Log;
use FireHub\Jezgra\Komponente\Log\Enumeratori\Level;
use FireHub\Jezgra\Greske\Kernel_Greska;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;

/**
 * ### Osnovna klasa Kernel za pokretanje upita
 * @since 0.2.3.pre-alpha.M2
 *
 * @package Sustav\Jezgra
 */
abstract class Kernel {

    /**
     * ### Konstruktor.
     * @since 0.2.3.pre-alpha.M2
     *
     * @param Zahtjev $zahtjev <p>
     * Zahtjev.
     * </p>
     */
    abstract function __construct (Zahtjev $zahtjev);

    /**
     * ### Pokreni Kernel
     *
     * Ova metoda služi za pokretanje sustava i jedina je
     * metoda izložena datotekama koje pokreću sustav.
     * @since 0.2.3.pre-alpha.M2
     *
     * @return Odgovor Instanca Odgovora.
     */
    abstract public function pokreni ():Odgovor;

    /**
     * ### Učitaj datoteku sa pomoćnim funkcijama
     * @since 0.3.1.pre-alpha.M3
     *
     * @throws Kernel_Greska Ukoliko se ne mogu učitati pomagači.
     * @throws Kontejner_Greska Ako ne postoji objekt sa nazivom klase ili ukoliko nije uspješno obrađen atribut.
     *
     * @return self Kernel objekt.
     */
    protected function pomagaci ():self {

        if (!include(FIREHUB_ROOT . 'jezgra' . RAZDJELNIK_MAPE . 'firehub.Pomagaci.php')) {

            (new Log)->level(Level::KRITICNO)->poruka(sprintf(_('Pomagači: %s, se ne mogu učitati'), FIREHUB_ROOT . 'jezgra' . RAZDJELNIK_MAPE . 'firehub.Pomagaci.php'))->napravi()->posalji();
            throw new Kernel_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru.'));

        }

        return $this;

    }

}