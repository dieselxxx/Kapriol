<?php declare(strict_types = 1);

/**
 * Servis za pozivanje potrebnih metoda konfiguracije
 * @since 0.3.5.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework.
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Konfiguracija
 */

namespace FireHub\Jezgra\Komponente\Konfiguracija\Servisi;

use FireHub\Jezgra\Komponente\Konfiguracija\Greske\KonfiguracijaMetoda_Greska;
use FireHub\Jezgra\Komponente\Log\Enumeratori\Level;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;
use UnitEnum;

/**
 * ### Servis za pozivanje potrebnih metoda konfiguracije
 * @since 0.3.5.pre-alpha.M3
 *
 * @package Sustav\Konfiguracija
 */
final class Metode_PodServis {

    /**
     * ### Niz kojim upravlja servis
     * @var array<string, mixed>
     */
    private array $konfiguracija = [];

    /**
     * ### Modificirani niz koje je nastao od konfiguracijskog niza
     * @var array<string, mixed>
     */
    private array $rezultat = [];

    /**
     * ### Pozovi sve metode koje su navedene u konfiguracijskoj datoteki
     * @since 0.3.5.pre-alpha.M3
     *
     * @param array<string, mixed> $konfiguracija <p>
     * Konfiguracijski niz iz kojeg se pokreći metode.
     * </p>
     *
     * @return self Instanca Metoda Pod-Servisa.
     */
    public function pokreni (array $konfiguracija):self {

        $this->konfiguracija = $konfiguracija;

        array_walk(
            $this->konfiguracija,
            function (array $vrijednost, string $kljuc):bool {

                return array_walk(
                    $vrijednost,
                    function (array|string|int|float|bool|UnitEnum $opcije, string $metoda) use ($kljuc):array|string|int|float|bool|UnitEnum {

                        // prikljuci kljuc trenutnoj metodi
                        $kljuc_metoda = $kljuc.'_'.$metoda;

                        if (method_exists(self::class, $kljuc_metoda)) {

                            if (is_array($opcije)) { // ako je array pozovi metodu i raspakiraj opcije

                                return $this->rezultat[$kljuc][$metoda] = $this->$kljuc_metoda(...$opcije);

                            }

                            // ako je string, int ili bool pozovi metodu sa opcijama
                            return $this->rezultat[$kljuc][$metoda] = $this->$kljuc_metoda($opcije);

                        }

                        // sve ostale opcije se zapisuju direktno u konfiguraciju
                        return $this->rezultat[$kljuc][$metoda] = $opcije;

                    }
                );

            }
        );

        return $this;

    }

    /**
     * ### Modificirani niz koje je nastao od konfiguracijskog niza
     * @since 0.3.5.pre-alpha.M3
     *
     * @return array<string, mixed> Modificirani niz.
     */
    public function rezultat ():array {

        return $this->rezultat;

    }

    /**
     * ### Provjerava preduvjete sustava
     * @since 0.3.5.pre-alpha.M3
     *
     * @param string|int ...$opcije <p>
     * Opcije preduvjeta sustava.
     * </p>
     *
     * @throws KonfiguracijaMetoda_Greska Ukoliko je verzija PHP-a manja od minimalne verzije koju zahjeva sustav.
     * @throws Kontejner_Greska Ukoliko se može spremiti instanca Log-a.
     *
     * @return bool True ukoliko je PHP verzija ispravna, False u suprotnome.
     */
    private function sustav_preduvjeti (string|int ...$opcije):bool {

        if (version_compare(PHP_VERSION, $opcije['php_verzija'], '<')) {

            zapisnik(Level::HITNO, sprintf(_('Sustav zahtjeva da verzija PHP-a mora biti najmanje %s'), $opcije['php_verzija']));
            throw new KonfiguracijaMetoda_Greska(sprintf(_('Sustav zahtjeva da verzija PHP-a mora biti najmanje %s'), $opcije['php_verzija']));

        }

        return true;

    }

    /**
     * ### Postavljanje vremenske zone sustava
     * @since 0.3.5.pre-alpha.M3
     *
     * @param string $opcija <p>
     * Opcije vremenske zone sustava.
     * </p>
     *
     * @throws KonfiguracijaMetoda_Greska Ukoliko vremenska zona nije ispravna.
     * @throws Kontejner_Greska Ukoliko se može spremiti instanca Log-a.
     *
     * @return string Naziv vremenske zone.
     */
    private function sustav_vremenskaZona (string $opcija):string {

        if (!date_default_timezone_set($opcija)) {

            zapisnik(Level::HITNO, sprintf(_('Zadana vremenska zona %s nije ispravna'), $opcija));
            throw new KonfiguracijaMetoda_Greska(sprintf(_('Zadana vremenska zona %s nije ispravna'), $opcija));

        }

        return $opcija;

    }

    /**
     * ### Provjerava predefinirane sistemske putanje
     * @since 0.3.5.pre-alpha.M3
     *
     * @param string ...$opcije <p>
     * Sistemske putanje sustava.
     * </p>
     *
     * @throws KonfiguracijaMetoda_Greska Ukoliko ne postoji mapa za stavku.
     * @throws Kontejner_Greska Ukoliko se može spremiti instanca Log-a.
     *
     * @return array<string, string> Sistemske putanje.
     */
    private function sustav_sistemskePutanje (string ...$opcije):array {

        // zamjena / u razdjelnik direktorija
        $firehub_putanje = array_map(
            static fn($naziv):string => FIREHUB_ROOT . implode(RAZDJELNIK_MAPE, explode('/', $naziv)),
            $opcije
        );

        // provjeri da li postoje navedene mape
        array_walk(
            $firehub_putanje,
            static function ($putanja, $stavka):void {
                if (!is_dir($putanja)) {
                    zapisnik(Level::HITNO, sprintf(_('Ne postoji mapa %s za stavku %s, aplikacija se ne može učitati!'), $putanja, $stavka));
                    throw new KonfiguracijaMetoda_Greska(sprintf(_('Ne postoji mapa %s za stavku %s, aplikacija se ne može učitati!'), $putanja, $stavka));
                }
            }
        );

        return $opcije;

    }

    /**
     * ### Provjerava nužne PHP ekstenzije
     * @since 0.3.5.pre-alpha.M3
     *
     * @param string ...$opcije <p>
     * PHP ekstenzije.
     * </p>
     *
     * @throws KonfiguracijaMetoda_Greska Ukoliko ekstenzija nije dostupna.
     * @throws Kontejner_Greska Ukoliko se može spremiti instanca Log-a.
     *
     * @return bool True ukoliko su sve ekstenzije dostupne.
     */
    private function php_ekstenzije (string ...$opcije):bool {

        array_walk(
            $opcije,
            static function ($ekstenzija):void {
                if (!extension_loaded($ekstenzija)) {
                    zapisnik(Level::HITNO, sprintf(_('Ekstenzija %s nije dostupna!'), $ekstenzija));
                    throw new KonfiguracijaMetoda_Greska(sprintf(_('Ekstenzija %s nije dostupna!'), $ekstenzija));
                }
            }
        );

        return true;

    }

    /**
     * ### Postavljanje zadanih PHP i PHP.ini postavki
     * @since 0.3.5.pre-alpha.M3
     *
     * @param string ...$opcije <p>
     * PHP postavke.
     * </p>
     *
     * @throws KonfiguracijaMetoda_Greska Ukoliko php.ini postavka ne postoji ili ima pogrešnu vrijednost.
     * @throws Kontejner_Greska Ukoliko se može spremiti instanca Log-a.
     *
     * @return bool True ukoliko se sve opcije uključe.
     */
    private function php_phpPostavke (string ...$opcije):bool {

        // ugasi sve PHP greške
        error_reporting(0);

        // postavi php.ini opcije
        array_walk(
            $opcije,
            static function ($php_postavka, $stavka):void {
                if (ini_set($stavka, $php_postavka) === false) {
                    zapisnik(Level::HITNO, sprintf(_('php.ini postavka %s ne postoji ili ima pogrešnu vrijednost za korištenje: %s!'), $stavka, $php_postavka));
                    throw new KonfiguracijaMetoda_Greska(sprintf(_('php.ini postavka %s ne postoji ili ima pogrešnu vrijednost za korištenje: %s!'), $stavka, $php_postavka));
                }
            }
        );

        return true;

    }

    /**
     * ### Uključivanje PHP debug-a
     * @since 0.3.5.pre-alpha.M3
     *
     * @param bool|array ...$opcije <p>
     * PHP debug postavke.
     * </p>
     *
     * @throws KonfiguracijaMetoda_Greska Ukoliko postavka ne postoji ili ima pogrešnu vrijednost.
     * @throws Kontejner_Greska Ukoliko se može spremiti instanca Log-a.
     *
     * @return bool True ukoliko se debug uključi.
     */
    private function debug_php (bool|array ...$opcije):bool {

        if ($opcije['upaljeno']) {

            // upali sve PHP greške
            error_reporting(E_ALL);

            // postavi php.ini opcije
            array_walk(
                $opcije['opcije'],
                static function ($opcija, $stavka):void {
                    if (ini_set($stavka, $opcija) === false) {
                        zapisnik(Level::HITNO, sprintf(_('php.ini postavka %s ne postoji ili ima pogrešnu vrijednost za korištenje: %s!'), $stavka, $opcija));
                        throw new KonfiguracijaMetoda_Greska(sprintf(_('php.ini postavka %s ne postoji ili ima pogrešnu vrijednost za korištenje: %s!'), $stavka, $opcija));
                    }
                }
            );

            return true;

        }

        return false;

    }

    /**
     * ### Uključivanje debug-a sustava
     * @since 0.3.5.pre-alpha.M3
     *
     * @param bool|array ...$opcije <p>
     * Sustav debug postavke.
     * </p>
     *
     * @throws KonfiguracijaMetoda_Greska Ukoliko postavka ne postoji ili ima pogrešnu vrijednost.
     * @throws Kontejner_Greska Ukoliko se može spremiti instanca Log-a.
     *
     * @return bool True ukoliko se debug uključi.
     */
    private function debug_sustav (bool|array ...$opcije):bool {

        if ($opcije['upaljeno']) {

            // postavi php.ini opcije
            array_walk(
                $opcije['opcije'],
                static function ($opcija, $stavka):void {
                    if (ini_set($stavka, $opcija) === false) {
                        zapisnik(Level::HITNO, sprintf(_('php.ini postavka %s ne postoji ili ima pogrešnu vrijednost za korištenje: %s!'), $stavka, $opcija));
                        throw new KonfiguracijaMetoda_Greska(sprintf(_('php.ini postavka %s ne postoji ili ima pogrešnu vrijednost za korištenje: %s!'), $stavka, $opcija));
                    }
                }
            );

            return true;

        }

        return false;

    }

    /**
     * ### Provjera i postavljenje odabrane baze podataka
     * @since 0.5.1.pre-alpha.M5
     *
     * @param string $opcija <p>
     * Opcija baze podataka.
     * </p>
     *
     * @throws KonfiguracijaMetoda_Greska Ukoliko baza podataka nije dostupna kao izbor ili nedostaje ekstenzija.
     * @throws Kontejner_Greska Ukoliko se može spremiti instanca Log-a.
     *
     * @return string Opcija.
     */
    private function baza_podataka_server (string $opcija):string {

        // provjeri da li je odabrana baza podataka dostupna
        if (!array_key_exists($opcija, $this->rezultat['baza_podataka']['konekcije'])) {
            zapisnik(Level::HITNO, sprintf(_('Baza podataka %s nije dostupna kao izbor!'), $opcija));
            throw new KonfiguracijaMetoda_Greska(sprintf(_('Baza podataka %s nije dostupna kao izbor!'), $opcija));
        }

        // provjeri da li su sve ekstenzije učitane
        array_walk(
            $this->rezultat['baza_podataka']['konekcije'][$opcija]['ekstenzije'],
            static function ($ekstenzija) use ($opcija):void {
                if (!extension_loaded($ekstenzija)) {
                    zapisnik(Level::HITNO, sprintf(_('Ekstenzija potrebna %s za korištenje %s nije dostupna!'), $ekstenzija, $opcija));
                    throw new KonfiguracijaMetoda_Greska(sprintf(_('Ekstenzija potrebna %s za korištenje %s nije dostupna!'), $ekstenzija, $opcija));
                }
            }
        );

        // dodaj zadane parametre u rezultat koji nisu navedeni
        array_walk(
            $this->rezultat['baza_podataka']['konekcije'][$opcija]['parametri'],
            function ($vrijednost, $parametar):void {
                if (!array_key_exists($parametar, $this->rezultat['baza_podataka'])) {
                    $this->rezultat['baza_podataka'][$parametar] = $vrijednost;
                }
            }
        );

        return $opcija;

    }

    /**
     * ### Provjera i postavljenje odabrane predmemorije
     * @since 0.5.0.pre-alpha.M5
     *
     * @param string $opcija <p>
     * Opcija predmemorije.
     * </p>
     *
     * @throws KonfiguracijaMetoda_Greska Ukoliko predmemorija nije dostupna kao izbor ili nedostaje ekstenzija.
     * @throws Kontejner_Greska Ukoliko se može spremiti instanca Log-a.
     *
     * @return string Opcija.
     */
    private function predmemorija_server (string $opcija):string {

        // provjeri da li je odabrana predmemorija dostupna
        if (!array_key_exists($opcija, $this->rezultat['predmemorija']['konekcije'])) {
            zapisnik(Level::HITNO, sprintf(_('Predmemorija %s nije dostupna kao izbor!'), $opcija));
            throw new KonfiguracijaMetoda_Greska(sprintf(_('Predmemorija %s nije dostupna kao izbor!'), $opcija));
        }

        var_dump($this->rezultat['predmemorija']['ukljuceno']);

        // ukoliko je uključena predmemorija provjeri da li su sve ekstenzije učitane
        if ($this->rezultat['predmemorija']['ukljuceno']) {
            var_dump('x');
            array_walk(
                $this->rezultat['predmemorija']['konekcije'][$opcija]['ekstenzije'],
                static function ($ekstenzija) use ($opcija):void {
                    if (!extension_loaded($ekstenzija)) {
                        zapisnik(Level::HITNO, sprintf(_('Ekstenzija potrebna %s za korištenje %s nije dostupna!'), $ekstenzija, $opcija));
                        throw new KonfiguracijaMetoda_Greska(sprintf(_('Ekstenzija potrebna %s za korištenje %s nije dostupna!'), $ekstenzija, $opcija));
                    }
                }
            );
        }

        // dodaj zadane parametre u rezultat koji nisu navedeni
        array_walk(
            $this->rezultat['predmemorija']['konekcije'][$opcija]['parametri'],
            function ($vrijednost, $parametar):void {
                if (!array_key_exists($parametar, $this->rezultat['predmemorija'])) {
                    $this->rezultat['predmemorija'][$parametar] = $vrijednost;
                }
            }
        );

        return $opcija;

    }

    /**
     * ### Provjera i postavljenje odabrane vrste sesije
     * @since 0.5.3.pre-alpha.M5
     *
     * @param string $opcija <p>
     * Opcija sesije.
     * </p>
     *
     * @throws KonfiguracijaMetoda_Greska Ukoliko sesija nije dostupna kao izbor.
     * @throws Kontejner_Greska Ukoliko se može spremiti instanca objekt Log-a.
     *
     * @return string Opcija.
     */
    private function sesija_vrsta (string $opcija):string {

        // provjeri da li je odabrana sesija dostupna
        if (!array_key_exists($opcija, $this->rezultat['sesija']['vrste'])) {
            zapisnik(Level::HITNO, sprintf(_('Sesija %s nije dostupna kao izbor!'), $opcija));
            throw new KonfiguracijaMetoda_Greska(sprintf(_('Sesija %s nije dostupna kao izbor!'), $opcija));
        }

        // dodaj zadane parametre u rezultat koji nisu navedeni
        array_walk(
            $this->rezultat['sesija']['vrste'][$opcija]['parametri'],
            function ($vrijednost, $parametar):void {
                if (!array_key_exists($parametar, $this->rezultat['sesija'])) {
                    $this->rezultat['sesija'][$parametar] = $vrijednost;
                }
            }
        );

        return $opcija;

    }

    /**
     * ### Provjera i postavljenje odabrane vrste slika
     * @since 0.6.1.alpha.M6
     *
     * @param string $opcija <p>
     * Opcija sesije.
     * </p>
     *
     * @throws KonfiguracijaMetoda_Greska Ukoliko vrsta slika nije dostupna kao izbor.
     * @throws Kontejner_Greska Ukoliko se može spremiti instanca objekt Log-a.
     *
     * @return string Opcija.
     */
    private function slika_servis_slika (string $opcija):string {

        // provjeri da li je odabrani servis vrsta slika dostupan
        if (!array_key_exists($opcija, $this->rezultat['slika']['servisi'])) {
            zapisnik(Level::HITNO, sprintf(_('Servis slika %s nije dostupan kao izbor!'), $opcija));
            throw new KonfiguracijaMetoda_Greska(sprintf(_('Servis slika %s nije dostupna kao izbor!'), $opcija));
        }

        // provjeri da li su sve ekstenzije učitane
        array_walk(
            $this->rezultat['slika']['servisi'][$opcija]['ekstenzije'],
            static function ($ekstenzija) use ($opcija):void {
                if (!extension_loaded($ekstenzija)) {
                    zapisnik(Level::HITNO, sprintf(_('Ekstenzija potrebna %s za korištenje %s nije dostupna!'), $ekstenzija, $opcija));
                    throw new KonfiguracijaMetoda_Greska(sprintf(_('Ekstenzija potrebna %s za korištenje %s nije dostupna!'), $ekstenzija, $opcija));
                }
            }
        );

        // dodaj zadane parametre u rezultat koji nisu navedeni
        array_walk(
            $this->rezultat['slika']['servisi'][$opcija]['parametri'],
            function ($vrijednost, $parametar):void {
                if (!array_key_exists($parametar, $this->rezultat['slika'])) {
                    $this->rezultat['slika'][$parametar] = $vrijednost;
                }
            }
        );

        return $opcija;

    }

    /**
     * ### Provjerava preduvjete aplikacije
     * @since 0.3.5.pre-alpha.M3
     *
     * @param string|int ...$opcije <p>
     * Preduvjeti.
     * </p>
     *
     * @throws KonfiguracijaMetoda_Greska Ukoliko preduvjet nije ispunjen.
     * @throws Kontejner_Greska Ukoliko se može spremiti instanca Log-a.
     *
     * @return bool True ukoliko su svi preduvjeti ispunjeni.
     */
    private function aplikacija_preduvjeti (string|int ...$opcije):bool {

        // provjeri FireHub verziju
        if ($opcije['firehub_verzija'] !== $this->rezultat['sustav']['informacije']['verzija'].'.'.$this->rezultat['sustav']['informacije']['ciklus']) {

            zapisnik(Level::HITNO, sprintf(_('Aplikacija zahtjeva da verzija FireHub-a mora biti %s'), $this->rezultat['sustav']['informacije']['verzija'].'.'.$this->rezultat['sustav']['informacije']['ciklus']));
            throw new KonfiguracijaMetoda_Greska(sprintf(_('Aplikacija zahtjeva da verzija FireHub-a mora biti %s'), $this->rezultat['sustav']['informacije']['verzija'].'.'.$this->rezultat['sustav']['informacije']['ciklus']));

        }

        // provjeri PHP verziju
        if (version_compare(PHP_VERSION, $opcije['php_verzija'], '<')) {

            zapisnik(Level::HITNO, sprintf(_('Aplikacija zahtjeva da verzija PHP-a mora biti najmanje %s'), $opcije['php_verzija']));
            throw new KonfiguracijaMetoda_Greska(sprintf(_('Aplikacija zahtjeva da verzija PHP-a mora biti najmanje %s'), $opcije['php_verzija']));

        }

        return true;

    }

}