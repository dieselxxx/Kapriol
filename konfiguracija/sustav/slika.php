<?php declare(strict_types = 1);

/**
 * Datoteka za konfiguraciju parametara poslužitelja slika
 * @since 0.6.1.alpha.M6
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
    | Konekcije
    |--------------------------------------------------------------------------
     * Vrste servisa za obradu slika i njihove potrebne ekstenzije i parametri.
     * Ovi parameteri su zadani za sve aplikacije i prepisuje ih datoteka sa prvim ključevima kao parametrima.
     * @since 0.6.1.alpha.M6
     *
     * @var array<string, array<string, array>>
     */
    'servisi' => [
        'slika' => [
            'ekstenzije' => [
                'mbstring', 'exif', 'gd'
            ],
            'parametri' => [
                'servis' => \FireHub\Jezgra\Komponente\Slika\Servisi\Slika_Servis::class,
                'kompresija' => 80
            ]
        ]
    ],

    /**
    |--------------------------------------------------------------------------
    | Zadani servis za obradu slika
    |--------------------------------------------------------------------------
     * Zadani servis za obradu slika ukoliko nije naveden u konfiguracijskim
     * datotekama aplikacije.
     * @since 0.6.1.alpha.M6
     *
     * @var array<string, string>
     */
    'servis_slika' => 'slika'

];