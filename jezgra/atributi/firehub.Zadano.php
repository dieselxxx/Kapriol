<?php declare(strict_types = 1);

/**
 * Datoteka za atribut zadane vrijednosti opcije servis poslužitelja
 * @since 0.3.5.pre-alpha.M3
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
 * ### Atribut zadane vrijednosti opcije servis poslužitelja
 * @since 0.3.5.pre-alpha.M3
 *
 * @package Sustav\Jezgra
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class Zadano implements Atribut {

    public function __construct (
        public readonly string $vrijednost
    ) {}

    /**
     * @inheritDoc
     */
    public function obradi ():bool {

        return true;

    }

}