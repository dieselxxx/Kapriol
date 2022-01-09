<?php declare(strict_types = 1);

/**
 * Datoteka za konfiguraciju glavnih parametara sustava
 *
 * Ove postavke vrijede za sve aplikacije unutar sustava i ne mogu
 * se mijenjati sa dodatnim konfiguracijskim datotekama.
 * @since 0.3.5.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Konfiguracija
 */

return [

    /**
     * --------------------------------------------------------------------------
     * Informacije
     * --------------------------------------------------------------------------
     * Osnovne informacije o sustavu.
     * @since 0.3.5.pre-alpha.M3
     *
     * @var array<string, array<string, mixed>>
     */
    'informacije' => [
        'naziv' => 'FireHub',
        'ciklus' => 'pre-alpha.M3',
        'verzija' => '0.3.5'
    ],

    /**
     * --------------------------------------------------------------------------
     * Preduvjeti
     * --------------------------------------------------------------------------
     * Lista preduvjeta koji se moraju zadovoljiti kako bi sustav mogao
     * normalano raditi.
     * @since 0.3.5.pre-alpha.M3
     *
     * @var array<string, array<string, mixed>>
     */
    'preduvjeti' => [
        'php_verzija' => '8.1.0'
    ],

    /**
     * --------------------------------------------------------------------------
     * Vremenska zona
     * --------------------------------------------------------------------------
     * Zadana vremenska zona na kojoj radi sustav.
     * @since 0.3.5.pre-alpha.M3
     *
     * @var array<string, string>
     *
     * @see https://www.php.net/manual/en/function.date-default-timezone-set.php
     */
    'vremenskaZona' => env('VREMENSKA_ZONA','Europe/Zagreb'),

    /**
     * --------------------------------------------------------------------------
     * Sistemske putanje
     * --------------------------------------------------------------------------
     * Lista predefiniranih sistemskih putanja koje su nužne za rad sustava.
     * Koristiti / kao razdjelinik mapa.
     * @since 0.3.5.pre-alpha.M3
     *
     * @var array<string, array<string, string>>
     */
    'putanje' => [
        'aplikacija' => 'aplikacija/',
        'biblioteke' => 'biblioteke/',
        'dokumentacija' => 'dokumentacija/',
        'jezgra' => 'jezgra/',
        'konfiguracija' => 'konfiguracija/',
        'log' => 'log/',
        'podatci' => 'podatci/',
        'predmemorija' => 'predmemorija/',
        'sesije' => 'sesije/',
        'temp' => 'temp/',
        'vendor' => 'vendor/',
        'web' => 'web/'
    ]

];