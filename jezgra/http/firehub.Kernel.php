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

use FireHub\Jezgra\Kernel as OsnovniKernel;
use FireHub\Jezgra\HTTP\Zahtjev as HTTP_Zahtjev;
use Throwable;

/**
 * ### Osnovna klasa Kernel za pokretanje HTTP upita
 * @since 0.2.3.pre-alpha.M2
 *
 * @package Sustav\HTTP
 */
final class Kernel extends OsnovniKernel {

    /**
     * Konstruktor.
     * @since 0.2.3.pre-alpha.M2
     *
     * @param HTTP_Zahtjev $http_zahtjev <p>
     * HTTP zahtjev.
     * </p>
     */
    public function __construct (private HTTP_Zahtjev $http_zahtjev) {

        var_dump($http_zahtjev);

    }

    /**
     * @inheritDoc
     */
    public function pokreni ():self {

        try {

            return $this;

        } catch (Throwable $objekt) {

            var_dump($objekt);

        }

    }

}