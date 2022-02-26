<?php declare(strict_types = 1);

/**
 * Obavijest model
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
use FireHub\Jezgra\Komponente\BazaPodataka\BazaPodataka;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;

/**
 * ### Obavijest
 * @since 0.1.2.pre-alpha.M1
 *
 * @package Aplikacija\Model
 */
final class Obavijest_Model extends Master_Model {

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
     * ### Obavijest
     * @since 0.1.2.pre-alpha.M1
     *
     * @param int $id
     *
     * @throws Kontejner_Greska
     * @return array|false|mixed[]
     */
    public function obavijest (int $id):array|false {

        $obavijest = $this->bazaPodataka
            ->sirovi("
                SELECT
                    obavijesti.ID, obavijesti.Obavijest
                FROM obavijesti
                WHERE obavijesti.ID = $id
                LIMIT 1
            ")
            ->napravi();

        return $obavijest->redak();

    }

    /**
     * ### Izbrisi
     * @since 0.1.2.pre-alpha.M1
     *
     * @param int $id
     */
    public function izbrisi (int $id) {

        $obavijest = $this->bazaPodataka
            ->sirovi("
                SELECT
                    obavijesti.ID, obavijesti.Obavijest
                FROM obavijesti
                WHERE obavijesti.ID = $id
                LIMIT 1
            ")
            ->napravi();

        $izbrisi = $this->bazaPodataka
            ->sirovi("
                DELETE
                FROM obavijesti
                WHERE obavijesti.ID = $id
                LIMIT 1
            ")
            ->napravi();

        unlink(FIREHUB_ROOT.'web/kapriol/resursi/grafika/baneri/'.$obavijest->redak()['Obavijest']);

        return 'ok';

    }

    /**
     * ### Dodaj
     * @since 0.1.2.pre-alpha.M1
     */
    public function dodaj (string $naziv_datoteke) {

        // prenesi sliku
        $datoteka = new PrijenosDatoteka($naziv_datoteke);
        $datoteka->Putanja(FIREHUB_ROOT.konfiguracija('sustav.putanje.web').'kapriol'.RAZDJELNIK_MAPE.'resursi'.RAZDJELNIK_MAPE.'grafika'.RAZDJELNIK_MAPE.'baneri'.RAZDJELNIK_MAPE);
        $datoteka->NovoIme($naziv_datoteke, true);
        $datoteka->DozvoljeneVrste(array('image/jpeg'));
        $datoteka->DozvoljenaVelicina(5000);
        $datoteka->PrijenosDatoteke();
        $datoteka->SlikaDimenzije(2000, 1000);

        $obavijest = $this->bazaPodataka
            ->sirovi("
                INSERT INTO obavijesti (Obavijest) VALUES('{$datoteka->ImeDatoteke()}')
            ")
            ->napravi();

    }

}