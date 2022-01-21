<?php declare(strict_types = 1);

/**
 * Datoteka za NoSQL dokument query jezik
 * @since 0.6.0.alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\BazaPodataka\Servisi\Jezik;

use FireHub\Jezgra\Komponente\BazaPodataka\Servisi\Jezik_Interface;
use FireHub\Jezgra\Komponente\BazaPodataka\Servisi\Upit;

/**
 * ### Podservis za NoSQL dokument query jezik
 * @since 0.6.0.alpha.M1
 *
 * @package Sustav\Jezgra
 */
final class NoSQLDokument implements Jezik_Interface {

    /**
     * ### Objekt upita
     * @var Upit
     */
    protected Upit $upit;

    /**
     * ### Rezultat
     * @var string
     */
    protected string $rezultat;

    /**
     * @inheritDoc
     */
    public function obradi (string $baza, string $tabela, Upit $upit):string {

        $this->upit = $upit;

        match ($upit->vrsta) {
            'odaberi' => $this->odaberi(),
            'umetni' => $this->umetni(),
            'azuriraj' => $this->azuriraj(),
            'izbrisi' => $this->izbrisi()
        };

        return $this->rezultat;

    }

    /**
     * ### Odaberi podatke
     * @since 0.6.0.alpha.M1
     *
     * @return $this Instanca ovog objekta.
     */
    private function odaberi ():self {

        $this->rezultat = serialize(
            [
                $this->gdje(),
                [
                    'projection' => array_merge(['_id' => 0], $this->kolumne($this->upit->kolumne)),
                    'sort' => [$this->poredaj() => $this->poredaj_redoslijed()],
                    'skip' => $this->pomak(),
                    'limit' => $this->limit()
                ]
            ]
        );

        return $this;

    }

    /**
     * ### Umetni podatke
     * @since 0.6.0.alpha.M1
     *
     * @return $this Instanca ovog objekta.
     */
    private function umetni ():self {

        $this->rezultat = serialize(
            $this->upit->podatci
        );

        return $this;

    }

    /**
     * ### Ažuriraj podatke
     * @since 0.6.0.alpha.M1
     *
     * @return $this Instanca ovog objekta.
     */
    private function azuriraj ():self {

        $this->rezultat = serialize(
            [
                $this->gdje(),
                ['$set' => $this->upit->podatci]
            ]
        );

        return $this;

    }

    /**
     * ### Izbriši podatke
     * @since 0.6.0.alpha.M1
     *
     * @return $this Instanca ovog objekta.
     */
    private function izbrisi ():self {

        $this->rezultat = serialize(
            $this->gdje()
        );

        return $this;

    }

    /**
     * ### Filtar podataka
     * @since 0.6.0.alpha.M1
     *
     * @return array Niz filtara za gdje.
     */
    private function gdje ():array {

        $rezultat = [];
        if (!empty($this->upit->gdje)) {

            foreach ($this->upit->gdje as $gdje) {

                $rezultat[$gdje['naziv']] = match ($gdje['operator']) {
                    '>' => ['$gt' => $gdje['vrijednost']],
                    '>=' => ['$gte' => $gdje['vrijednost']],
                    '<' => ['$lt' => $gdje['vrijednost']],
                    '<=' => ['$lte' => $gdje['vrijednost']],
                    default => $gdje['vrijednost']
                };

            }

        }

        return $rezultat;

    }

    /**
     * ### Limit rezultata upita
     * @since 0.6.0.alpha.M1
     *
     * @return int Upit za limit.
     */
    private function limit ():int {

        return !is_null($this->upit->limit_pomak) && !is_null($this->upit->limit_broj_redaka)
            ? $this->upit->limit_broj_redaka
            : 0;

    }

    /**
     * ### Pomak rezultata upita
     * @since 0.6.0.alpha.M1
     *
     * @return int Upit za pomak.
     */
    private function pomak ():int {

        return !is_null($this->upit->limit_pomak) && !is_null($this->upit->limit_broj_redaka)
            ? $this->upit->limit_pomak
            : 0;

    }

    /**
     * ### Redanje zapisa
     * @since 0.6.0.alpha.M1
     *
     * @return string Upit za poredaj.
     */
    private function poredaj ():string {

        return $this->upit->poredaj ?: $this->upit->kolumne[0];

    }

    /**
     * ### Redoslijed redanje zapisa
     * @since 0.6.0.alpha.M1
     *
     * @return int Upit za poredaj redoslijed.
     */
    private function poredaj_redoslijed ():int {

        return $this->upit->poredaj_redoslijed
            ? $this->upit->poredaj_redoslijed === 'ASC' ? 1 : -1
            : -1;

    }

    /**
     * ### Formatiranje niza kolumni
     * @since 0.6.0.alpha.M1
     *
     * @param array $lista<p>
     * Lista kolumni za formatiranje.
     * </p>
     *
     * @return array Formtirani niz kolumni.
     */
    private function kolumne (array $lista):array {

        $rezultat = [];
        foreach ($lista as $kolumna) {

            $rezultat[$kolumna] = 1;

        }

        return $rezultat;

    }

}