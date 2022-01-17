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

namespace FireHub\Aplikacija\Kapriol\Model;

use FireHub\Jezgra\Model\Model;
use FireHub\Jezgra\Komponente\BazaPodataka\BazaPodataka;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;

/**
 * ### Artikl model
 * @since 0.1.2.pre-alpha.M1
 *
 * @package Aplikacija\Model
 */
final class Artikl_Model extends Model {

    /**
     * ### Konstruktor
     * @since 0.1.2.pre-alpha.M1
     */
    public function __construct (
        private BazaPodataka $bazaPodataka
    ){}

    /**
     * ### Dohvati artikl
     * @since 0.1.2.pre-alpha.M1
     *
     * @param string $link <p>
     * Link artikla.
     * </p>
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca objekta.
     *
     * @return array Artikl.
     */
    public function artikl (string $link) {

        $artikl = $this->bazaPodataka->tabela('artikliview')
            ->sirovi("
                SELECT
                    artikliview.ID, artikliview.Naziv, artikliview.Opis,
                    kategorije.Kategorija
                FROM artikliview
                LEFT JOIN kategorije ON kategorije.ID = artikliview.KategorijaID
                WHERE artikliview.Link = '$link' AND artikliview.Aktivan = 1 AND artikliview.Ba = 1
                LIMIT 1
            ")
            ->napravi();

        if (!$redak = $artikl->redak()) {

            $redak = [
                'ID' => 0,
                'Naziv' => 'Artikl ne postoji',
                'Kategorija' => 'Sve'
            ];

        }

        return $redak;

    }

    /**
     * ### Dohvati slike artikl
     * @since 0.1.2.pre-alpha.M1
     *
     * @param string|int $artiklID <p>
     * ID artikla.
     * </p>
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca objekta.
     *
     * @return array Artikl.
     */
    public function slike (string|int $artiklID) {

        $slike = $this->bazaPodataka->tabela('slikeartikal')
            ->odaberi(['Slika'])
            ->gdje('ClanakID', '=', $artiklID)
            ->poredaj('Zadana', 'ASC')->napravi();

        return $slike->niz();

    }

}