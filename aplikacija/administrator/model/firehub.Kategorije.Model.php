<?php declare(strict_types = 1);

/**
 * Kategorije model
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
use FireHub\Aplikacija\Administrator\Jezgra\tTablicaEfekti;
use FireHub\Jezgra\Greske\Greska;

/**
 * ### Kategorije
 * @since 0.1.2.pre-alpha.M1
 *
 * @package Aplikacija\Model
 */
final class Kategorije_Model extends Master_Model {

    use tTablicaEfekti;

    private int $broj_stranice;
    protected string $poredaj;
    protected string $redoslijed;
    private string $pretraga;
    public int $limit_zapisa_po_stranici;

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
     * Lista kategorija.
     *
     * @param int $broj_stranice
     * @param string $poredaj
     * @param string $redoslijed
     *
     * @throws Greska
     *
     * @return array
     */
    public function lista (int $broj_stranice = 1, string $poredaj = 'Kategorija', string $redoslijed = 'asc', int $limit_zapisa_po_stranici = 10):array {

        if (isset($_REQUEST['pretraga']) && $_REQUEST['pretraga'] <> '') {

            $pretraga = $_REQUEST['pretraga'];

        } else {

            $pretraga = '';

        }

        $pretraga = Validacija::String(_('Pretraga'), $pretraga, 0, 50);
        $broj_stranice = Validacija::Broj(_('Broj stranice'), $broj_stranice, 1, 10);

        $this->broj_stranice = $broj_stranice;

        $this->pretraga = $pretraga;

        $this->poredaj = $poredaj;
        $this->poredaj = Validacija::Slova(_('Poredaj rezutate'), $this->poredaj, 1);

        $this->redoslijed = $redoslijed;
        $this->redoslijed = Validacija::Slova(_('Redoslijed rezultata'), $this->redoslijed, 3, 4);
        if ($this->redoslijed <> 'asc' && $this->redoslijed <> 'desc') {throw new Greska(_('Redoslijed rezultata ima pogrešnu vrijednost'));}

        $this->limit_zapisa_po_stranici = $limit_zapisa_po_stranici;

        // potraži kategorije
        $pomak = ($broj_stranice - 1) * $this->limit_zapisa_po_stranici;

        $rezultat = $this->bazaPodataka
            ->sirovi("
                SELECT
                    ID, Kategorija
                FROM kategorije
                WHERE ID <> 0
                {$this->trazi()}
                ORDER BY ".ucwords($poredaj)." $redoslijed
                LIMIT $pomak, $this->limit_zapisa_po_stranici
            ")
            ->napravi();

        return $rezultat->niz() ?: [];

    }

    /**
     * ### Traži kategoriju
     * @since 0.1.2.pre-alpha.M1
     *
     * @return string Upit za traženje.
     */
    private function trazi ():string {

        if (isset($_POST['pretraga'])) {

            $trazi = explode(' ', (string)$_POST['pretraga']);

            $trazi_array = '';
            foreach ($trazi as $stavka) {

                $trazi_array .= "
                    AND (
                        Kategorija LIKE '%{$stavka}%'
                    )
                ";

            }

            return $trazi_array;

        }

        return '';

    }

}