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
    public function pokreni ():self {

        try {

            return $this;

        } catch (Throwable $objekt) {

            var_dump($objekt);

        }

    }

}