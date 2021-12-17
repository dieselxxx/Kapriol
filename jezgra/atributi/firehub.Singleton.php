<?php declare(strict_types = 1);

/**
 * Datoteka za atribut singleton objekata
 * @since 0.3.0.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Atributi;

use Attribute;

/**
 * ### Atribut za sve singleton servise
 *
 * Atribut osigurava da je dostupna samo jedna instanca objekta.
 * @since 0.3.0.pre-alpha.M3
 *
 * @package Sustav\Jezgra
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class Singleton implements Atribut {

    /**
     * @inheritDoc
     */
    public function obradi ():bool {

        return true;

    }

}