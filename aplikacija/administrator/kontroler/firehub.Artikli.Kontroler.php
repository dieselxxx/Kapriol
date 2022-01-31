<?php declare(strict_types = 1);

/**
 * Artikli
 * @since 0.1.2.pre-alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2022 Kapriol Web Trgovina
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Aplikacija\Kontroler
 */

namespace FireHub\Aplikacija\Administrator\Kontroler;

use FireHub\Aplikacija\Administrator\Model\Artikl_Model;
use FireHub\Aplikacija\Administrator\Model\Kategorije_Model;
use FireHub\Jezgra\Sadrzaj\Sadrzaj;
use FireHub\Aplikacija\Administrator\Model\Artikli_Model;
use FireHub\Jezgra\HTTP\Enumeratori\Vrsta;
use FireHub\Jezgra\Sadrzaj\Enumeratori\Vrsta as Sadrzaj_Vrsta;
use FireHub\Jezgra\HTTP\Atributi\Zaglavlja;
use FireHub\Jezgra\Greske\Greska;

/**
 * ### Artikli
 * @since 0.1.2.pre-alpha.M1
 *
 * @package Aplikacija\Kontroler
 */
final class Artikli_Kontroler extends Master_Kontroler {

    /**
     * ## index
     * @since 0.1.2.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function index ():Sadrzaj {

        return sadrzaj()->datoteka('artikli/lista.html')->podatci([]);

    }

    /**
     * ## Lista artikala
     * @since 0.1.2.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    #[Zaglavlja(vrsta: Vrsta::JSON)]
    public function lista (string $kontroler = '', string $metoda = '', int $broj_stranice = 1, string $poredaj = 'Naziv', string $redoslijed = 'asc'):Sadrzaj {

        try {

            // model
            $artikli = $this->model(Artikli_Model::class);

            return sadrzaj()->format(Sadrzaj_Vrsta::JSON)->podatci([
                'Validacija' => 'da',
                'Artikli' => $artikli->lista($broj_stranice, $poredaj, $redoslijed),
                'Zaglavlje' => $artikli->IspisiZaglavlje(),
                'Navigacija' => $artikli->IspisiNavigaciju()
            ]);

        } catch (Greska $greska) {

            return sadrzaj()->format(Sadrzaj_Vrsta::JSON)->podatci([
                'Validacija' => 'ne',
                'Poruka' => $greska->getMessage()
            ]);

        }

    }

    /**
     * ## Uredi artikl
     * @since 0.1.2.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function uredi (string $kontroler = '', string $metoda = '', int $id = 0) {

        $artikl_model = $this->model(Artikl_Model::class);
        $artikl = $artikl_model->artikl($id);

        // formatiranje rezultata
        if ($artikl['Izdvojeno'] === true) {$artikl['Izdvojeno'] = 'checked';} else {$artikl['Izdvojeno'] = '';}
        if ($artikl['Aktivan'] === true) {$artikl['Aktivan'] = 'checked';} else {$artikl['Aktivan'] = '';}

        // kategorije
        $kategorije_model = $this->model(Kategorije_Model::class);
        $kategorije = $kategorije_model->lista(limit_zapisa_po_stranici: 100);

        $kategorije_html = '';
        foreach ($kategorije as $kategorija) {

            $kategorije_html .= "<option value='{$kategorija['ID']}'>{$kategorija['Kategorija']}</option>";

        }

        return sadrzaj()->predlozakPutanja('prazno/')->datoteka('artikli/uredi.html')->podatci([
            'id' => $artikl['ID'],
            'naziv' => $artikl['Naziv'],
            'opis' => $artikl['Opis'],
            'cijena' => $artikl['Cijena'],
            'cijena_akcija' => $artikl['CijenaAkcija'],
            'cijena_hr' => $artikl['CijenaKn'],
            'cijena_akcija_hr' => $artikl['CijenaAkcijaKn'],
            'aktivno' => $artikl['Aktivan'],
            'izdvojeno' => $artikl['Izdvojeno'],
            'kategorija' => $artikl['KategorijaID'],
            'kategorija_naziv' => $artikl['Kategorija'],
            'kategorije' => $kategorije_html
        ]);

    }

    /**
     * ### Spremi artikl
     * @since 0.1.2.pre-alpha.M1
     *
     * @return Sadrzaj
     */
    #[Zaglavlja(vrsta: Vrsta::JSON)]
    public function spremi (string $kontroler = '', string $metoda = '', int $id = 0):Sadrzaj {

        try {

            // model
            $artikl = $this->model(Artikl_Model::class);
            $artikl->spremi($id);

            return sadrzaj()->format(Sadrzaj_Vrsta::JSON)->podatci([
                'Validacija' => 'da',
                'Poruka' => _('Postavke spremljene')
            ]);

        } catch (Greska $greska) {

            return sadrzaj()->format(Sadrzaj_Vrsta::JSON)->podatci([
                'Validacija' => 'ne',
                'Poruka' => $greska->getMessage()
            ]);

        }

    }

}