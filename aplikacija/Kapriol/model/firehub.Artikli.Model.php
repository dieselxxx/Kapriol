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
            ->odaberi(['Naziv', 'Link', 'Opis', 'Cijena', 'CijenaAkcija'])
            ->gdje('KategorijaID', '=', $kategorija)
            ->gdje('Aktivan' , '=', 1)
            ->gdje('Ba', '=', 1)
            ->limit($pomak, $limit)
            ->poredaj('Naziv', 'ASC')
            ->napravi();

        return $artikli->niz() ?: [];

    }

}