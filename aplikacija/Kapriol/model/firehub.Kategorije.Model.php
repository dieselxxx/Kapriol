<?php declare(strict_types = 1);

/**
 * Kategorije model
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
 * ### Kategorije model
 * @since 0.1.1.pre-alpha.M1
 *
 * @package Aplikacija\Model
 */
final class Kategorije_Model extends Model {

    /**
     * ### Konstruktor
     * @since 0.1.1.pre-alpha.M1
     */
    public function __construct (
        private BazaPodataka $bazaPodataka
    ) {}

    /**
     * ### Sve kategorije za glavni meni
     * @since 0.1.1.pre-alpha.M1
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca objekta.
     *
     * @return string HTML za glavni meni.
     */
    public function glavni_meni ():string {

        $kategorije = $this->bazaPodataka->tabela('kategorijeview')
            ->odaberi(['Kategorija', 'Link', 'Ikona'])
            ->gdje('Meni' , '=', 1)
            ->poredaj('Prioritet', 'ASC')->napravi();

        $rezultat = '';
        foreach ($kategorije->niz() as $kategorija) {

            $rezultat .= '
                <li>
                    <svg><use xlink:href="/kapriol/resursi/grafika/simboli/simbol.ikone.svg#'.$kategorija['Ikona'].'"></use></svg>
                    <span><a href="/rezultat/'.$kategorija['Link'].'">'.$kategorija['Kategorija'].'</a></span>
                </li>
            ';

        }

        return $rezultat;

    }

    /**
     * ### Dohvati kategoriju
     * @since 0.1.1.pre-alpha.M1
     *
     * @param string $kategorija <p>
     * Naziv kategorije.
     * </p>
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca objekta.
     *
     * @return array Kategorija.
     */
    public function kategorija (string $kategorija):array {

        $id = $this->bazaPodataka->tabela('kategorijeview')
            ->odaberi(['ID', 'Kategorija'])
            ->gdje('Link', '=', $kategorija)
            ->napravi();

        if (!$redak = $id->redak()) {

            $redak = [
                'ID' => 0,
                'Kategorija' => 'Kategorija ne postoji'
            ];

        }

        return $redak;

    }

}