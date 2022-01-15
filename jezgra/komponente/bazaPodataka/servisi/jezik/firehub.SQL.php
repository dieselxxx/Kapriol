<?php declare(strict_types = 1);

/**
 * Datoteka za SQL query jezik
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
 * ### Podservis za SQL query jezik
 * @since 0.6.0.alpha.M1
 *
 * @package Sustav\Jezgra
 */
class SQL implements Jezik_Interface {

    /**
     * ### Tabela upita
     * @var string
     */
    protected string $tabela;

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

        $this->tabela = '['.$baza.'].'.'['.konfiguracija('baza_podataka.shema').'].['.$tabela.']';
        $this->upit = $upit;

        match ($upit->vrsta) {
            'odaberi' => $this->odaberi()->spoji()->gdje(),
            'umetni' => $this->umetni(),
            'azuriraj' => $this->azuriraj(),
            'izbrisi' => $this->izbrisi()->spoji()->gdje()
        };

        return match ($upit->vrsta) {
            'odaberi' => "SELECT * FROM ($this->rezultat) [upit] ORDER BY RedBroj{$this->limit()}",
            default => $this->rezultat
        };

    }

    /**
     * ### Odaberi podatke
     * @since 0.6.0.alpha.M1
     *
     * @return $this Instanca ovog objekta.
     */
    protected function odaberi ():self {

        $this->rezultat = "SELECT ROW_NUMBER() OVER (ORDER BY [{$this->poredaj()}] {$this->poredaj_redoslijed()}) AS RedBroj, {$this->kolumne($this->upit->kolumne)} FROM $this->tabela";

        return $this;

    }

    /**
     * ### Umetni podatke
     * @since 0.6.0.alpha.M1
     *
     * @return $this Instanca Baze Podataka.
     */
    protected function umetni ():self {

        $podatci = array_map(
            function ($podataka):mixed {
                return $this->format($podataka);
            },
            array_values($this->upit->podatci)
        );

        $this->rezultat = "INSERT INTO $this->tabela ({$this->kolumne(array_keys($this->upit->podatci))}) VALUES (".implode(',', array_values($podatci)).")";

        return $this;

    }

    /**
     * ### Ažuriraj podatke
     * @since 0.6.0.alpha.M1
     *
     * @return $this Instanca ovog objekta.
     */
    protected function azuriraj ():self {

        $podatci = [];
        foreach ($this->upit->podatci as $kljuc => $vrijednost) {

            $podatci[] = $this->kolumne($kljuc).''.' = '.$this->format($vrijednost);

        }

        $this->rezultat = "UPDATE $this->tabela SET".implode(',', $podatci);

        return $this;

    }

    /**
     * ### Izbriši podatke
     * @since 0.6.0.alpha.M1
     *
     * @return $this Instanca ovog objekta.
     */
    protected function izbrisi ():self {

        $this->rezultat = "DELETE FROM $this->tabela";

        return $this;

    }

    /**
     * ### Spoji dodatnu tabelu
     * @since 0.6.0.alpha.M1
     *
     * @return $this Instanca ovog objekta.
     */
    protected function spoji ():self {

        if (!empty($this->upit->spoji)) {

            foreach ($this->upit->spoji as $spoji) {

                $this->rezultat .= 'LEFT JOIN '.$spoji['tabela'].' ON '.$spoji['kolumna_lijevo'].' = '.$spoji['kolumna_desno'];

            }

        }

        return $this;

    }

    /**
     * ### Filtar podataka
     * @since 0.6.0.alpha.M1
     *
     * @return $this Instanca ovog objekta.
     */
    protected function gdje ():self {

        if (!empty($this->upit->gdje)) {

            $this->rezultat .= ' WHERE '.implode
                (
                    ' AND ',
                    array_map(
                        function ($gdje) {
                            return "{$this->kolumne($gdje['naziv'])} $gdje[operator] {$this->format($gdje['vrijednost'])}";
                        },
                        $this->upit->gdje
                    )
                );

        }

        return $this;

    }

    /**
     * ### Limit rezultata upita
     * @since 0.6.0.alpha.M1
     *
     * @return string Upit za limit.
     */
    protected function limit ():string {

        return !is_null($this->upit->limit_pomak) && !is_null($this->upit->limit_broj_redaka)
            ? ' OFFSET '.$this->upit->limit_pomak.' ROWS FETCH NEXT '.$this->upit->limit_broj_redaka.' ROWS ONLY'
            : '';

    }

    /**
     * ### Redanje zapisa
     * @since 0.6.0.alpha.M1
     *
     * @return string Upit za poredaj.
     */
    protected function poredaj ():string {

        return $this->upit->poredaj ?: $this->upit->kolumne[0];

    }

    /**
     * ### Redoslijed redanje zapisa
     * @since 0.6.0.alpha.M1
     *
     * @return string Upit za redoslijed.
     */
    protected function poredaj_redoslijed ():string {

        return $this->upit->poredaj_redoslijed ?: 'ASC';

    }

    /**
     * ### Formatiranje niza kolumni
     * @since 0.6.0.alpha.M1
     *
     * @param array|string $lista <p>
     * Lista kolumni za formatiranje.
     * </p>
     *
     * @return string Formtirani niz kolumni.
     */
    protected function kolumne (array|string $lista):string {

        if (is_array($lista)) {

            array_walk(
                $lista,
                static function (&$kolumna) {
                    return $kolumna = '['.$kolumna.']';
                }
            );

            return implode(',', $lista);

        }

        return '['.$lista.']';

    }

    /**
     * ### Formatiranje vrijednosti
     * @since 0.6.0.alpha.M1
     *
     * @param mixed $vrijednost <p>
     * Lista vrijednosti za formatiranje.
     * </p>
     *
     * @return mixed Formtirani niz vrijednosti.
     */
    protected function format (mixed $vrijednost):mixed {

        return match (true) {
            is_string($vrijednost) => "'$vrijednost'",
            default => $vrijednost
        };

    }

}