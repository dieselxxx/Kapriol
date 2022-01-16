<?php declare(strict_types = 1);

/**
 * Datoteka za SLIKA sadržaj
 * @since 0.6.1.alpha.M6
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Sadrzaj
 */

namespace FireHub\Jezgra\Sadrzaj\Vrste;

use FireHub\Jezgra\Sadrzaj\Vrsta_Interface;

/**
 * ### Klasa za SLIKA sadržaj
 * @since 0.6.1.alpha.M6
 *
 * @package Sustav\Sadrzaj
 */
final class SLIKA implements Vrsta_Interface {

    /**
     * @inheritDoc
     */
    public function __construct (
        private array $podatci
    ) {}

    /**
     * @inheritDoc
     */
    public function ispisi ():string {

        return '';

    }

}