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
     * ## Autorizacija
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
                'Artikli' => $artikli->lista($broj_stranice, $poredaj, $redoslijed)
            ]);

        } catch (Greska $greska) {

            return sadrzaj()->format(Sadrzaj_Vrsta::JSON)->podatci([
                'Validacija' => 'ne',
                'Poruka' => $greska->getMessage()
            ]);

        }

    }

}