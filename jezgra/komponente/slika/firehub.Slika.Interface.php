<?php declare(strict_types = 1);

/**
 * Datoteka ugovora poslužitelja slika
 * @since 0.6.1.alpha.M6
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\Slika;

use FireHub\Jezgra\Komponente\Servis_Interface;

/**
 * ### Osnovni interface za poslužitelja baze podataka
 * @since 0.6.1.alpha.M6
 *
 * @package Sustav\Jezgra
 */
interface Slika_Interface extends Servis_Interface {

    /**
     * ### Ispiši sliku
     * @since 0.6.1.alpha.M6
     *
     * @return void
     */
    public function ispisi ():void;

}