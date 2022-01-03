<?php declare(strict_types = 1);

/**
 * Datoteka za atribut posrednika
 * @since 0.4.3.pre-alpha.M4
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Posrednici
 */

namespace FireHub\Jezgra\Atributi;

use Attribute;
use FireHub\Jezgra\Posrednici\Posrednik_Kontejner;
use FireHub\Jezgra\Komponente\Log\Enumeratori\Level;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;
use FireHub\Jezgra\Posrednici\Greske\Posrednik_Greska;
use Generator;

/**
 * ### Atribut za sve posrednike
 * @since 0.4.3.pre-alpha.M4
 *
 * @package Sustav\Posrednici
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class Posrednici implements Atribut {

    /**
     * ### Konstruktor
     * @since 0.4.3.pre-alpha.M4
     *
     * @param string[] $posrednici <p>
     * Lista naziva posrednika.
     * </p>
     */
    public function __construct (
        public array $posrednici
    ) {}

    /**
     * @inheritDoc
     */
    public function obradi ():bool {

        // pokeni posrednike
        $generator = $this->obradiPosrednike();

        // obradi posrednike
        while ($generator->valid()) {

            $generator->next();

        }

        return true;

    }

    /**
     * ### Obradi sve posrednike
     *
     * Pokreće svim posrednicima metodu {obradi} koja
     * vraća stanje posrednika. Posrednici se učitavaju redosljedom iz
     * konfiguracijske datoteke posrednika, te prvi koji vrati stanje false
     * zaustavlja se daljnje obrađivanje posrednika.
     * @since 0.4.3.pre-alpha.M4
     *
     * @throws Posrednik_Greska Ukoliko ne mogu obraditi posrednika.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca posrednika ili Log-a..
     *
     * @return Generator Pokreće posrednika.
     */
    private function obradiPosrednike ():Generator {

        foreach ($this->posrednici as $posrednik) {

            // napravi posrednika
            $obradi_posrednika = (new Posrednik_Kontejner($posrednik))->dohvati()->obradi();

            if ($obradi_posrednika === false) {

                zapisnik(Level::KRITICNO, sprintf(_('Ne mogu obraditi posrednika %s!'), $posrednik));
                throw new Posrednik_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru.'));

            }

            yield $obradi_posrednika;

        }

    }

}