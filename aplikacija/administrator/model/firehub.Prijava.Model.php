<?php declare(strict_types = 1);

/**
 * Prijava model
 * @since 0.1.2.pre-alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2022 Kapriol Web Trgovina
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Aplikacija\Model
 */

namespace FireHub\Aplikacija\Administrator\Model;

use FireHub\Jezgra\Greske\Greska;

/**
 * ### Prijava model
 * @since 0.1.2.pre-alpha.M1
 *
 * @package Aplikacija\Model
 */
final class Prijava_Model extends Master_Model {

    /**
     * ### Konstruktor
     * @since 0.1.2.pre-alpha.M1
     *
     * @throws Greska
     */
    public function __construct (
    ) {

        parent::__construct();

        // ako nisu poslani svi podatci za prijavu
        if (!isset($_REQUEST["korisnicko_ime"]) || !isset($_REQUEST["lozinka"]) || !isset($_REQUEST["ip"])) {

            throw new Greska('Nema dovoljno podataka za prijavu!');

        }

    }

}