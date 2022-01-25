<?php declare(strict_types = 1);

/**
 * Master
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

use FireHub\Jezgra\Kontroler\Kontroler;
use FireHub\Aplikacija\Kapriol\Model\Kosarica_Model;
use FireHub\Aplikacija\Kapriol\Jezgra\Validacija;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;
use FireHub\Jezgra\Kontroler\Greske\Kontroler_Greska;

/**
 * ### Master
 * @since 0.1.2.pre-alpha.M1
 *
 * @package Aplikacija\Kontroler
 */
abstract class Master_Kontroler extends Kontroler {

    /**
     * ### Konstruktor
     * @since 0.1.2.pre-alpha.M1
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Baze podataka Log-a.
     * @throws Kontroler_Greska Ukoliko objekt nije validan model.
     */
    public function __construct () {

        if (isset($_POST['kosarica_izmijeni'])) {

            if (isset($_POST['velicina'])) {

                $velicina =  Validacija::String('Veličina', $_POST['velicina'], 1, 10);

                $this->model(Kosarica_Model::class)->izmijeni($velicina, (int)$_POST['vrijednost'] ?? 0);

            }

        }

        if (isset($_POST['kosarica_izbrisi'])) {

            if (isset($_POST['velicina'])) {

                $velicina =  Validacija::String('Veličina', $_POST['velicina'], 1, 10);

                $this->model(Kosarica_Model::class)->izbrisi($velicina);

            }

        }

    }

    /**
     * ### Broj komada u košarici
     * @since 0.1.2.pre-alpha.M1
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     * @throws Kontroler_Greska Ukoliko objekt nije validan model.
     *
     * @return string Broj komada u košarici.
     */
    protected function kosaricaArtikli ():string {

        $kosarica_model = $this->model(Kosarica_Model::class);

        // artikli
        $kosarica_artikli = $kosarica_model->artikli();
        $total_kolicina = 0;
        $total_cijena = 0;
        if (!empty($kosarica_artikli)) {

            foreach ($kosarica_artikli as $artikal) {

                // ukupno
                $total_kolicina += $artikal['Kolicina'];
                $total_cijena += $artikal['CijenaUkupno'];

            }

            return ''.$total_kolicina.' kom';

        } else {

            return '0';

        }

    }

}