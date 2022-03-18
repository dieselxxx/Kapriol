<?php declare(strict_types = 1);

/**
 * PodKategorija model
 * @since 0.1.2.pre-alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2022 Kapriol Web Trgovina
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Aplikacija\Model
 */

namespace FireHub\Aplikacija\Administrator\Model;

use FireHub\Aplikacija\Administrator\Jezgra\PrijenosDatoteka;
use FireHub\Aplikacija\Kapriol\Jezgra\Validacija;
use FireHub\Jezgra\Greske\Greska;
use FireHub\Jezgra\Komponente\BazaPodataka\BazaPodataka;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;

/**
 * ### PodKategorija
 * @since 0.1.2.pre-alpha.M1
 *
 * @package Aplikacija\Model
 */
final class PodKategorija_Model extends Master_Model {

    /**
     * ### Konstruktor
     * @since 0.1.2.pre-alpha.M1
     */
    public function __construct (
        private BazaPodataka $bazaPodataka
    ){

        parent::__construct();

    }

    /**
     * ### PodKategorija
     * @since 0.1.2.pre-alpha.M1
     *
     * @param int $id
     *
     * @throws Kontejner_Greska
     * @return array|false|mixed[]
     */
    public function podkategorija (int $id):array|false {

        $kategorija = $this->bazaPodataka
            ->sirovi("
                SELECT
                    podkategorije.ID, podkategorije.PodKategorija, kategorije.Kategorija
                FROM podkategorije
                LEFT JOIN kategorije ON kategorije.ID = podkategorije.KategorijaID
                WHERE podkategorije.ID = $id
                LIMIT 1
            ")
            ->napravi();

        $kategorija = $kategorija->redak();

        return $kategorija;

    }

    /**
     * ### Spremi podkategoriju
     * @since 0.1.2.pre-alpha.M1
     */
    public function spremi (int $id) {

        $id = Validacija::Broj(_('ID podkategorije'), $id, 1, 10);

        $naziv = $_REQUEST['naziv'];
        $naziv = Validacija::String(_('Naziv podkategorije'), $naziv, 3, 250);

        if ($id !== 0) {

            $kategorija = $this->bazaPodataka
                ->sirovi("
                UPDATE podkategorije
                    SET PodKategorija = '$naziv'
                WHERE podkategorije.ID = $id
            ")
                ->napravi();

        } else {

            $kategorija = $_REQUEST['kategorija'];
            $kategorija = Validacija::Broj(_('Kategorija'), $kategorija, 1, 7);

            $kategorija = $this->bazaPodataka
                ->sirovi("
                INSERT INTO podkategorije (PodKategorija, KategorijaID) VALUES ('$naziv', '$kategorija')
            ")
                ->napravi();

        }

    }

    /**
     * ### Izbrisi podkategoriju
     * @since 0.1.2.pre-alpha.M1
     */
    public function izbrisi (int $id) {

        $id = Validacija::Broj(_('ID podkategorije'), $id, 1, 10);

        $broj = $this->bazaPodataka
            ->sirovi("
                SELECT *
                FROM artikli
                WHERE PodKategorijaID = $id
            ")
            ->napravi();

        if ($broj->broj_zapisa() > 0) {

            throw new Greska('Ne možete izbrisati podkategoriju jer imate artikala u njoj!');

        }

        $kategorija = $this->bazaPodataka
            ->sirovi("
                DELETE FROM podkategorije
                WHERE podkategorije.ID = $id
            ")
            ->napravi();

    }

}