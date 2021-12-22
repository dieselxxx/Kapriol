<?php declare(strict_types = 1);

/**
 * Datoteka interface-a za dot servise
 * @since 0.3.2.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\Dot;

use FireHub\Jezgra\Komponente\Servis_Interface;

/**
 * ### Interface-a za dot servise
 * @since 0.3.2.pre-alpha.M3
 *
 * @package Sustav\Jezgra
 */
interface Dot_Interface extends Servis_Interface {

    /**
     * ### Dohvati sve Dot zapise
     * @since 0.3.2.pre-alpha.M3
     *
     * @return array<string, mixed> Niz zapisa dot servisa.
     */
    public function sve ():array;

    /**
     * ### Dohvati Dot zapis
     * @since 0.3.2.pre-alpha.M3
     *
     * @param string $vrijednost <p>
     * Vrijednost za dohvatiti.
     * </p>
     *
     * @return mixed Rezultat dot zapisa.
     */
    public function dohvati (string $vrijednost):mixed;

    /**
     * ### Dodaj Dot zapis ukoliko ne postoji
     * @since 0.3.2.pre-alpha.M3
     *
     * @param string $zapis <p>
     * Zapis koji treba dodati.
     * </p>
     * @param mixed $vrijednost <p>
     * Vrijednost zapisa koji treba dodati.
     * </p>
     *
     * @return bool True ukoliko je dodan zapis.
     */
    public function dodaj (string $zapis, mixed $vrijednost):bool;

    /**
     * ### Postavi novi ili zamijeni postojeći Dot zapis
     * @since 0.3.2.pre-alpha.M3
     *
     * @param string $zapis <p>
     * Zapis koji treba postaviti.
     * </p>
     * @param mixed $vrijednost <p>
     * Vrijednost zapisa koji treba dodati.
     * </p>
     *
     * @return bool True ukoliko je postavljen zapis.
     */
    public function postavi (string $zapis, mixed $vrijednost):bool;

    /**
     * ### Zamijeni postojeći Dot zapis
     * @since 0.3.2.pre-alpha.M3
     *
     * @param string $zapis <p>
     * Zapis koji treba zamijeniti.
     * </p>
     * @param mixed $vrijednost <p>
     * Vrijednost zapisa koji treba dodati.
     * </p>
     *
     * @return bool True ukoliko je zamijenjen zapis.
     */
    public function zamijeni (string $zapis, mixed $vrijednost):bool;

    /**
     * ### Očisti vrijednost Dot zapisa
     * @since 0.3.2.pre-alpha.M3
     *
     * @param string $zapis <p>
     * Zapis koji treba očistiti.
     * </p>
     *
     * @return bool True ukoliko je očišćen zapis.
     */
    public function ocisti (string $zapis):bool;

    /**
     * ### Izbriši vrijednost Dot zapisa
     * @since 0.3.2.pre-alpha.M3
     *
     * @param string $zapis <p>
     * Zapis koji treba izbrisati.
     * </p>
     *
     * @return bool True ukoliko je izbrisan zapis.
     */
    public function izbrisi (string $zapis):bool;

    /**
     * ### Provjerava da li postoji Dot zapis
     * @since 0.3.2.pre-alpha.M3
     *
     * @param string $zapis <p>
     * Zapis koji treba provjeriti.
     * </p>
     *
     * @return bool True ako postoji zapis, false ako ne postoji.
     */
    public function postoji (string $zapis):bool;

}