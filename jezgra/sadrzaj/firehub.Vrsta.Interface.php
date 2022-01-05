<?php declare(strict_types = 1);

/**
 * Datoteka za interface vrste sadržaja
 * @since 0.4.4.pre-alpha.M4
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Sadrzaj
 */

namespace FireHub\Jezgra\Sadrzaj;

/**
 * ### Interface za vrste sadržaj
 * @since 0.4.4.pre-alpha.M4
 *
 * @package Sustav\Sadrzaj
 */
interface Vrsta_Interface {

    /**
     * ### Konstruktor
     * @since 0.4.4.pre-alpha.M4
     *
     * @param array<string, string|int> $podatci <p>
     * Podatci koje treba prenijeti u sadržaj.
     * </p>
     */
    public function __construct (array $podatci);

    /**
     * ### Ispiši sadržaj
     * @since 0.4.4.pre-alpha.M4
     *
     * @return string Ispis sadržaja.
     */
    public function ispisi ():string;

}