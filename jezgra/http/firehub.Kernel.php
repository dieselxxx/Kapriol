<?php declare(strict_types = 1);

/**
 * Osnovna datoteka za pokretanje HTTP upita
 * @since 0.2.3.pre-alpha.M2
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\HTTP
 */

namespace FireHub\Jezgra\HTTP;

use FireHub\Jezgra\Kernel as OsnovniKernel;;

use FireHub\Jezgra\Zahtjev;
use FireHub\Jezgra\HTTP\Odgovor as HTTP_Odgovor;
use FireHub\Jezgra\Komponente\Log\Log;
use FireHub\Jezgra\Komponente\Log\Servisi\AutoPosalji;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;
use Throwable;

/**
 * ### Osnovna klasa Kernel za pokretanje HTTP upita
 * @since 0.2.3.pre-alpha.M2
 *
 * @package Sustav\HTTP
 */
final class Kernel extends OsnovniKernel {

    /**
     * @inheritDoc
     */
    public function __construct (private Zahtjev $zahtjev) {}

    /**
     * {@inheritDoc}
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     */
    public function pokreni ():HTTP_Odgovor {

        try {

            return $this
                ->pomagaci()
                ->odgovor();

        } catch (Throwable $objekt) {

            (new Log)->servis(AutoPosalji::class)->greska($objekt)->napravi()->posalji();

        }

    }

    /**
     * ### HTTP odgovor.
     * @since 0.2.6.pre-alpha.M2
     *
     * @return HTTP_Odgovor Odgovor za HTTP.
     */
    private function odgovor ():HTTP_Odgovor {

        return (new HTTP_Odgovor());

    }

}