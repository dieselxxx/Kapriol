<?php declare(strict_types = 1);

/**
 * Datoteka posrednika za gušenje web zahtjeva
 * @since 0.4.0.pre-alpha.M4
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Posrednici
 */

namespace FireHub\Jezgra\Posrednici;

/**
 * ### Posrednik za gušenje web zahtjeva
 * @since 0.4.0.pre-alpha.M4
 *
 * @package Sustav\Posrednici
 */
final class Gusenje_Posrednik implements Posrednik {

    /**
     * @inheritDoc
     */
    public function obradi ():bool {

        // spavaj
        usleep(500_000);

        return true;

    }

}