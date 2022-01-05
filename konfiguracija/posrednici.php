<?php declare(strict_types = 1);

/**
 * Konfiguracijska datoteka za mapiranje grupa posrednika u sustavu
 * @since 0.4.0.pre-alpha.M4
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Posrednici
 */

return [

    /**
    |--------------------------------------------------------------------------
    | Globalni http posrednici
    |--------------------------------------------------------------------------
     * Lista posrednika koje vrijede za sve HTTP zahtjeve sustava.
     * @since 0.4.0.pre-alpha.M4
     *
     * @var array<string, array<\FireHub\Jezgra\Posrednici\Posrednik::class>>
     */
    'http' => [
        \FireHub\Jezgra\Posrednici\Test1_Posrednik::class,
        \FireHub\Jezgra\Posrednici\Test2_Posrednik::class
    ],

    /**
    |--------------------------------------------------------------------------
    | Globalni konzola posrednici
    |--------------------------------------------------------------------------
     * Lista posrednika koje vrijede za sve konzola zahtjeve sustava.
     * @since 0.4.0.pre-alpha.M4
     *
     * @var array<string, array<\FireHub\Jezgra\Posrednici\Posrednik::class>>
     */
    'konzola' => [
        \FireHub\Jezgra\Posrednici\Gusenje_Posrednik::class,
        \FireHub\Jezgra\Posrednici\Test1_Posrednik::class
    ]

];