<?php declare(strict_types = 1);

/**
 * Datoteka ugovora poslužitelja baze podataka
 * @since 0.5.1.pre-alpha.M5
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\BazaPodataka;

use FireHub\Jezgra\Komponente\Servis_Interface;

/**
 * ### Osnovni interface za poslužitelja baze podataka
 * @since 0.5.1.pre-alpha.M5
 *
 * @package Sustav\Jezgra
 */
interface BazaPodataka_Interface extends Servis_Interface {

    /**
     * ### Vraća prvi redak iz baze podataka kao niz
     * @since 0.5.1.pre-alpha.M5
     *
     * @return array<string, mixed> Niz rezultata iz retka.
     */
    public function redak ():array;

    /**
     * ### Vraća prvi redak iz baze podataka kao objekt
     * @since 0.5.1.pre-alpha.M5
     *
     * @return object Rezultat kao objekt.
     */
    public function objekt ():object;

    /**
     * ### Vraća sve rezultate iz baze podataka kao niz
     * @since 0.5.1.pre-alpha.M5
     *
     * @return array<int, array<string, mixed>> Rezultat kao niz redaka.
     */
    public function niz ():array;

    /**
     * ### Lista odrađenih naredbi transakcije
     * @since 0.5.1.pre-alpha.M5
     *
     * @return array<int, bool> Lista odrađenih naredbi transakcije.
     */
    public function rezultat ():array;

}