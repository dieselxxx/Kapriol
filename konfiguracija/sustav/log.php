<?php declare(strict_types = 1);

/**
 * Datoteka za konfiguraciju parametara log poslužitelja
 * @since 0.3.5.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Konfiguracija
 */

use FireHub\Jezgra\Komponente\Log\Enumeratori\Level;

return [

    /**
    |--------------------------------------------------------------------------
    | Zadani level log-a
    |--------------------------------------------------------------------------
     * Zadani level log-a ukoliko nije navedena u konfiguracijskim
     * datotekama aplikacije.
     * @since 0.3.5.pre-alpha.M3
     *
     * @var Level
     */
    'level' => Level::BILJESKA

];