<?php declare(strict_types = 1);

/**
 * Kosarica
 * @since 0.1.2.pre-alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2022 Kapriol Web Trgovina
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version $id$ vvv
 * @package Aplikacija\Kontroler
 */

namespace FireHub\Aplikacija\Kapriol\Kontroler;

use FireHub\Aplikacija\Kapriol\Model\Gdpr_Model;
use FireHub\Jezgra\Sadrzaj\Sadrzaj;
use FireHub\Aplikacija\Kapriol\Model\Kategorije_Model;
use FireHub\Aplikacija\Kapriol\Model\Kosarica_Model;
use FireHub\Aplikacija\Kapriol\Jezgra\Server;
use FireHub\Aplikacija\Kapriol\Jezgra\Domena;
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

        $gdpr = $this->model(Gdpr_Model::class);

        $kategorije = $this->model(Kategorije_Model::class);

        $kosarica_model = $this->model(Kosarica_Model::class);

        // artikli
        $kosarica_artikli = $kosarica_model->artikli();
        $artikli_html = '';
        $kosarica_artikli_ukupno = '';
        $total_kolicina = 0;
        $total_cijena = 0;
        if (!empty($kosarica_artikli)) {

            foreach ($kosarica_artikli as $artikal) {

                // cijene
                if ($artikal['CijenaAkcija'] > 0) {

                    $artikl_cijena = '
                        <span class="akcija">'.number_format((float)$artikal['CijenaAkcija'], 2, ',', '.').' '.Domena::valuta().'</span>
                        <span class="prekrizi">'.number_format((float)$artikal['Cijena'], 2, ',', '.').' '.Domena::valuta().'</span>
                    ';

                } else {

                    $artikl_cijena = '
                        <span>'.number_format((float)$artikal['Cijena'], 2, ',', '.').' '.Domena::valuta().'</span>
                    ';

                }

                // ako nije dostava
                if ($artikal['ID'] !== '0') {

                    // artikli
                    $artikli_html .= '
                    <form class="artikl" method="post" enctype="multipart/form-data" action="">
                        <img src="/slika/malaslika/'.$artikal['Slika'].'" alt="" loading="lazy"/>
                        <a class="naslov" href="/artikl/'.$artikal['Link'].'">'.$artikal['Naziv'].'</a>
                        <span class="velicina">Veličina: '.$artikal['Velicina'].'</span>
                        <span class="cijena">'.$artikl_cijena.'</span>
                        <h3 class="ukupno">Ukupno: '.number_format((float)$artikal['CijenaUkupno'], 2, ',', '.').' '.Domena::valuta().'</h3>
                        <div class="kosarica">
                            <button type="button" class="gumb" onclick="ArtikalPlusMinus(this, $vrsta = \'minus\');">-</button>
                            <label data-boja="boja" class="unos">
                                <input type="hidden" name="velicina" value="'.$artikal['Sifra'].'">
                                <input type="number" name="vrijednost" value="'.$artikal['Kolicina'].'" data-pakiranje="1" data-maxpakiranje="1000" value="0" min="0" max="100" step="1" autocomplete="off" pattern="0-9">
                                <span class="naslov">
                                    <span></span>
                                </span>
                                <span class="granica"></span>
                            </label>
                            <button type="button" class="gumb" onclick="ArtikalPlusMinus(this, $vrsta = \'plus\');">+</button>
                            <button type="submit" class="gumb ikona" name="kosarica_izmijeni">
                                <svg><use xlink:href="/kapriol/resursi/grafika/simboli/simbol.ikone.svg#uredi"></use></svg>
                                <span>Izmijeni</span>
                            </button>
                            <button type="submit" class="gumb ikona" name="kosarica_izbrisi">
                                <svg><use xlink:href="/kapriol/resursi/grafika/simboli/simbol.ikone.svg#izbrisi"></use></svg>
                                <span>Izbriši</span>
                            </button>
                        </div>
                    </form>
                ';

                } else {

                    // artikli
                    $artikli_html .= '
                    <form class="artikl" method="post" enctype="multipart/form-data" action="">
                        <img src="/slika/malaslika/'.$artikal['Slika'].'" alt="" loading="lazy"/>
                        <a class="naslov" href="/artikl/'.$artikal['Link'].'">'.$artikal['Naziv'].'</a>
                        <h3 class="ukupno">Ukupno: '.number_format((float)$artikal['CijenaUkupno'], 2, ',', '.').' '.Domena::valuta().'</h3>
                    </form>
                ';


                }

                // ukupno
                if ($artikal['Naziv'] !== 'Dostava') {

                    // ukupno količina
                    $total_kolicina += $artikal['Kolicina'];

                }
                $total_cijena += $artikal['CijenaUkupno'];

                $kosarica_artikli_ukupno = '
                    <ul>
                        <li>Ukupna količina: '.$total_kolicina.'</li>
                        <li class="ukupno">Ukupna cijena: <span>'.number_format((float)$total_cijena, 2, ',', '.').' '.Domena::valuta().'</span></li>
                    </ul>
                    <a data-boja="boja" class="gumb ikona" href="/kosarica/narudzbab2b">
                        <svg><use xlink:href="/kapriol/resursi/grafika/simboli/simbol.ikone.svg#strelica_desno_duplo2"></use></svg>
                        <span>Nastavi</span>
                    </a>
                ';

            }

        } else {

            $artikli_html = '<h2>Vaša košarica je prazna!</h2>';

        }

        return sadrzaj()->datoteka('kosarica.html')->podatci([
            'predlozak_opis' => Domena::opis(),
            'predlozak_GA' => Domena::GA(),
            'predlozak_naslov' => 'Košarica',
            'facebook_link' => Domena::facebook(),
            'instagram_link' => Domena::instagram(),
            'mobitel' => Domena::mobitel(),
            'glavni_meni' => $kategorije->glavniMeni(),
            'glavni_meni_hamburger' => $kategorije->glavniMeniHamburger(),
            'zaglavlje_kosarica_artikli' => $this->kosaricaArtikli(),
            'zaglavlje_kosarica_artikli_html' => $this->kosaricaArtikliHTML(),
            'zaglavlje_favorit_artikli' => $this->favoritArtikli(),
            'zaglavlje_tel' => Domena::telefon(),
            'zaglavlje_adresa' => Domena::adresa(),
            'podnozje_dostava' => Domena::podnozjeDostava(),
            'gdpr' => $gdpr->html(),
            'vi_ste_ovdje' => '<a href="/">Kapriol Web Trgovina</a> \\\\ Košarica',
            'opci_uvjeti' => Domena::opciUvjeti(),
            'kosarica_artikli' => $artikli_html,
            'kosarica_artikli_ukupno' => $kosarica_artikli_ukupno
        ]);

    }

    /**
     * ### Narudzba
     * @since 0.1.2.pre-alpha.M1
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     * @throws Kontroler_Greska Ukoliko objekt nije validan model.
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function narudzba ():Sadrzaj {

        $gdpr = $this->model(Gdpr_Model::class);

        if ($this->kosaricaArtikli() === '0') {

            header("Location: ".Server::URL());

        }

        $kategorije = $this->model(Kategorije_Model::class);

        $kosarica_model = $this->model(Kosarica_Model::class);

        $narudzba_greska = '';
        if (isset($_POST['naruci'])) {

            try {

                $this->model(Kosarica_Model::class)->naruci();

                header("Location: ".Server::URL()."/kosarica/ispravno");

            } catch (\Throwable $greska) {

                $narudzba_greska = $greska->getMessage();

            }

        }

        return sadrzaj()->datoteka('narudzba.html')->podatci([
            'predlozak_opis' => Domena::opis(),
            'predlozak_GA' => Domena::GA(),
            'predlozak_naslov' => 'Narudžba',
            'facebook_link' => Domena::facebook(),
            'mobitel' => Domena::mobitel(),
            'glavni_meni' => $kategorije->glavniMeni(),
            'glavni_meni_hamburger' => $kategorije->glavniMeniHamburger(),
            'zaglavlje_kosarica_artikli' => $this->kosaricaArtikli(),
            'zaglavlje_kosarica_artikli_html' => $this->kosaricaArtikliHTML(),
            'zaglavlje_favorit_artikli' => $this->favoritArtikli(),
            'zaglavlje_tel' => Domena::telefon(),
            'zaglavlje_adresa' => Domena::adresa(),
            'podnozje_dostava' => Domena::podnozjeDostava(),
            'gdpr' => $gdpr->html(),
            'vi_ste_ovdje' => '<a href="/">Kapriol Web Trgovina</a> \\\\ Narudžba',
            'opci_uvjeti' => Domena::opciUvjeti(),
            'narudzba_greska' => $narudzba_greska,
            'forma_ime' => $_POST['ime'] ?? '',
            'forma_email' => $_POST['email'] ?? '',
            'forma_telefon' => $_POST['telefon'] ?? '',
            'forma_adresa' => $_POST['adresa'] ?? '',
            'forma_zip' => $_POST['zip'] ?? '',
            'forma_napomena' => $_POST['napomena'] ?? ''
        ]);

    }

    /**
     * ### Narudzba B2B
     * @since 0.1.2.pre-alpha.M1
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     * @throws Kontroler_Greska Ukoliko objekt nije validan model.
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function narudzbab2b ():Sadrzaj {

        $gdpr = $this->model(Gdpr_Model::class);

        if ($this->kosaricaArtikli() === '0') {

            header("Location: ".Server::URL());

        }

        $kategorije = $this->model(Kategorije_Model::class);

        $kosarica_model = $this->model(Kosarica_Model::class);

        $narudzba_greska = '';
        if (isset($_POST['naruci'])) {

            try {

                $this->model(Kosarica_Model::class)->narucib2b();

                header("Location: ".Server::URL()."/kosarica/ispravno");

            } catch (\Throwable $greska) {

                $narudzba_greska = $greska->getMessage();

            }

        }

        return sadrzaj()->datoteka('narudzba_b2b.html')->podatci([
            'predlozak_opis' => Domena::opis(),
            'predlozak_GA' => Domena::GA(),
            'predlozak_naslov' => 'Narudžba',
            'facebook_link' => Domena::facebook(),
            'instagram_link' => Domena::instagram(),
            'glavni_meni' => $kategorije->glavniMeni(),
            'glavni_meni_hamburger' => $kategorije->glavniMeniHamburger(),
            'zaglavlje_kosarica_artikli' => $this->kosaricaArtikli(),
            'zaglavlje_kosarica_artikli_html' => $this->kosaricaArtikliHTML(),
            'zaglavlje_favorit_artikli' => $this->favoritArtikli(),
            'zaglavlje_tel' => Domena::telefon(),
            'zaglavlje_adresa' => Domena::adresa(),
            'podnozje_dostava' => Domena::podnozjeDostava(),
            'gdpr' => $gdpr->html(),
            'vi_ste_ovdje' => '<a href="/">Kapriol Web Trgovina</a> \\\\ Narudžba',
            'opci_uvjeti' => Domena::opciUvjeti(),
            'domena_oibpdv' => Domena::OIBPDV(),
            'domena_valuta' => Domena::valuta(),
            'narudzba_greska' => $narudzba_greska,
            'forma_ime' => $_POST['ime'] ?? '',
            'forma_email' => $_POST['email'] ?? '',
            'forma_telefon' => $_POST['telefon'] ?? '',
            'forma_grad' => $_POST['grad'] ?? '',
            'forma_adresa' => $_POST['adresa'] ?? '',
            'forma_zip' => $_POST['zip'] ?? '',
            'forma_tvrtka' => $_POST['tvrtka'] ?? '',
            'forma_oib' => $_POST['oib'] ?? '',
            'forma_tvrtka_adresa' => $_POST['tvrtkaadresa'] ?? '',
            'forma_placanje' => $_POST['placanje'] ?? '',
            'forma_napomena' => $_POST['napomena'] ?? ''
        ]);

    }

    /**
     * ### Odabir vrste narudžbe
     * @since 0.1.2.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function odabir ():Sadrzaj {

        $gdpr = $this->model(Gdpr_Model::class);

        $kategorije = $this->model(Kategorije_Model::class);

        return sadrzaj()->datoteka('narudzba_vrsta.html')->podatci([
            'predlozak_opis' => Domena::opis(),
            'predlozak_GA' => Domena::GA(),
            'predlozak_naslov' => 'Vrsta narudžbe',
            'facebook_link' => Domena::facebook(),
            'instagram_link' => Domena::instagram(),
            'mobitel' => Domena::mobitel(),
            'glavni_meni' => $kategorije->glavniMeni(),
            'glavni_meni_hamburger' => $kategorije->glavniMeniHamburger(),
            'zaglavlje_kosarica_artikli' => $this->kosaricaArtikli(),
            'zaglavlje_kosarica_artikli_html' => $this->kosaricaArtikliHTML(),
            'zaglavlje_favorit_artikli' => $this->favoritArtikli(),
            'zaglavlje_tel' => Domena::telefon(),
            'zaglavlje_adresa' => Domena::adresa(),
            'podnozje_dostava' => Domena::podnozjeDostava(),
            'gdpr' => $gdpr->html(),
            'vi_ste_ovdje' => '<a href="/">Kapriol Web Trgovina</a> \\\\ Vrsta narudžbe',
            'opci_uvjeti' => Domena::opciUvjeti()
        ]);

    }

    /**
     * ### Ispravna narudžba
     * @since 0.1.2.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function ispravno ():Sadrzaj {

        $gdpr = $this->model(Gdpr_Model::class);

        $kategorije = $this->model(Kategorije_Model::class);

        return sadrzaj()->datoteka('narudzba_ispravno.html')->podatci([
            'predlozak_opis' => Domena::opis(),
            'predlozak_GA' => Domena::GA(),
            'predlozak_naslov' => 'Naslovna',
            'facebook_link' => Domena::facebook(),
            'instagram_link' => Domena::instagram(),
            'mobitel' => Domena::mobitel(),
            'glavni_meni' => $kategorije->glavniMeni(),
            'glavni_meni_hamburger' => $kategorije->glavniMeniHamburger(),
            'zaglavlje_kosarica_artikli' => $this->kosaricaArtikli(),
            'zaglavlje_kosarica_artikli_html' => $this->kosaricaArtikliHTML(),
            'zaglavlje_favorit_artikli' => $this->favoritArtikli(),
            'zaglavlje_tel' => Domena::telefon(),
            'zaglavlje_adresa' => Domena::adresa(),
            'podnozje_dostava' => Domena::podnozjeDostava(),
            'gdpr' => $gdpr->html(),
            'vi_ste_ovdje' => 'Vi ste ovdje : <a href="/">Kapriol Web Trgovina</a>',
            'opci_uvjeti' => Domena::opciUvjeti()
        ]);

    }

}