<?php declare(strict_types=1);

/**
 * Datoteka za interface čitača konfiguracijskih podataka
 * @since 0.3.5.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Konfiguracija
 */

namespace FireHub\Jezgra\Komponente\Konfiguracija\Servisi;

/**
 * ### Interface za čitače konfiguracijskih podataka
 * @since 0.3.5.pre-alpha.M3
 *
 * @package Sustav\Konfiguracija
 */
interface Citac_Interface {

    /**
     * ### Dohvati konfiguracijski niz
     * @since 0.3.5.pre-alpha.M3
     *
     * @param string $putanja <p>
     * Puna putanja do konfiguracijske datoteke.
     * </p>
     *
     * @return array<string, mixed> Konfiguracijski niz.
     */
    public function ucitaj (string $putanja):array;

}