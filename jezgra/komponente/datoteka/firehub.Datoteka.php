<?php declare(strict_types = 1);

/**
 * Datoteka za čitanje podataka iz ostalih datoteka
 * @since 0.3.4.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\Datoteka;

use FireHub\Jezgra\Komponente\Servis_Kontejner;
use FireHub\Jezgra\Komponente\Servis_Posluzitelj;

/**
 * ### Poslužitelj za čitanje podataka iz ostalih datoteka
 * @since 0.3.4.pre-alpha.M3
 *
 * @property-read string $datoteka Puna putanja datoteke koje se učitava
 *
 * @method $this datoteka(string $datoteka) Učitaj datoteku
 *
 * @package Sustav\Jezgra
 */
final class Datoteka extends Servis_Posluzitelj {

    /**
     * ### Puna putanja datoteke koje se učitava
     * @var string
     */
    protected string $datoteka;

    /**
     * {@inheritDoc}
     *
     * @return Datoteka_Interface Objekt Datoteka servisa.
     */
    public function napravi ():object {

        return (new Servis_Kontejner($this))->dohvati();

    }

}