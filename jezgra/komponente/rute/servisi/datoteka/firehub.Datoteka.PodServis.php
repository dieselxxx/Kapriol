<?php declare(strict_types = 1);

/**
 * Datoteka za registriranje HTTP ruta
 * @since 0.4.1.pre-alpha.M4
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\HTTP
 */

namespace FireHub\Jezgra\Komponente\Rute\Servisi\Datoteka;

use FireHub\Jezgra\Komponente\Log\Enumeratori\Level;
use FireHub\Jezgra\Komponente\Konfiguracija\Greske\Konfiguracija_Greska;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;

/**
 * ### Podservis za registriranje HTTP ruta
 * @since 0.4.1.pre-alpha.M4
 *
 * @package Sustav\HTTP
 */
final class Datoteka_PodServis {

    /**
     * ### Puna putanja do konfiguracijske datoteke
     * @var string
     */
    private string $putanja;

    /**
     * ### Dohvati niz zapisa ruta
     * @since 0.4.1.pre-alpha.M4
     *
     * @param string $putanja <p>
     * Puna putanja do datoteke sa zapisima ruta.
     * </p>
     *
     * @return array<string, mixed> Niz zapisa ruta.
     */
    public function ucitaj (string $putanja):array {

        $this->putanja = $putanja;

        return array_map(
            array($this, 'dodajDatoteku'),
            $this->formatiranNizDatoteka()
        );

    }

    /**
     * ### Učitaj niz zapisa ruta
     * @since 0.4.1.pre-alpha.M4
     *
     * @param string $datoteka <p>
     * Puna putanja do niza zapisa ruta.
     * </p>
     *
     * @throws Konfiguracija_Greska Ukoliko datoteka nije niz.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     *
     * @return int Dodane datoteke.
     */
    private function dodajDatoteku (string $datoteka):int {

        // dodaj datoteku
        $podatci = include $datoteka;

        // ukoliko datoteka nije ispravno učitana
        if ($podatci !== 1) {

            zapisnik(Level::KRITICNO, sprintf(_('Datoteka: %s,nije ispravno učitana!'), $datoteka));
            throw new Konfiguracija_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru'));

        }

        return $podatci;

    }

    /**
     * ### Formatiraj niz skeniranih datoteka
     * @since 0.4.1.pre-alpha.M4
     *
     * @return array<string, string> Formatirani niz datoteka.
     */
    private function formatiranNizDatoteka ():array {

        if (
            empty(
            array_map(
                static function($number) use (&$formatiran_niz):string {
                    return $formatiran_niz[pathinfo($number)['filename']] = $number;
                }, $this->skeniraj())
            )
        ) {
            return [];
        }

        return $formatiran_niz;

    }

    /**
     * ### Skeniraj zadanu mapu za datoteke u potrazi za konfiguracijskim datotekama
     * @since 0.4.1.pre-alpha.M4
     *
     * @return array<int, string> Skenirani niz datoteka.
     */
    private function skeniraj ():array {

        return glob($this->putanja);

    }

}