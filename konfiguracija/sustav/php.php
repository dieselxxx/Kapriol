<?php declare(strict_types = 1);

/**
 * Datoteka za konfiguraciju PHP postavka sustava
 *
 * Ulazna točka za konfiguraciju svih servisa.
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
     * Ekstenzije
     * --------------------------------------------------------------------------
     * Nužne PHP ekstenzije kako bi sustav mogao normalano raditi.
     * @since 0.3.5.pre-alpha.M3
     *
     * @var array<string, array<string>>
     */
    'ekstenzije' => [
        'gettext' // lokalizacija
    ],

    /**
     * --------------------------------------------------------------------------
     * Postavke
     * --------------------------------------------------------------------------
     * Zadane PHP i PHP.ini postavke.
     * @since 0.3.5.pre-alpha.M3
     *
     * @var array<string, array<string, string>>
     */
    'postavke' => [
        'display_errors' => '0', // prikaz php greški na stranici
        'display_startup_errors' => '0', // prikaz greški od PHP redoslijedu pokretanja
        'memory_limit' => '64M', // limit memorije po korisniku
        'log_errors' => '1', // zapiši greške u serverski log
        'log_errors_max_len' => '1024', // maksimalna veličina log datoteke u bajtima
        'ignore_repeated_errors' => '0', // ne ponavljaj iste greške u istoj liniji koda
        'ignore_repeated_source' => '0', // ne ponavljaj iste greške iz različitih izvora
        'error_log' => 'log/greske.log', // throw greške koje nisu uhvaćene
        //'session.use_strict_mode' => '1', // ne dopusti neinicializirane ID-ove sesija
        //'session.gc_probability' => '1', // debian / ubunutu kolektor smeća za sesije
        'session.cookie_samesite' => 'Lax' // zadana cross-domain opcije za sesije da bude Strict (zadano Unset, druga opcija Strict)
    ]

];