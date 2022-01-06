<?php declare(strict_types = 1);

/**
 * Datoteka za konfiguraciju parametara poslužitelja predmemorije
 * @since 0.5.0.pre-alpha.M5
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
    | Koristi predmemoriju
    |--------------------------------------------------------------------------
     * Da li aplikacije zadano korisite predmemoriju.
     * @since 0.5.0.pre-alpha.M5
     *
     * @var array<string, bool>
     */
    'ukljuceno' => false,

    /**
    |--------------------------------------------------------------------------
    | Konekcije
    |--------------------------------------------------------------------------
     * Vrste predmemorije i njihove potrebne ekstenzije i parametri.
     * Ovi parameteri su zadani za sve aplikacije i prepisuje ih datoteka sa prvim ključevima kao parametrima.
     * @since 0.5.0.pre-alpha.M5
     *
     * @var array<string, array<string, array>>
     */
    'konekcije' => [
        'memcache' => [
            'ekstenzije' => [
                'memcache'
            ],
            'parametri' => [
                'servis' => \FireHub\Jezgra\Komponente\Predmemorija\Servisi\MemCache::class,
                'host' => 'localhost',
                'port' => 11211,
                'trajno' => true,
                'tezina' => 1,
                'odziv' => 0.5,
                'interval_ponovni_pokusaj' => 60,
                'korisnicko_ime' => '',
                'lozinka' => '',
                'prefiks' => APLIKACIJA . '_',
                'prag_duljine_kompresije' => 20000,
                'kompresija' => 0.2,
                'dodatni_serveri' => ''
            ]
        ]
    ],

    /**
    |--------------------------------------------------------------------------
    | Zadana predmemorija
    |--------------------------------------------------------------------------
     * Zadana predmemorija ukoliko nije navedena u konfiguracijskim
     * datotekama aplikacije.
     * @since 0.5.0.pre-alpha.M5
     *
     * @var array<string, string>
     */
    'server' => 'memcache'

];