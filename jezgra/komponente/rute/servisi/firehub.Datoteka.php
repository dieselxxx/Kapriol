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
     */
    public function __construct (
        private Rute $posluzitelj,
        private Datoteka_PodServis $datotekaPodServis
    ) {}

    /**
     * @inheritDoc
     */
    public function provjeri (string $metoda, string $url):array|false {

        // napuni rute u servis iz datoteke
        $this->datotekaPodServis->ucitaj(APLIKACIJA_ROOT . 'podatci' . RAZDJELNIK_MAPE . 'rute' .  RAZDJELNIK_MAPE . '*.php');

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
     * @inheritDoc
     */
    public function dodaj (string $metoda, string $url, array $podatci):bool {

        if (
            array_push(
                $this->rute,
                ['http_metoda' => $metoda, 'url' => $url, 'podatci' => $podatci]
            ) < 0
        ) {

            return true;

        }

        return false;

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