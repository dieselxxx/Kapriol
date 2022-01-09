<?php declare(strict_types = 1);

/**
 * Datoteka za konfiguraciju parametara dostupnih baza podataka
 * @since 0.5.1.pre-alpha.M5
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
     * Vrste baza podataka i njihove potrebne ekstenzije i parametri.
     * Ovi parameteri su zadani za sve aplikacije i prepisuje ih datoteka sa prvih ključevima kao parametrima.
     * @since 0.5.1.pre-alpha.M5
     *
     * @var array<string, array<string, array>>
     */
    'konekcije' => [
        'MSSQL' => [
            'ekstenzije' => [
                'pdo_sqlsrv', 'sqlsrv'
            ],
            'parametri' => [
                'servis' => \FireHub\Jezgra\Komponente\BazaPodataka\Servisi\MSSQL::class,
                'host' => 'localhost',
                'port' => 1433,
                'instanca' => 'MSSQLSERVER',
                'baza' => 'FireHub',
                'shema' => 'dbo',
                'korisnicko_ime' => '',
                'lozinka' => '',
                'karakteri' => 'UTF-8',
                'greske' => -1, // SQLSRV_LOG_SEVERITY_ALL, SQLSRV_LOG_SEVERITY_ERROR, SQLSRV_LOG_SEVERITY_NOTICE, SQLSRV_LOG_SEVERITY_WARNING
                'vrste_greski' => 0, // SQLSRV_LOG_SYSTEM_ALL, SQLSRV_LOG_SYSTEM_CONN, SQLSRV_LOG_SYSTEM_INIT, SQLSRV_LOG_SYSTEM_OFF, SQLSRV_LOG_SYSTEM_STMT, SQLSRV_LOG_SYSTEM_UTIL
                'odziv' => 500,
                'stream_u_dijelovima' => true,
                'kursor' => \FireHub\Jezgra\Komponente\BazaPodataka\Servisi\MSSQL\Enumeratori\Kursor::SQLSRV_CURSOR_FORWARD
            ]
        ],
        'MYSQL' => [
            'ekstenzije' => [
                'mysqli'
            ],
            'parametri' => [
                'servis' => \FireHub\Jezgra\Komponente\BazaPodataka\Servisi\MySQL::class,
                'host' => 'localhost',
                'baza' => 'FireHub',
                'shema' => 'dbo',
                'korisnicko_ime' => '',
                'lozinka' => ''
            ]
        ],
        'MONGODB' => [
            'ekstenzije' => [
                'mongodb'
            ],
            'parametri' => [
                'servis' => \FireHub\Jezgra\Komponente\BazaPodataka\Servisi\MongoDB::class,
                'host' => '192.168.8.50',
                'port' => 27017,
                'baza' => 'firehub',
                'korisnicko_ime' => 'root',
                'lozinka' => 'toor'
            ]
        ]
    ],

    /**
    |--------------------------------------------------------------------------
    | Zadana baza podataka
    |--------------------------------------------------------------------------
     * Zadana baza podataka ukoliko nije navedena u konfiguracijskim
     * datotekama aplikacije.
     * @since 0.5.1.pre-alpha.M5
     *
     * @var array<string, string>
     */
    'server' => 'MSSQL'

];