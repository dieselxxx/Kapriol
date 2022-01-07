<?php declare(strict_types = 1);

/**
 * Konfiguracijska datoteka za komponente sustava
 *
 * Datoteka vraća konfiguracijski niz za sve komponente sustava, kao što su
 * liste poslužitelja, servisi poslužitelja i dependancy injection za servise.
 * @since 0.3.0.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

return [

    \FireHub\Jezgra\Komponente\Log\Log::class => [
        'servisi' => [
            \FireHub\Jezgra\Komponente\Log\Servisi\Posalji::class => [],
            \FireHub\Jezgra\Komponente\Log\Servisi\AutoPosalji::class => []
        ]
    ],

    \FireHub\Jezgra\Komponente\Dot\Dot::class => [
        'servisi' => [
            \FireHub\Jezgra\Komponente\Dot\Servisi\Dot_Servis::class => []
        ]
    ],

    \FireHub\Jezgra\Komponente\Env\Env::class => [
        'servisi' => [
            \FireHub\Jezgra\Komponente\Env\Servisi\Datoteka::class => []
        ]
    ],

    \FireHub\Jezgra\Komponente\Datoteka\Datoteka::class => [
        'servisi' => [
            \FireHub\Jezgra\Komponente\Datoteka\Servisi\Datoteka_Servis::class => []
        ]
    ],

    \FireHub\Jezgra\Komponente\Konfiguracija\Konfiguracija::class => [
        'servisi' => [
            \FireHub\Jezgra\Komponente\Konfiguracija\Servisi\Niz::class => []
        ]
    ],

    \FireHub\Jezgra\Komponente\Rute\Rute::class => [
        'servisi' => [
            \FireHub\Jezgra\Komponente\Rute\Servisi\Datoteka::class => []
        ]
    ],

    \FireHub\Jezgra\Komponente\Predmemorija\Predmemorija::class => [
        'servisi' => [
            \FireHub\Jezgra\Komponente\Predmemorija\Servisi\MemCache::class => []
        ]
    ],

    \FireHub\Jezgra\Komponente\BazaPodataka\BazaPodataka::class => [
        'servisi' => [
            \FireHub\Jezgra\Komponente\BazaPodataka\Servisi\MSSQL::class => [],
            \FireHub\Jezgra\Komponente\BazaPodataka\Servisi\MySQL::class => [],
            \FireHub\Jezgra\Komponente\BazaPodataka\Servisi\MongoDB::class => []
        ]
    ],

    \FireHub\Jezgra\Komponente\Kolacic\Kolacic::class => [
        'servisi' => [
            \FireHub\Jezgra\Komponente\Kolacic\Servisi\Kolacic_Servis::class => []
        ]
    ],

    FireHub\Jezgra\Komponente\Sesija\Sesija::class => [
        'servisi' => [
            \FireHub\Jezgra\Komponente\Sesija\Servisi\Datoteka_Servis::class => []
        ]
    ]

];