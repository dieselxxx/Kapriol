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
final class Artikl_Kontroler extends Master_Kontroler {

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

        // slike
        $artikl_slike = $artikl_model->slike($trenutni_artikl['ID']);
        $artikl_slike_html = '';
        foreach ($artikl_slike as $slike) {

            $artikl_slike_html .= '
                <li>
                    <a data-vrsta="slika" href="/slika/velikaslika/'.$slike['Slika'].'"><img src="/slika/malaslika/'.$slike['Slika'].'" alt=""></a>
                </li>';

        }

        // cijene
        if ($trenutni_artikl['CijenaAkcija'] > 0) {

            $artikl_cijena = '
                <span class="prekrizi">'.number_format((float)$trenutni_artikl['Cijena'], 2, ',', '.').' KM</span>
                <h2 class="akcija">'.number_format((float)$trenutni_artikl['CijenaAkcija'], 2, ',', '.').' KM</h2>
            ';

        } else {

            $artikl_cijena = '
                <h2>'.number_format((float)$trenutni_artikl['Cijena'], 2, ',', '.').' KM</h2>
            ';

        }

        // zaliha
        $artikl_zaliha = $artikl_model->zaliha($trenutni_artikl['ID']);
        $artikl_zaliha_html = '';
        $artikl_kosarica_velicine = '';
        foreach ($artikl_zaliha as $zaliha) {

            if ((int)$zaliha['StanjeSkladisteTF'] === 1) {

                $artikl_zaliha_html .= '<li><span class="gumb dostupno">'.$zaliha['Velicina'].'</span></li>';

                $artikl_kosarica_velicine .= '<option value="'.$zaliha['artiklikarakteristikeSifra'].'">'.$zaliha['Velicina'].'</option>';

            } else {

                $artikl_zaliha_html .= '<li><span class="gumb nedostupno">'.$zaliha['Velicina'].'</span></li>';

            }

        }

        return sadrzaj()->datoteka('artikl.html')->podatci([
            'predlozak_naslov' => $trenutni_artikl['Naziv'],
            'glavni_meni' => $kategorije->glavniMeni(),
            'glavni_meni_hamburger' => $kategorije->glavniMeniHamburger(),
            'zaglavlje_kosarica_artikli' => $this->kosaricaArtikli(),
            'vi_ste_ovdje' => 'Vi ste ovdje : <a href="/">Kapriol Web Trgovina</a> \\\\ '.$trenutni_artikl['Kategorija'].' \\\\ ' . $trenutni_artikl['Naziv'],
            'artikl_slika' => $trenutni_artikl['Slika'],
            'artikl_slike' => $artikl_slike_html,
            'artikl_naziv' => $trenutni_artikl['Naziv'],
            'artikl_cijena' => $artikl_cijena,
            'artikl_zaliha' => $artikl_zaliha_html,
            'artikl_kosarica_velicine' => $artikl_kosarica_velicine,
            'artikl_opis' => $trenutni_artikl['Opis']
        ]);

    }

}