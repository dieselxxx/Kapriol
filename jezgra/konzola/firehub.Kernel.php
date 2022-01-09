<?php declare(strict_types = 1);

/**
 * Osnovna datoteka za pokretanje upita konzole
 * @since 0.2.3.pre-alpha.M2
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Konzola
 */

namespace FireHub\Jezgra\Konzola;

use FireHub\Jezgra\Kernel as OsnovniKernel;
use FireHub\Jezgra\Zahtjev as Zahtjev;
use FireHub\Jezgra\Konzola\Zahtjev as Konzola_Zahtjev;
use FireHub\Jezgra\Konzola\Odgovor as Konzola_Odgovor;
use FireHub\Jezgra\Komponente\Log\Log;
use FireHub\Jezgra\Komponente\Log\Servisi\AutoPosalji;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;
use Throwable;

/**
 * ### Osnovna klasa Kernel za upita konzole
 * @since 0.2.3.pre-alpha.M2
 *
 * @package Sustav\Konzola
 */
final class Kernel extends OsnovniKernel {

    /**
     * {@inheritDoc}
     *
     * @param Konzola_Zahtjev $zahtjev <p>
     * Zahtjev.
     * </p>
     */
    public function __construct (private Zahtjev $zahtjev) {}

    /**
     * {@inheritDoc}
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     */
    public function pokreni ():Konzola_Odgovor {

        try {

            return $this
                ->posrednici(include FIREHUB_ROOT . 'konfiguracija' . RAZDJELNIK_MAPE . 'posrednici.php', 'konzola')
                ->pomagaci()
                ->ucitajEnv(FIREHUB_ROOT . '.env')
                ->odgovor();

        } catch (Throwable $objekt) {

            (new Log)->servis(AutoPosalji::class)->greska($objekt)->napravi()->posalji();

        }

    }

    /**
     * ### Konzola odgovor.
     * @since 0.2.6.pre-alpha.M2
     *
     * @return Konzola_Odgovor Odgovor za konzolu.
     */
    private function odgovor ():Konzola_Odgovor {

        return (new Konzola_Odgovor());

    }

}