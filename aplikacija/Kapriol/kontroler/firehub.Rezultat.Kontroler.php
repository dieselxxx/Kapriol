<?php declare(strict_types = 1);

/**
 * Rezultat
 * @since 0.1.1.pre-alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2022 Kapriol Web Trgovina
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Aplikacija\Kontroler
 */

namespace FireHub\Aplikacija\Kapriol\Kontroler;

use FireHub\Jezgra\Kontroler\Kontroler;
use FireHub\Jezgra\Sadrzaj\Sadrzaj;
use FireHub\Aplikacija\Kapriol\Model\Kategorije_Model;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;
use FireHub\Jezgra\Kontroler\Greske\Kontroler_Greska;

/**
 * ### Rezultat
 * @since 0.1.1.pre-alpha.M1
 *
 * @package Aplikacija\Kontroler
 */
final class Rezultat_Kontroler extends Kontroler {

    /**
     * ## index
     * @since 0.1.0.pre-alpha.M1
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     * @throws Kontroler_Greska Ukoliko objekt nije validan model.
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function index (string $stranica = '', string $kategorija = ''):Sadrzaj {

        $kategorije = $this->model(Kategorije_Model::class);

        return sadrzaj()->datoteka('rezultat.html')->podatci([
            'predlozak_naslov' => ucfirst($kategorija),
            'kategorije' => $kategorije->glavni_meni(),
            'vi_ste_ovdje' => 'Vi ste ovdje: Kapriol Web Trgovina \\\\ ' . ucfirst($kategorija)
        ]);

    }

}