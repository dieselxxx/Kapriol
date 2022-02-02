<?php declare(strict_types = 1);

/**
 * Datoteka za prazan HTML sadržaj
 * @since 0.6.1.alpha.M6
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Sadrzaj
 */

namespace FireHub\Jezgra\Sadrzaj\Vrste;

use FireHub\Jezgra\Sadrzaj\Vrsta_Interface;
use FireHub\Jezgra\Komponente\Log\Enumeratori\Level;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;
use FireHub\Jezgra\Sadrzaj\Greske\Sadrzaj_Greska;
use Generator;

/**
 * ### Klasa za prazan HTML sadržaj
 * @since 0.6.1.alpha.M6
 *
 * @package Sustav\Sadrzaj
 */
final class HTMLP implements Vrsta_Interface {

    /**
     * ### Sadržaj za ispis
     * @var string
     */
    private string $sadrzaj = '';

    /**
     * @inheritDoc
     */
    public function __construct (
        private array $podatci,
        private string $datoteka = ''
    ) {}

    /**
     * @inheritDoc
     *
     * @throws Sadrzaj_Greska Ukoliko se ne može učitati datoteka sa sadržajem.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     */
    public function ispisi ():string {

        $this->ucitajDatoteku();
        $this->obradiPodatke();

        return $this->sadrzaj;

    }

    /**
     * ### Datoteka sa HTML sadržajem
     * @since 0.6.1.alpha.M6
     *
     * @throws Sadrzaj_Greska Ukoliko se ne može učitati datoteka sa sadržajem.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     *
     * @return string Sadržaj.
     */
    private function ucitajDatoteku ():string {

        $sadrzaj = APLIKACIJA_ROOT . 'sadrzaj' . RAZDJELNIK_MAPE . $this->datoteka;

        if (!file_exists($sadrzaj) || !$sadrzaj = file_get_contents($sadrzaj)) {

            zapisnik(Level::KRITICNO, sprintf(_('Ne mogu učitati datoteku sa sadržajem: %s!'), $this->datoteka));
            throw new Sadrzaj_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru.'));

        }

        return $this->sadrzaj = $sadrzaj;

    }

    /**
     * ### Zamijeni sve varijable sa podatcima
     * @since 0.6.1.alpha.M6
     *
     * @return bool Da li se mogu obraditi podatci.
     */
    private function obradiPodatke ():bool {

        // dodaj novi sadržaj sa zamijenjenim podatcima
        foreach ($this->generator($this->sadrzaj, $this->podatci) as $sadrzaj) {

            if (!is_string($sadrzaj)) { // sadržaj nije string

                return false;

            }

            // dodaj sadržaj
            $this->sadrzaj = $sadrzaj;

        }

        return true;

    }

    /**
     * ### Zamijeni sve varijable sa podatcima preko generatora
     * @since 0.6.1.alpha.M6
     *
     * @param string $sadrzaj <p>
     * Sadržaj za ispis.
     * </p>
     * @param array<string, string|int> $podatci <p>
     * Podatci koje treba prenijeti u sadržaj.
     * </p>
     *
     * @return Generator Izmijenjeni sadržaj.
     */
    private function generator (string $sadrzaj, array $podatci):Generator {

        foreach ($podatci as $kljuc => $vrijednost) {

            // zamijeni ključeve sa vrijednosti
            yield $sadrzaj = str_replace('{{'.$kljuc.'}}', $vrijednost, $sadrzaj);

        }

    }

}