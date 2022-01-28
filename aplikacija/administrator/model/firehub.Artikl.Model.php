<?php declare(strict_types = 1);

/**
 * Artikl model
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

use FireHub\Jezgra\Komponente\BazaPodataka\BazaPodataka;
use FireHub\Aplikacija\Kapriol\Jezgra\Validacija;
use FireHub\Jezgra\Greske\Greska;

/**
 * ### Artikl
 * @since 0.1.2.pre-alpha.M1
 *
 * @package Aplikacija\Model
 */
final class Artikl_Model extends Master_Model {

    /**
     * ### Konstruktor
     * @since 0.1.2.pre-alpha.M1
     */
    public function __construct (
        private BazaPodataka $bazaPodataka
    ){

        parent::__construct();

    }

    public function artikl (int $artikal) {

        $artikl = $this->bazaPodataka
            ->sirovi("
                SELECT
                    artikli.ID, artikli.Naziv, artikli.Opis,
                    artikli.Cijena, artikli.CijenaAkcija, artikli.CijenaKn, artikli.CijenaAkcijaKn,
                    artikli.Ba, artikli.Hr,
                    artikli.Aktivan, artikli.Izdvojeno
                FROM artikli
                WHERE artikli.ID = $artikal
                LIMIT 1
            ")
            ->napravi();

        $artikl = $artikl->redak();

        $artikl['Cijena'] = number_format((float)$artikl['Cijena'], 2, ',', '.');
        $artikl['CijenaAkcija'] = number_format((float)$artikl['CijenaAkcija'], 2, ',', '.');
        $artikl['CijenaKn'] = number_format((float)$artikl['CijenaKn'], 2, ',', '.');
        $artikl['CijenaAkcijaKn'] = number_format((float)$artikl['CijenaAkcijaKn'], 2, ',', '.');
        if ($artikl['Izdvojeno']) {$artikl['Izdvojeno'] = true;} else {$artikl['Izdvojeno'] = false;}
        if ($artikl['Aktivan']) {$artikl['Aktivan'] = true;} else {$artikl['Aktivan'] = false;}

        return $artikl;

    }

}