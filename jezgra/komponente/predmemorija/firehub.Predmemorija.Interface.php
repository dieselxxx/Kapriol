<?php declare(strict_types = 1);

/**
 * Datoteka ugovora poslužitelja predmemorije
 * @since 0.5.0.pre-alpha.M5
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\Predmemorija;

use FireHub\Jezgra\Komponente\Servis_Interface;

/**
 * ### Osnovni interface za poslužitelja predmemorije
 * @since 0.5.0.pre-alpha.M5
 *
 * @package Sustav\Jezgra
 */
interface Predmemorija_Interface extends Servis_Interface {

    /**
     * ### Dodaj vrijednosti na server predmemorije
     * @since 0.5.0.pre-alpha.M5
     *
     * @param string $kljuc <p>
     * Naziv stavke koja se sprema.
     * </p>
     * @param mixed $vrijednost <p>
     * Vrijednost stavke koja se sprema.
     * </p>
     * @param bool $kompresija [optional] <p>
     * Da li se stavka kompresira.
     * </p>
     * @param int $vrijeme [optional] <p>
     * Broj sekundi nakon kojih se briše zapis. Max 2592000 (30 dana), ili 0 za beskonačno vrijeme.
     * </p>
     *
     * @return bool Da li je dodan zapis.
     */
    public function dodaj (string $kljuc, mixed $vrijednost, bool $kompresija = false, int $vrijeme = 0):bool;

    /**
     * ### Zapiši vrijednosti na server predmemorije
     *
     * Metoda radi isto kao i kombinacija metoda "dodaj" i "zamijeni".
     * @since 0.5.0.pre-alpha.M5
     *
     * @param string $kljuc <p>
     * Naziv stavke koja se sprema.
     * </p>
     * @param mixed $vrijednost <p>
     * Vrijednost stavke koja se sprema.
     * </p>
     * @param bool $kompresija [optional] <p>
     * Da li se stavka kompresira.
     * </p>
     * @param int $vrijeme [optional] <p>
     * Broj sekundi nakon kojih se briše zapis. Max 2592000 (30 dana), ili 0 za beskonačno vrijeme.
     * </p>
     *
     * @return bool Da li je zapisan zapis.
     */
    public function zapisi (string $kljuc, mixed $vrijednost, bool $kompresija = false, int $vrijeme = 0):bool;

    /**
     *
     * ### Zamijeni vrijednosti na server predmemorije
     * @since 0.5.0.pre-alpha.M5
     *
     * @param string $kljuc <p>
     * Naziv stavke koja se sprema.
     * </p>
     * @param mixed $vrijednost <p>
     * Vrijednost stavke koja se sprema.
     * </p>
     * @param bool $kompresija [optional] <p>
     * Da li se stavka kompresira.
     * </p>
     * @param int $vrijeme [optional] <p>
     * Broj sekundi nakon kojih se briše zapis. Max 2592000 (30 dana), ili 0 za beskonačno vrijeme.
     * </p>
     *
     * @return bool Da li je zamijenjen zapis.
     */
    public function zamijeni (string $kljuc, mixed $vrijednost, bool $kompresija = false, int $vrijeme = 0):bool;

    /**
     * ### Dohvati vrijednosti iz servera predmemorije
     * @since 0.5.0.pre-alpha.M5
     *
     * @param array|string $kljuc <p>
     * KLjuč iz predmemorije.
     * </p>
     *
     * @return string|array|false Vrijednost iz predmemorije ukoliko postoji, false ako ne postoji.
     */
    public function dohvati (array|string $kljuc):string|array|false;

    /**
     * ### Izbriši vrijednosti iz servera predmemorije
     * @since 0.5.0.pre-alpha.M5
     *
     * @param string $kljuc <p>
     * KLjuč iz predmemorije.
     * </p>
     *
     * @return bool Da li je izbrisan zapis.
     */
    public function izbrisi (string $kljuc):bool;

    /**
     * ### Izbriši sve zapise iz servera predmemorije
     * @since 0.5.0.pre-alpha.M5
     *
     * @return bool Da li su očišćeni zapisi.
     */
    public function ocisti ():bool;

    /**
     * ### Ispisuje sve zapise iz servera predmemorije
     * @since 0.5.0.pre-alpha.M5
     *
     * @return array Svi zapisi.
     */
    public function sve ():array;

    /**
     * ### Slanje komadni na server predmemorije
     * @since 0.5.0.pre-alpha.M5
     *
     * @param string $komadna <p>
     * Komadna.
     * </p>
     *
     * @return string Tekst komande.
     */
    public function komanda (string $komadna):string;

}