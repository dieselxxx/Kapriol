<?php declare(strict_types=1);

/**
 * Datoteka za dostavljača za ispisivanje logova
 * @since 0.3.1.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\Log\Servisi\Dostavljac;

use FireHub\Jezgra\Komponente\Log\Servisi\Dostavljac;

/**
 * ### Klasa za dostavljača za ispisivanje logova
 * @since 0.3.1.pre-alpha.M3
 *
 * @package Sustav\Jezgra
 */
final class Ispisi implements Dostavljac {

    /**
     * @inheritDoc
     */
    public function otvori ():self {

        return $this;

    }

    /**
     * @inheritDoc
     */
    public function zapisi (string $vrsta_objekta, int $level, string $level_naziv, int $kod, string $datoteka, int $linija, string $poruka, array $debug):self {

        echo <<<GRESKA
            Vrsta objekta: $vrsta_objekta<br>
            Level: $level<br>
            NazivLevela: $level_naziv<br>
            Kod: $kod<br>
            Datoteka: $datoteka<br>
            Linija: $linija<br>
            Poruka: $poruka<br><br><br>
        GRESKA;

        return $this;

    }

    /**
     * @inheritDoc
     */
    public function zatvori ():self {

        return $this;

    }

}