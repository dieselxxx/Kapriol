<?php declare(strict_types = 1);

/**
 * Datoteka za registriranje HTTP ruta iz datoteka
 * @since 0.4.1.pre-alpha.M4
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\HTTP
 */

namespace FireHub\Jezgra\Komponente\Rute\Servisi;

use FireHub\Jezgra\Komponente\Rute\Rute;
use FireHub\Jezgra\Komponente\Rute\Rute_Interface;
use FireHub\Jezgra\Komponente\Rute\Servisi\Datoteka\Datoteka_PodServis;
use FireHub\Jezgra\Komponente\Predmemorija\Predmemorija;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;

/**
 * ### Servis za registriranje HTTP ruta iz datoteka
 * @since 0.4.1.pre-alpha.M4
 *
 * @package Sustav\HTTP
 */
final class Datoteka implements Rute_Interface {

    /**
     * ### Kolekcija ruta iz datoteka
     * @var array<int, array<string, string, string, string>>
     */
    private array $rute = [];

    /**
     * ### Konstruktor
     * @since 0.4.1.pre-alpha.M4
     *
     * @param Rute $posluzitelj <p>
     * Poslužitelj servisa.
     * </p>
     * @param Datoteka_PodServis $datotekaPodServis <p>
     * Podservis za nizove ruta.
     * </p>
     * @param Predmemorija $predmemorija <p>
     * Predmemorija.
     * </p>
     */
    public function __construct (
        private Rute $posluzitelj,
        private Datoteka_PodServis $datotekaPodServis,
        private Predmemorija $predmemorija
    ) {}

    /**
     * {@inheritDoc}
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Konfiguracije ili predmemorije.
     */
    public function provjeri (string $metoda, string $url):array|false {

        // provjeri da li postoji ruta u predmemoriji
        if (konfiguracija('predmemorija.ukljuceno') && $this->predmemorijaRuta($metoda, $url)) {

            return $this->predmemorijaRuta($metoda, $url);

        }

        return $this->datotekaRuta($metoda, $url);

    }

    /**
     * @inheritDoc
     */
    public function dodaj (string $metoda, string $url, array $podatci):bool {

        if (
            array_push(
                $this->rute,
                ['http_metoda' => $metoda, 'url' => $url, 'podatci' => $podatci]
            ) > 0
        ) {

            return true;

        }

        return false;

    }

    /**
     * ### Odaberi rutu iz servisa datoteka
     * @since 0.5.0.pre-alpha.M5
     *
     * @param string $metoda <p>
     * Metoda rute.
     * </p>
     * @param string $url <p>
     * Url.
     * </p>
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Konfiguracije ili predmemorije.
     *
     * @return array<string, string>|false Niz podataka rute.
     */
    private function datotekaRuta (string $metoda, string $url):array|false {

        // napuni rute u servis iz datoteke
        $this->datotekaPodServis->ucitaj(APLIKACIJA_ROOT . 'podatci' . RAZDJELNIK_MAPE . 'rute' .  RAZDJELNIK_MAPE . '*.php');

        // zapiši rute u predmemoriju
        if (konfiguracija('predmemorija.ukljuceno')) {

            $this->predmemorija->napravi()->zapisi('firehub_rute', serialize($this->rute));

        }

        // ukoliko ne postoji ruta
        if (!$prva_ruta = $this->prvaRuta($metoda, $url)) {

            return false;

        }

        // ukoliko ne postoji ključ "podatci"
        if (!isset($prva_ruta['podatci'])) {

            return false;

        }

        return $prva_ruta['podatci'];

    }

    /**
     * ### Odaberi rutu iz servisa predmemorije
     * @since 0.5.0.pre-alpha.M5
     *
     * @param string $metoda <p>
     * Metoda rute.
     * </p>
     * @param string $url <p>
     * Url.
     * </p>
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Konfiguracije ili predmemorije.
     *
     * @return array<string, string>|false Niz podataka rute.
     */
    private function predmemorijaRuta (string $metoda, string $url):array|false {

        // dohvati rute iz predmemorije
        $rute = $this->predmemorija->napravi()->dohvati('firehub_rute');

        // ukoliko postoje rute u predmemoriji
        if ($rute) {

            // unserialize ruta u niz
            $rute = unserialize($rute);

            array_walk(
                $rute,
                function (array $ruta):bool {

                    // dodaj rutu u niz ruta
                    return $this->dodaj($ruta['http_metoda'], $ruta['url'], $ruta['podatci']);

                }
            );

        }

        // ukoliko ne postoji ruta
        if (!$prva_ruta = $this->prvaRuta($metoda, $url)) {

            return false;

        }

        // ukoliko ne postoji ključ "podatci"
        if (!isset($prva_ruta['podatci'])) {

            return false;

        }

        return $prva_ruta['podatci'];

    }

    /**
     * ### Odaberi prvu rutu iz niza ruta
     * @since 0.4.1.pre-alpha.M4
     *
     * @param string $metoda <p>
     * HTTP metoda rute.
     * </p>
     * @param string $url <p>
     * URL rute.
     * </p>
     *
     * @return array<string, string>|false Niz podataka rute.
     */
    private function prvaRuta (string $metoda, string $url):array|false {

        // filtirane rute
        $rute = $this->rute($metoda, $url);

        // ukoliko ne postoji ruta
        if (!$prva_ruta = reset($rute)) {

            return false;

        }

        return $prva_ruta;

    }

    /**
     * ### Filtriraj sve rute koje sadrže trenutni URL i trenutnu metodu
     * @since 0.4.1.pre-alpha.M4
     *
     * @param string $metoda <p>
     * HTTP metoda rute.
     * </p>
     * @param string $url <p>
     * URL rute.
     * </p>
     *
     * @return array<string, string> Niz podataka rute.
     */
    private function rute (string $metoda, string $url):array {

        return array_filter($this->rute, static function ($ruta) use ($url, $metoda):bool {

            if ($ruta['http_metoda'] === 'SVE' || $ruta['http_metoda'] === $metoda) {

                return $ruta['url'] === $url;

            }

            return false;

        });

    }

}