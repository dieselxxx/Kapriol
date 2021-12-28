<?php declare(strict_types = 1);

/**
 * Datoteka abstraktne klase za servise konfiguracije
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

use FireHub\Jezgra\Komponente\Dot\Dot;
use FireHub\Jezgra\Komponente\Dot\Dot_Interface;
use FireHub\Jezgra\Komponente\Konfiguracija\Servisi\Metode_PodServis;
use FireHub\Jezgra\Kolekcije\Niz_Kolekcija;
use FireHub\Jezgra\Komponente\Konfiguracija\Enumeratori\Citac;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;

/**
 * ### Abstraktna klasa za servise konfiguracije
 * @since 0.3.5.pre-alpha.M3
 *
 * @package Sustav\Konfiguracija
 */
abstract class Konfiguracija_Abstrakt implements Konfiguracija_Interface {

    /**
     * ### Konfiguracijski niz
     * @var array<string, mixed>
     */
    protected array $konfiguracija = [];

    /**
     * ### Dot servis
     * @var Dot_Interface
     */
    private Dot_Interface $dot;

    /**
     * ### Lista ključeva koje aplikacije ne smiju predefinirati u svojim konfiguracijskim datotekama
     * @var array<string, string>
     */
    private array $crna_lista_kljuceva = [
        'sustav' => '',
        'debug' => '',
        'baza_podataka' => ['konekcije' => ''],
        'predmemorija' => ['konekcije' => '']
    ];

    /**
     * ### Lista zapisa koje aplikacije ne smiju vidjeti niti pozvati
     * @var array<string, mixed>
     */
    private array $crna_lista_zapisa = [
        'baza_podataka' => 'konekcije',
        'predmemorija' => 'konekcije',
        'sustav' => 'aplikacije', 'preduvjeti'
    ];

    /**
     * ### Konstruktor
     * @since 0.3.5.pre-alpha.M3
     *
     * @param Konfiguracija $posluzitelj <p>
     * Poslužitelj servisa.
     * </p>
     * @param Dot $dot <p>
     * Dot servis za čitanje/pisanje u niz.
     * </p>
     * @param Metode_PodServis $metodePodServis <p>
     * Servis za pozivanje potrebnih metoda konfiguracije.
     * </p>
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Dot-a.
     */
    final public function __construct (
        private Konfiguracija $posluzitelj,
        Dot $dot,
        private Metode_PodServis $metodePodServis
    ) {

        // spremi konfiguraciju u listu
        $this->konfiguracija = $this->filtrirajZapiseKonfiguracije(
            $this->metodePodServis->pokreni($this->dohvatiKonfiguraciju())->rezultat()
        );

        // pokreni Dot servis
        $this->dot = $dot->niz([$this->konfiguracija])->napravi();

        var_dump($this->dot->sve());

    }

    /**
     * @inheritDoc
     */
    final public function dohvati (string $naziv):string|int|float|bool|array|null {

        return $this->dot->dohvati($naziv);

    }

    /**
     * @inheritDoc
     */
    final public function postoji (string $naziv):bool {

        return $this->dot->postoji($naziv);

    }

    /**
     * @inheritDoc
     */
    final public function dodaj (string $naziv, float|array|bool|int|string|null $vrijednost):bool {

        return $this->dot->dodaj($naziv, $vrijednost);

    }

    /**
     * ### Filtiriraj konfiguraciju sa crne liste zapisa
     * @since 0.3.5.pre-alpha.M3
     *
     * @param array $konfiguracija <p>
     * Lista konfiguracije koju treba filtirirati
     * </p>
     *
     * @return array<string, mixed> Filtirana lista konfiguracije.
     */
    private function filtrirajZapiseKonfiguracije (array $konfiguracija):array {

        foreach ($this->crna_lista_zapisa as $zapis => $vrijednost) {

            unset($konfiguracija[$zapis][$vrijednost]);

        }

        return $konfiguracija;

    }

    /**
     * ### Dohvati konfiguraciju
     * @since 0.3.5.pre-alpha.M3
     *
     * @return array<string, mixed> Lista konfiguracije.
     */
    private function dohvatiKonfiguraciju():array {

        return array_replace_recursive(
            $this->konfiguracijaSustav(),
            $this->filtirajAplikacijaKonfiguracija($this->konfiguracijaAplikacija())
        );

    }

    /**
     * ### Filtiranje konfiguracijske datoteke aplikacije
     * @since 0.3.5.pre-alpha.M3
     *
     * @param array<string, mixed> $konfiguracija_aplikacija <p>
     * Konfiguracija aplikacije.
     * </p>
     *
     * @return array<string, mixed> Filtirana lista konfiguracije aplikacije.
     */
    private function filtirajAplikacijaKonfiguracija (array $konfiguracija_aplikacija):array {

        return Niz_Kolekcija::filterNisuPrazni(
            array_replace_recursive(
                $konfiguracija_aplikacija, $this->crna_lista_kljuceva
            )
        );

    }

    /**
     * ### Dodaj konfiguracijsku datoteku sustava
     * @since 0.3.5.pre-alpha.M3
     *
     * @return array<string, mixed> Konfiguracijski niz.
     */
    abstract protected function konfiguracijaSustav ():array;

    /**
     * ### Dodaj konfiguracijsku datoteku aplikacije
     * @since 0.3.5.pre-alpha.M3
     *
     * @return array<string, mixed> Konfiguracijski niz.
     */
    abstract protected function konfiguracijaAplikacija ():array;

    /**
     * ### Pročitaj vrstu čitača iz env datoteke aplikacije
     * @since 0.3.5.pre-alpha.M3
     *
     * @return string Naziv klase čitača.
     */
    protected function vrstaKonfiguracijeAplikacija ():string {

        if (
            Citac::tryFrom(
                env('KONFIGURACIJA', 'niz')
            )
        ) {

            return "\\FireHub\\Jezgra\\Komponente\\Konfiguracija\\Servisi\\Citac\\".Citac::from(
                env('KONFIGURACIJA', 'niz')
            )->value;

        }

        return "\\FireHub\\Jezgra\\Komponente\\Konfiguracija\\Servisi\\Citac\\".'niz';

    }

}