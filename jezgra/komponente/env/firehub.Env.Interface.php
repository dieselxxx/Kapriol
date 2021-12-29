<?php declare(strict_types = 1);

/**
 * Datoteka interface-a za env servise
 * @since 0.3.3.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\Env;

use FireHub\Jezgra\Komponente\Servis_Interface;

/**
 * ### Interface-a za env servise
 * @since 0.3.3.pre-alpha.M3
 *
 * @package Sustav\Jezgra
 */
interface Env_Interface extends Servis_Interface {

    /**
     * ### Pročitaj env zapis
     * @since 0.3.3.pre-alpha.M3
     *
     * @param string $env <p>
     * FQN env datoteke.
     * <p>
     * @param string|int|bool|null $zadano <p>
     * Zadana vrijednost env zapisa ukoliko ne postoji traženi env zapis.
     * <p>
     *
     * @return string|int|float|bool|null Vrijednost env zapisa.
     */
    public static function procitaj (string $env, string|int|float|bool|null $zadano = null):string|int|float|bool|null;

}