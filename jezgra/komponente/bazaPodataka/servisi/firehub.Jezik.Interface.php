<?php declare(strict_types = 1);

/**
 * Datoteka ugovora za sve jezike baza podataka
 * @since 0.6.0.alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\BazaPodataka\Servisi;

use stdClass;

/**
 * ### Osnovni interface za sve jezike baza podataka
 *
 * @since 0.6.0.alpha.M1
 *
 * @package Sustav\Jezgra
 */
interface Jezik_Interface {

    /**
     * ### Obradi upit
     * @since 0.6.0.alpha.M1
     *
     * @param string $baza <p>
     * Naziv baze podataka.
     * </p>
     * @param string $tabela <p>
     * Naziv tabele upita.
     * </p>
     * @param stdClass $upit <p>
     * Upit prema bazi podataka.
     * </p>
     *
     * @return string Upit preko jezika.
     */
    public function obradi (string $baza, string $tabela, stdClass $upit):string;

}