<?php declare(strict_types = 1);

/**
 * Datoteka za registriranje konfiguracijskih podataka preko niza podataka
 * @since 0.3.5.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Konfiguracija
 */

namespace FireHub\Jezgra\Komponente\Konfiguracija\Servisi;

use FireHub\Jezgra\Komponente\Konfiguracija\Konfiguracija_Abstrakt;
use FireHub\Jezgra\Komponente\Konfiguracija\Servisi\Citac\Niz as Niz_Citac;

/**
 * ### Servis za registriranje konfiguracijskih podataka preko niza podataka
 * @since 0.3.5.pre-alpha.M3
 *
 * @package Sustav\Konfiguracija
 */
final class Niz extends Konfiguracija_Abstrakt {

    /**
     * @inheritDoc
     */
    protected function konfiguracijaSustav ():array {

        return (new Niz_Citac())->ucitaj(FIREHUB_ROOT . 'konfiguracija' . RAZDJELNIK_MAPE . 'sustav' . RAZDJELNIK_MAPE . '*.php');

    }

    /**
     * @inheritDoc
     */
    protected function konfiguracijaAplikacija ():array {

        return (new ($this->vrstaKonfiguracijeAplikacija()))->ucitaj(APLIKACIJA_ROOT . 'konfiguracija' . RAZDJELNIK_MAPE . 'sustav' . RAZDJELNIK_MAPE . '*.php');

    }

}