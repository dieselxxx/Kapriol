<?php declare(strict_types = 1);

/**
 * Kosarica
 * @since 0.1.2.pre-alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2022 Kapriol Web Trgovina
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Aplikacija\Kontroler
 */

namespace FireHub\Aplikacija\Kapriol\Kontroler;

use FireHub\Jezgra\Sadrzaj\Sadrzaj;
use FireHub\Aplikacija\Kapriol\Model\Kategorije_Model;
use FireHub\Aplikacija\Kapriol\Model\Kosarica_Model;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;
use FireHub\Jezgra\Kontroler\Greske\Kontroler_Greska;

/**
 * ### Kosarica
 * @since 0.1.2.pre-alpha.M1
 *
 * @package Aplikacija\Kontroler
 */
final class Kosarica_Kontroler extends Master_Kontroler {

    /**
     * ### index
     * @since 0.1.2.pre-alpha.M1
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     * @throws Kontroler_Greska Ukoliko objekt nije validan model.
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function index ():Sadrzaj {

        $kategorije = $this->model(Kategorije_Model::class);

        $kosarica_model = $this->model(Kosarica_Model::class);

        // artikli
        $kosarica_artikli = $kosarica_model->artikli();
        var_dump($kosarica_artikli);
        $kosarica_artikli_html = '';
        if (!empty($kosarica_artikli)) {

            foreach ($kosarica_artikli as $artikal) {

                $kosarica_artikli_html .= '
                    <div></div>
                ';

            }

        } else {

            $kosarica_artikli_html = '<h2>Vaša košarica je prazna!</h2>';

        }

        return sadrzaj()->datoteka('kosarica.html')->podatci([
            'predlozak_naslov' => 'Košarica',
            'glavni_meni' => $kategorije->glavniMeni(),
            'glavni_meni_hamburger' => $kategorije->glavniMeniHamburger(),
            'vi_ste_ovdje' => '<a href="/">Kapriol Web Trgovina</a> \\\\ Košarica',
            'kosarica_artikli' => $kosarica_artikli_html
        ]);

    }

}