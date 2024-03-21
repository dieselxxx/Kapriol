<?php declare(strict_types = 1);

/**
 * Poslovnice
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

use FireHub\Aplikacija\Kapriol\Model\Gdpr_Model;
use FireHub\Jezgra\Sadrzaj\Sadrzaj;
use FireHub\Aplikacija\Kapriol\Model\Kategorije_Model;
use FireHub\Aplikacija\Kapriol\Jezgra\Domena;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;
use FireHub\Jezgra\Kontroler\Greske\Kontroler_Greska;

/**
 * ### Poslovnice
 * @since 0.1.2.pre-alpha.M1
 *
 * @package Aplikacija\Kontroler
 */
final class Poslovnice_Kontroler extends Master_Kontroler {

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

        $gdpr = $this->model(Gdpr_Model::class);

        $kategorije = $this->model(Kategorije_Model::class);

        return sadrzaj()->datoteka(Domena::poslovnice())->podatci([
            'predlozak_opis' => Domena::opis(),
            'predlozak_GA' => Domena::GA(),
            'predlozak_naslov' => 'Poslovnice',
            'linkerRetargeting' => Domena::linkerRetargeting(),
            'facebook_link' => Domena::facebook(),
            'instagram_link' => Domena::instagram(),
            'mobitel' => Domena::mobitel(),
            'prodajni_predstavik' => Domena::prodajniPredstavnik(),
            'glavni_meni' => $kategorije->glavniMeni(),
            'glavni_meni_hamburger' => $kategorije->glavniMeniHamburger(),
            'zaglavlje_kosarica_artikli' => $this->kosaricaArtikli(),
            'zaglavlje_kosarica_artikli_html' => $this->kosaricaArtikliHTML(),
            'zaglavlje_favorit_artikli' => $this->favoritArtikli(),
            'zaglavlje_tel' => Domena::telefon(),
            'zaglavlje_adresa' => Domena::adresa(),
            'podnozje_dostava' => Domena::podnozjeDostava(),
            'gdpr' => $gdpr->html(),
            'vi_ste_ovdje' => '<a href="/">Kapriol Web Trgovina</a> \\\\ Poslovnice',
            'opci_uvjeti' => Domena::opciUvjeti(),
        ]);

    }

}