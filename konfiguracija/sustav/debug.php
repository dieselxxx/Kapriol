<?php declare(strict_types = 1);

/**
 * Datoteka za konfiguraciju parametara debuggiranja
 * @since 0.3.5.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright 2021 Grafotisak d.o.o.
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Konfiguracija
 */

return [

    /**
     * --------------------------------------------------------------------------
     * PHP
     * --------------------------------------------------------------------------
     * Prikazuje sve PHP greške i upozoranja na stranici sustava.
     * Ugasiti na produkciji.
     * @since 0.3.5.pre-alpha.M3
     *
     * @var array<string, array<string, mixed>>
     */
    'php' => [
        'upaljeno' => env('DEBUG_PHP',false),
        'opcije' => [
            'display_errors' => '1',
            'display_startup_errors' => '1'
        ]
    ],

    /**
     * --------------------------------------------------------------------------
     * Sustav
     * --------------------------------------------------------------------------
     * Zapisuje sve greške sustava u datoteku.
     * Upaliti u produkciji.
     * @since 0.3.5.pre-alpha.M3
     *
     * @var array<string, array<string, string>>
     */
    'sustav' => [
        'upaljeno' => env('DEBUG_SUSTAV',false),
        'opcije' => [
            'log_errors' => '1',
            'ignore_repeated_errors' => 'true',
            'ignore_repeated_source' => 'true',
            'error_log' => FIREHUB_ROOT . 'log' .RAZDJELNIK_MAPE . 'server.log'
        ]
    ]

];