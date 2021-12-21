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

    \FireHub\Jezgra\Komponente\Test\Test::class => [
        'servisi' => [
            \FireHub\Jezgra\Komponente\Test\Servisi\Test_Servis::class => []
        ]
    ],

    \FireHub\Jezgra\Komponente\Log\Log::class => [
        'servisi' => [
            \FireHub\Jezgra\Komponente\Log\Servisi\Posalji::class => [],
            \FireHub\Jezgra\Komponente\Log\Servisi\AutoPosalji::class => []
        ]
    ]

];