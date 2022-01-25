<?php declare(strict_types = 1);

/**
 * Master
 * @since 0.1.2.pre-alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2022 Kapriol Web Trgovina
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Aplikacija\Kontroler
 */

namespace FireHub\Aplikacija\Administrator\Kontroler;

use FireHub\Jezgra\Kontroler\Kontroler;
use FireHub\Jezgra\Komponente\Sesija\Sesija;
use FireHub\Jezgra\Komponente\Sesija\Sesija_Interface;
use FireHub\Aplikacija\Kapriol\Jezgra\Server;

/**
 * ### Master
 * @since 0.1.2.pre-alpha.M1
 *
 * @package Aplikacija\Kontroler
 */
abstract class Master_Kontroler extends Kontroler {

    private Sesija_Interface $sesija;

    /**
     * ### Konstruktor
     * @since 0.1.2.pre-alpha.M1
     */
    public function __construct () {

        // napravi sesiju
        $this->sesija = (new Sesija())->naziv('Kapriol')->napravi();

        if (!$this->sesija->procitaj('korisnik')) {

            header("Location: ".Server::URL()."/administrator/prijava");

        }

    }

}