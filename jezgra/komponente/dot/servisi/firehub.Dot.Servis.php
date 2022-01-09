<?php declare(strict_types = 1);

/**
 * Datoteka za čitanje dot zapisa
 * @since 0.3.2.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\Dot\Servisi;

use FireHub\Jezgra\Komponente\Dot\Dot;
use FireHub\Jezgra\Komponente\Dot\Dot_Interface;

/**
 * ### Servis za čitanje dot zapisa
 * @since 0.3.2.pre-alpha.M3
 *
 * @package Sustav\Jezgra
 */
final class Dot_Servis implements Dot_Interface {

    /**
     * ### Kontruktor
     * @since 0.3.2.pre-alpha.M3
     *
     * @param Dot $posluzitelj <p>
     * Poslužitelj servisa.
     * </p>
     */
    public function __construct (
        private Dot $posluzitelj
    ) {}

    /**
     * @inheritDoc
     */
    public function sve ():array {

        return $this->posluzitelj->niz;

    }

    /**
     * @inheritDoc
     */
    public function dohvati (string $vrijednost):mixed {

        $rezultat = &$this->posluzitelj->niz;

        foreach (explode('.', $vrijednost) as $segment) {

            // ako rezultat nije niz ili ključ ne postoji vrati prazno polje
            if (!is_array($rezultat) || !array_key_exists($segment, $rezultat)) {

                return null;

            }

            $rezultat = &$rezultat[$segment];

        }

        return $rezultat;

    }

    /**
     * @inheritDoc
     */
    public function dodaj (string $zapis, mixed $vrijednost):bool {

        if (!is_null($this->dohvati($zapis))) {

            return false;

        }

        return $this->postavi($zapis, $vrijednost);

    }

    /**
     * @inheritDoc
     */
    public function postavi (string $zapis, mixed $vrijednost):bool {

        $rezultat = &$this->posluzitelj->niz;

        foreach (explode('.', $zapis) as $kljuc) {

            if (!isset($rezultat[$kljuc]) || !is_array($rezultat[$kljuc])) {

                $rezultat[$kljuc] = [];

            }

            $rezultat = &$rezultat[$kljuc];

        }

        $rezultat = $vrijednost;

        return true;

    }

    /**
     * @inheritDoc
     */
    public function zamijeni (string $zapis, mixed $vrijednost):bool {

        if (is_null($this->dohvati($zapis))) {

            return false;

        }

        return $this->postavi($zapis, $vrijednost);

    }

    /**
     * @inheritDoc
     */
    public function ocisti (string $zapis):bool {

        if (is_null($this->dohvati($zapis))) {

            return false;

        }

        return $this->postavi($zapis, null);

    }

    /**
     * @inheritDoc
     */
    public function izbrisi (string $zapis):bool {

        $rezultat = &$this->posluzitelj->niz;
        $segmentni = explode('.', $zapis);
        $zadnji_segment = array_pop($segmentni);

        foreach ($segmentni as $segment) {

            if (!isset($rezultat[$segment]) || !is_array($rezultat[$segment])) {

                continue;

            }

            $rezultat = &$rezultat[$segment];

        }

        unset($rezultat[$zadnji_segment]);

        return true;

    }

    /**
     * @inheritDoc
     */
    public function postoji (string $zapis):bool {

        $rezultat = $this->posluzitelj->niz;

        foreach (explode('.', $zapis) as $segment) {

            if (!is_array($rezultat) || !array_key_exists($segment, $rezultat)) {

                return false;

            }

            $rezultat = $rezultat[$segment];

        }

        return true;

    }

}