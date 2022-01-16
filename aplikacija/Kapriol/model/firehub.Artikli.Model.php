<?php declare(strict_types = 1);

/**
 * Artikli model
 * @since 0.1.1.pre-alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2022 Kapriol Web Trgovina
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Aplikacija\Model
 */

namespace FireHub\Aplikacija\Kapriol\Model;

use FireHub\Jezgra\Model\Model;
use FireHub\Jezgra\Komponente\BazaPodataka\BazaPodataka;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;

/**
 * ### Artikli model
 * @since 0.1.1.pre-alpha.M1
 *
 * @package Aplikacija\Model
 */
final class Artikli_Model extends Model {

    /**
     * ### Konstruktor
     * @since 0.1.1.pre-alpha.M1
     */
    public function __construct (
        private BazaPodataka $bazaPodataka
    ) {}

    /**
     * ### Dohvati artikle
     * @since 0.1.1.pre-alpha.M1
     *
     * @param int|string $kategorija <p>
     * ID kategorije.
     * </p>
     * @param int $pomak <p>
     * Pomak od kojeg se limitiraju zapisi.
     * </p>
     * @param int $limit <p>
     * Broj redaka koje odabiremo.
     * </p>
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca objekta.
     *
     * @return array Niz artikala.
     */
    public function artikli (int|string $kategorija, int $pomak, int $limit):array {

        $artikli = $this->bazaPodataka->tabela('artikliview')
            ->odaberi(['Naziv', 'Link', 'Opis', 'Cijena', 'CijenaAkcija', 'Slika'])
            ->spoji('slikeartikal', 'ClanakID', 'artikliview.ID')
            ->gdje('KategorijaID', '=', $kategorija)
            ->gdje('Aktivan' , '=', 1)
            ->gdje('Ba', '=', 1)
            ->gdje('Zadana', '=', 1)
            ->limit($pomak, $limit)
            ->poredaj('Naziv', 'ASC')
            ->napravi();

        return $artikli->niz() ?: [];

    }

    /**
     * ### Ukupnjo pronađenih redaka
     * @since 0.1.1.pre-alpha.M1
     *
     * @param int|string $kategorija <p>
     * ID kategorije.
     * </p>
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca objekta.
     *
     * @return int Broj pronađenih redaka.
     */
    public function ukupnoRedaka (int|string $kategorija) {

        $ukupno_redaka = $this->bazaPodataka->tabela('artikliview')
            ->odaberi(['Naziv'])
            ->gdje('KategorijaID', '=', $kategorija)
            ->gdje('Aktivan' , '=', 1)
            ->gdje('Ba', '=', 1)
            ->napravi();

        return $ukupno_redaka->broj_zapisa();

    }

    public function ukupnoRedakaHTML (int|string $kategorija, int $limit, string $url = '/', int $broj_stranice = 1, string $boja = 'boja', $poredaj = 'Naziv', $poredaj_redosijed = 'ASC') {

        $broj_zapisa = $this->ukupnoRedaka($kategorija);

        $pocetak_link_stranice = "";
        $link_stranice = "";
        $kraj_link_stranice = "";

        $ukupno_stranica = ceil($broj_zapisa / $limit);
        if (($broj_stranice - 2) < 1) {$x = 1;} else {$x = ($broj_stranice - 2);}
        if (($broj_stranice + 2) >= $ukupno_stranica) {$y = $ukupno_stranica;} else {$y = ($broj_stranice + 2);}

        if ($broj_stranice >= 2) {

            $prosla_stranica = $broj_stranice - 1;

            if ($url) {$url_prva_stranica = "href='{$url}/1'";} else {$url_prva_stranica = "";}
            if ($url) {$url_prosla_stranica = "href='{$url}/{$prosla_stranica}'";} else {$url_prosla_stranica = "";}

            $pocetak_link_stranice .= "<li><a class='gumb ikona' {$url_prva_stranica}><svg data-boja='{$boja}'><use xlink:href=\"/kapriol/resursi/grafika/simboli/simbol.ikone.svg#strelica_lijevo_duplo\" /></svg></a></li>";
            $pocetak_link_stranice .= "<li><a class='gumb ikona' {$url_prosla_stranica}><svg data-boja='{$boja}'><use xlink:href=\"/kapriol/resursi/grafika/simboli/simbol.ikone.svg#strelica_lijevo\" /></svg></a></li>";

        }

        for ($i = $x; $i <= $y; $i++) {

            if ($url) {$url_broj_stranice = "href='{$url}/{$i}'";} else {$url_broj_stranice = "";}

            if ($i == $broj_stranice) {

                $link_stranice .= "<li><a class='gumb' data-boja='{$boja}' {$url_broj_stranice}>{$i}</a></li>";

            }  else {

                $link_stranice .= "<li><a class='gumb' {$url_broj_stranice}>{$i}</a></li>";

            }

        }

        if ($broj_stranice < $ukupno_stranica) {

            $sljedeca_stranica = $broj_stranice + 1;

            if ($url) {$url_sljedeca_stranica = "href='{$url}/{$sljedeca_stranica}'";} else {$url_sljedeca_stranica = "";}
            if ($url) {$url_ukupno_stranica = "href='{$url}/{$ukupno_stranica}'";} else {$url_ukupno_stranica = "";}

            $kraj_link_stranice .= "<li><a class='gumb ikona' {$url_sljedeca_stranica}><svg data-boja='{$boja}'><use xlink:href=\"/kapriol/resursi/grafika/simboli/simbol.ikone.svg#strelica_desno\" /></svg></a></li>";
            $kraj_link_stranice .= "<li><a class='gumb ikona' {$url_ukupno_stranica}><svg data-boja='{$boja}'><use xlink:href=\"/kapriol/resursi/grafika/simboli/simbol.ikone.svg#strelica_desno_duplo\" /></svg></a></li>";

        }

        return array('pocetak' => $pocetak_link_stranice, 'stranice' => $link_stranice, 'kraj' => $kraj_link_stranice);

    }

}