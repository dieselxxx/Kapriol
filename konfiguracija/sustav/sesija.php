<?php declare(strict_types = 1);

/**
 * Datoteka za konfiguraciju parametara dostupnih vrsta sesije
 * @since 0.5.3.pre-alpha.M5
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
    |--------------------------------------------------------------------------
    | Vrste sesija
    |--------------------------------------------------------------------------
     * Vrste sesija i njihovi parametri.
     * Ovi parameteri su zadani za sve aplikacije i prepisuje ih datoteka sa prvih ključevima kao parametrima.
     * @since 0.5.3.pre-alpha.M5
     *
     * @var array<string, array<string, array>>
     */
    'vrste' => [
        'datoteka' => [
            'parametri' => [
                'servis' => \FireHub\Jezgra\Komponente\Sesija\Servisi\Datoteka::class,
                'naziv' => 'FireHub_Sesija',
                'lokacija' => FIREHUB_ROOT . 'sesije',
                'vrijeme' => 86400,
                'putanja'=>  '/',
                'domena' => '',
                'ssl' => false,
                'http' => true,
                'ista_stranica' => \FireHub\Jezgra\Komponente\Kolacic\Enumeratori\IstaStranica::LAX
            ]
        ]
    ],

    /**
    |--------------------------------------------------------------------------
    | Zadana vrsta sesije
    |--------------------------------------------------------------------------
     * Zadana vrsta sesije ukoliko nije navedena u konfiguracijskim
     * datotekama aplikacije.
     * @since 0.5.3.pre-alpha.M5
     *
     * @var array<string, string>
     */
    'vrsta' => 'datoteka'

];