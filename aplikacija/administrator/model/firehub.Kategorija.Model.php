<?php declare(strict_types = 1);

/**
 * Kategorija model
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
 * ### Kategorija
 * @since 0.1.2.pre-alpha.M1
 *
 * @package Aplikacija\Model
 */
final class Kategorija_Model extends Master_Model {

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
     * ### Kateogrija
     * @since 0.1.2.pre-alpha.M1
     *
     * @param int $id
     *
     * @throws Kontejner_Greska
     * @return array|false|mixed[]
     */
    public function kategorija (int $id):array|false {

        $kategorija = $this->bazaPodataka
            ->sirovi("
                SELECT
                    kategorije.ID, kategorije.Kategorija, kategorije.Slika, kategorije.CalcVelicina, kategorije.Prioritet
                FROM kategorije
                WHERE kategorije.ID = $id
                LIMIT 1
            ")
            ->napravi();

        $kategorija = $kategorija->redak();

        if ($kategorija['CalcVelicina']) {$kategorija['CalcVelicina'] = true;} else {$kategorija['CalcVelicina'] = false;}

        return $kategorija;

    }
