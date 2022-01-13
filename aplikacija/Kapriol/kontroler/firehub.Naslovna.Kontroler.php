<?php declare(strict_types = 1);

/**
 * Naslovna
 * @since 0.1.0.pre-alpha.M1
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
use FireHub\Jezgra\Sadrzaj\Sadrzaj;
use FireHub\Jezgra\Komponente\BazaPodataka\BazaPodataka;

/**
 * ### Naslovna
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Kontroler
 */
final class Naslovna_Kontroler extends Kontroler {

    /**
     * ## index
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function index (BazaPodataka $bazaPodataka = null):Sadrzaj {

        $artikli = $bazaPodataka->tabela('kategorije')->odaberi(['kategorija'])->poredaj('ID', 'ASC')->napravi();

        return sadrzaj()->datoteka('naslovna.html')->podatci([
            'predlozak_naslov' => 'Naslovna',
            'prvi_podatak' => 'naslovna-index',
            'kategorije' => serialize($artikli->niz())
        ]);

    }

}