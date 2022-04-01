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

use FireHub\Aplikacija\Kapriol\Model\Gdpr_Model;
use FireHub\Jezgra\Komponente\BazaPodataka\BazaPodataka;
use FireHub\Jezgra\Sadrzaj\Sadrzaj;
use FireHub\Aplikacija\Kapriol\Model\Kategorije_Model;
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
    public function index (BazaPodataka $bazaPodataka = null):Sadrzaj {

        $gdpr = $this->model(Gdpr_Model::class);

        $kategorije = $this->model(Kategorije_Model::class);

        $obavijest_html = '';
        $obavijesti = $bazaPodataka->tabela('obavijesti')
            ->sirovi("
                SELECT 
                    Obavijest, artikliview.Link
                FROM obavijesti
                LEFT JOIN artikliview ON artikliview.ID = obavijesti.ArtikalID
                WHERE obavijesti.".Domena::sqlTablica()." = 1
                ORDER BY obavijesti.Redoslijed ASC
            ")->napravi();

        foreach ($obavijesti->niz() as $obavijest) {

            $link = $obavijest['Link'] ? 'href="/artikl/'.$obavijest['Link'].'"' : '' ;

            $obavijest_html .= "
            <a class='swiper-slide' $link>
                <img src='/slika/baner/{$obavijest['Obavijest']}' />
            </a>
            ";

        }

        return sadrzaj()->datoteka('naslovna.html')->podatci([
            'predlozak_naslov' => 'Naslovna',
            'facebook_link' => Domena::facebook(),
            'instagram_link' => Domena::instagram(),
            'mobitel' => Domena::mobitel(),
            'glavni_meni' => $kategorije->glavniMeni(),
            'opci_uvjeti' => Domena::opciUvjeti(),
            'glavni_meni_hamburger' => $kategorije->glavniMeniHamburger(),
            'zaglavlje_kosarica_artikli' => $this->kosaricaArtikli(),
            'zaglavlje_kosarica_artikli_html' => $this->kosaricaArtikliHTML(),
            'zaglavlje_favorit_artikli' => $this->favoritArtikli(),
            'zaglavlje_tel' => Domena::telefon(),
            'zaglavlje_adresa' => Domena::adresa(),
            'podnozje_dostava' => Domena::podnozjeDostava(),
            'kategorije' => $kategorije->kategorijeNaslovna(),
            'gdpr' => $gdpr->html(),
            'dostavaLimit' => ''.Domena::dostavaLimit().'',
            'valuta' => ''.Domena::valuta().'',
            'obavijesti' => $obavijest_html,
            'reklama1vrijeme' => ''.filemtime(APLIKACIJA_ROOT.'../../'.konfiguracija('sustav.putanje.web').'kapriol/resursi/grafika/reklame/reklama1.jpg').'',
            'reklama2vrijeme' => ''.filemtime(APLIKACIJA_ROOT.'../../'.konfiguracija('sustav.putanje.web').'kapriol/resursi/grafika/reklame/reklama2.jpg').''
        ]);

    }

}