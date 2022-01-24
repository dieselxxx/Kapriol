<?php declare(strict_types = 1);

/**
 * Naslovna
 * @since 0.1.0.pre-alpha.M1
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
use FireHub\Aplikacija\Kapriol\Model\Artikli_Model;
use FireHub\Aplikacija\Kapriol\Jezgra\Domena;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;
use FireHub\Jezgra\Kontroler\Greske\Kontroler_Greska;

/**
 * ### Naslovna
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Kontroler
 */
final class Naslovna_Kontroler extends Master_Kontroler {

    /**
     * ## index
     * @since 0.1.0.pre-alpha.M1
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     * @throws Kontroler_Greska Ukoliko objekt nije validan model.
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function index ():Sadrzaj {

        $kategorije = $this->model(Kategorije_Model::class);

        $artikli = $this->model(Artikli_Model::class)->artikli('izdvojeno', 1, 1, 1, 1, 'Naziv', 'ASC');

        // artikli
        $artikli_html = '';
        foreach ($artikli as $artikal) {

            // cijene
            if ($artikal['CijenaAkcija'] > 0) {

                $artikl_cijena = '
                <span class="prekrizi">'.number_format((float)$artikal['Cijena'], 2, ',', '.').' '.Domena::valuta().'</span>
                <h2 class="akcija">'.number_format((float)$artikal['CijenaAkcija'], 2, ',', '.').' '.Domena::valuta().'</h2>
            ';

            } else {

                $artikl_cijena = '
                <h2>'.number_format((float)$artikal['Cijena'], 2, ',', '.').' '.Domena::valuta().'</h2>
            ';

            }

            $artikli_html .= <<<Artikal
            
                <a class="artikal" href="/artikl/{$artikal['Link']}">
                    <img src="/slika/malaslika/{$artikal['Slika']}" alt="" loading="lazy"/>
                    <span class="naslov">{$artikal['Naziv']}</span>
                    <span class="cijena">$artikl_cijena</span>
                    <span class="zaliha"></span>
                </a>

            Artikal;

        }

        return sadrzaj()->datoteka('naslovna.html')->podatci([
            'predlozak_naslov' => 'Naslovna',
            'glavni_meni' => $kategorije->glavniMeni(),
            'glavni_meni_hamburger' => $kategorije->glavniMeniHamburger(),
            'zaglavlje_kosarica_artikli' => $this->kosaricaArtikli(),
            'zaglavlje_tel' => Domena::telefon(),
            'zaglavlje_adresa' => Domena::adresa(),
            'artikli' => $artikli_html
        ]);

    }

}