<?php declare(strict_types = 1);

/**
 * Datoteka interface-a za servise konfiguracije
 * @since 0.3.5.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Konfiguracija
 */

namespace FireHub\Jezgra\Komponente\Konfiguracija;

use FireHub\Jezgra\Komponente\Servis_Interface;

/**
 * ### Interface-a za servise konfiguracije
 * @since 0.3.5.pre-alpha.M3
 *
 * @package Sustav\Konfiguracija
 */
interface Konfiguracija_Interface extends Servis_Interface {

    /**
     * ### Dohvati konfiguracijski zapis
     * @since 0.3.5.pre-alpha.M3
     *
     * @param string $naziv <p>
     * Naziv konfiguracijskog zapisa.
     * <p>
     *
     * @return string|int|float|bool|array|null Vrijednosti konfiguracijskog zapisa.
     */
    public function dohvati (string $naziv):string|int|float|bool|array|null;

    /**
     * ### Provjerava dali postoji konfiguracijski zapis
     * @since 0.3.5.pre-alpha.M3
     *
     * @param string $naziv <p>
     * Naziv konfiguracijskog zapisa.
     * <p>
     *
     * @return bool Da li postoji zapis.
     */
    public function postoji (string $naziv):bool;

    /**
     * ### Dodaj konfiguracijski zapis ukoliko ne postoji
     * @since 0.3.5.pre-alpha.M3
     *
     * @param string $naziv <p>
     * Naziv konfiguracijskog zapisa.
     * <p>
     * @param string|int|float|bool|array|null $vrijednost <p>
     * Vrijednosti konfiguracijskog zapisa.
     * <p>
     *
     * @return bool Da li je uspješno dodan zapis.
     */
    public function dodaj (string $naziv, string|int|float|bool|array|null $vrijednost):bool;

}