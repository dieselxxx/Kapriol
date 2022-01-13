<?php declare(strict_types = 1);

/**
 * Kateorije
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
 * ### Kateorije
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
     * ### Sve kateorije za glavni meni
     * @since 0.1.1.pre-alpha.M1
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca objekta.
     *
     * @return string
     */
    public function glavni_meni ():string {

        $kategorije = $this->bazaPodataka->tabela('kategorije')->odaberi(['Kategorija', 'Ikona'])->gdje('Meni' , '=', 1)->poredaj('Prioritet', 'ASC')->napravi();

        $rezultat = '';
        foreach ($kategorije->niz() as $kategorija) {

            $kategorija['Kategorija'] = strtolower($kategorija['Kategorija']);

            $kategorija['Kategorija'] = str_replace( '/', '_', $kategorija['Kategorija']);

            $rezultat .= '
                <li>
                    <svg><use xlink:href="/kapriol/resursi/grafika/simboli/simbol.ikone.svg#'.$kategorija['Ikona'].'"></use></svg>
                    <span><a href="/rezultat/'.$kategorija['Kategorija'].'">'.$kategorija['Kategorija'].'</a></span>
                </li>
            ';

        }

        return $rezultat;

    }

    public function kategorija () {

    }

}