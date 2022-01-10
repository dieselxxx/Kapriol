<?php declare(strict_types = 1);

/**
 * Datoteka za MySQL query jezik
 * @since 0.6.0.alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\BazaPodataka\Servisi\Jezik;

/**
 * ### MySQL query jezik
 * @since 0.6.0.alpha.M1
 *
 * @package Sustav\Jezgra
 */
class MySQL extends SQL {

    public function obradi (string $baza, string $tabela):string {

        return 'SELECT * FROM test LIMIT 2';

    }

}