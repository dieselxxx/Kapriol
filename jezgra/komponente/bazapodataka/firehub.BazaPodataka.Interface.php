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
     * @return array<string, mixed>|false Niz rezultata iz retka ili false ako nema više redaka.
     */
    public function redak ():array|false;

    /**
     * ### Vraća prvi redak iz baze podataka kao objekt
     * @since 0.5.1.pre-alpha.M5
     *
     * @return object|false Rezultat kao objekt ili false ako nema više redaka.
     */
    public function objekt ():object|false;

    /**
     * ### Vraća sve rezultate iz baze podataka kao niz
     * @since 0.5.1.pre-alpha.M5
     *
     * @return array<int, array<string, mixed>>|false Rezultat kao niz redaka ili false ako nema rezultata.
     */
    public function niz ():array|false;

    /**
     * ### Lista odrađenih naredbi transakcije
     * @since 0.5.1.pre-alpha.M5
     *
     * @return array<int, bool> Lista odrađenih naredbi transakcije.
     */
    public function rezultat ():array;

    /**
     * ### Broj zapisa rezultata
     * @since 0.6.0.alpha.M1
     *
     * @return int Broj zapisa rezultata.
     */
    public function broj_zapisa ():int;

}