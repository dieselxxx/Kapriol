<?php declare(strict_types = 1);

/**
 * Artikl
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
;
use FireHub\Jezgra\Kontroler\Kontroler;
use FireHub\Jezgra\Sadrzaj\Sadrzaj;
use FireHub\Aplikacija\Kapriol\Model\Kategorije_Model;
use FireHub\Aplikacija\Kapriol\Model\Artikl_Model;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;
use FireHub\Jezgra\Kontroler\Greske\Kontroler_Greska;

/**
 * ### Artikl
 * @since 0.1.2.pre-alpha.M1
 *
 * @package Aplikacija\Kontroler
 */
final class Artikl_Kontroler extends Kontroler {

    /**
     * ## index
     * @since 0.1.2.pre-alpha.M1
     *
     * @param string $kontroler [optional] <p>
     * Trenutni kontroler.
     * </p>
     * @param string $artikl [optional] <p>
     * Naziv artikla.
     * </p>
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     * @throws Kontroler_Greska Ukoliko objekt nije validan model.
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function index (string $kontroler = '', string $artikl = ''):Sadrzaj {

        $kategorije = $this->model(Kategorije_Model::class);

        $artikl_model = $this->model(Artikl_Model::class);

        $trenutni_artikl = $artikl_model->artikl($artikl);

        $artikl_slike = $artikl_model->slike($trenutni_artikl['ID']);

        $artikl_slike_html = '';
        foreach ($artikl_slike as $slike) {

            $artikl_slike_html .= '
                <li>
                    <a data-vrsta="slika" href="/slika/velikaslika/'.$slike['Slika'].'"><img src="/slika/malaslika/'.$slike['Slika'].'" alt=""></a>
                </li>';

        }

        return sadrzaj()->datoteka('artikl.html')->podatci([
            'predlozak_naslov' => $trenutni_artikl['Naziv'],
            'glavni_meni' => $kategorije->glavniMeni(),
            'glavni_meni_hamburger' => $kategorije->glavniMeniHamburger(),
            'vi_ste_ovdje' => 'Vi ste ovdje : <a href="/">Kapriol Web Trgovina</a> \\\\ '.$trenutni_artikl['Kategorija'].' \\\\ ' . $trenutni_artikl['Naziv'],
            'artikl_slika' => 'x',
            'artikl_slike' => $artikl_slike_html
        ]);

    }

}