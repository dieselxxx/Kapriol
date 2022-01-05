<?php declare(strict_types = 1);

/**
 * Datoteka za poslužitelja predmemorije
 * @since 0.5.0.pre-alpha.M5
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\Predmemorija;

use FireHub\Jezgra\Komponente\Servis_Kontejner;
use FireHub\Jezgra\Komponente\Servis_Posluzitelj;
use FireHub\Jezgra\Atributi\Zadano;

/**
 * ### Poslužitelj za predmemoriju
 * @since 0.5.0.pre-alpha.M5
 *
 * @package Sustav\Jezgra
 */
final class Predmemorija extends Servis_Posluzitelj {

    //#[Zadano('predmemorija.server')]
    protected ?string $servis = null;

    /**
     * ### IP adresa ili naziv servera predmemorije
     * @var string
     */
    protected string $host;

    /**
     * {@inheritDoc}
     *
     * @return Predmemorija_Interface Objekt Predmemorije servisa.
     */
    public function napravi ():object {

        return (new Servis_Kontejner($this))->singleton();

    }

}