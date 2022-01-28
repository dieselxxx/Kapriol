<?php declare(strict_types = 1);

/**
 * Datoteka za MySQL query jezik
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
 * ### Podservis za MySQL query jezik
 * @since 0.6.0.alpha.M1
 *
 * @package Sustav\Jezgra
 */
final class MySQL extends SQL implements Jezik_Interface {

    /**
     * @inheritDoc
     */
    public function obradi (string $baza, string $tabela, Upit $upit):string {

        $this->tabela = '`'.$baza.'`.'.'`'.$tabela.'`';
        $this->upit = $upit;

        match ($upit->vrsta) {
            'odaberi' => $this->odaberi()->spoji()->gdje(),
            'umetni' => $this->umetni(),
            'azuriraj' => $this->azuriraj()->gdje(),
            'izbrisi' => $this->izbrisi()->spoji()->gdje()
        };

        return match ($upit->vrsta) {
            'odaberi' => "SELECT * FROM ($this->rezultat) `upit` ORDER BY RedBroj{$this->limit()}",
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

        $this->rezultat = "SELECT ROW_NUMBER() OVER (ORDER BY `{$this->poredaj()}` {$this->poredaj_redoslijed()}) AS RedBroj, {$this->kolumne($this->upit->kolumne)} FROM $this->tabela";

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
            ? ' LIMIT '.$this->upit->limit_broj_redaka.' OFFSET '.$this->upit->limit_pomak
            : '';

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
                    return $kolumna = '`'.$kolumna.'`';
                }
            );

            return implode(',', $lista);

        }

        return '`'.$lista.'`';

    }

}