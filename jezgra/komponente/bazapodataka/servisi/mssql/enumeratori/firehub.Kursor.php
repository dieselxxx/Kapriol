<?php declare(strict_types = 1);

/**
 * Datoteka za enumerator za dostupne vrste kursora za MSSQL server
 * @since 0.5.1.pre-alpha.M5
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\BazaPodataka\Servisi\MSSQL\Enumeratori;

use FireHub\Jezgra\Komponente\BazaPodataka\Kursor_Interface;

/**
 * ### Enumerator za dostupne vrste kursora za MSSQL server
 * @since 0.5.1.pre-alpha.M5
 *
 * @package Sustav\Jezgra
 */
enum Kursor implements Kursor_Interface {

    case SQLSRV_CURSOR_FORWARD;
    case SQLSRV_CURSOR_STATIC;
    case SQLSRV_CURSOR_DYNAMIC;
    case SQLSRV_CURSOR_KEYSET;
    case SQLSRV_CURSOR_CLIENT_BUFFERED;

    /**
     * @inheritDoc
     */
    public function vrijednost ():string {

        return match ($this) {
            self::SQLSRV_CURSOR_FORWARD => SQLSRV_CURSOR_FORWARD,
            self::SQLSRV_CURSOR_STATIC => SQLSRV_CURSOR_STATIC,
            self::SQLSRV_CURSOR_DYNAMIC => SQLSRV_CURSOR_DYNAMIC,
            self::SQLSRV_CURSOR_KEYSET => SQLSRV_CURSOR_KEYSET,
            self::SQLSRV_CURSOR_CLIENT_BUFFERED => SQLSRV_CURSOR_CLIENT_BUFFERED
        };

    }

}