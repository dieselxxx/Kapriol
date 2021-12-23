<?php declare(strict_types = 1);

/**
 * Čitač datoteka za registriranje konfiguracijskih podataka
 * @since 0.3.5.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Konfiguracija
 */

namespace FireHub\Jezgra\Komponente\Konfiguracija\Servisi\Citac;

use FireHub\Jezgra\Komponente\Konfiguracija\Servisi\Citac_Interface;

/**
 * ### Čitač datoteka za registriranje konfiguracijskih podataka
 * @since 0.3.5.pre-alpha.M3
 *
 * @package Sustav\Konfiguracija
 */
final class Niz implements Citac_Interface {

    /**
     * ### Puna putanja do konfiguracijske datoteke
     * @var string
     */
    private string $putanja;

    /**
     * @inheritDoc
     */
    public function ucitaj (string $putanja):array {

        $this->putanja = $putanja;

        return array_map(
            array($this, 'dodajDatoteku'),
            $this->formatiranNizDatoteka()
        );

    }

    /**
     * ### Učitaj konfiguracijske datoteke
     * @since 0.3.5.pre-alpha.M3
     *
     * @param string $datoteka <p>
     * Puna putanja do konfiguracijske datoteke.
     * </p>
     *
     * @return array<string, string> Dodane datoteke.
     */
    private function dodajDatoteku (string $datoteka):array {

        return include $datoteka;

    }

    /**
     * ### Formatiraj niz skeniranih datoteka
     * @since 0.3.5.pre-alpha.M3
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
     * @since 0.3.5.pre-alpha.M3
     *
     * @return array<int, string> Skenirani niz datoteka.
     */
    private function skeniraj ():array {

        return glob($this->putanja);

    }

}