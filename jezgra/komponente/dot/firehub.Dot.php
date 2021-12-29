<?php declare(strict_types = 1);

/**
 * Datoteka za čitanje dot zapisa
 * @since 0.3.2.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\Dot;

use FireHub\Jezgra\Komponente\Servis_Kontejner;
use FireHub\Jezgra\Komponente\Servis_Posluzitelj;

/**
 * ### Poslužitelj za čitanje dot zapisa
 * @since 0.3.2.pre-alpha.M3
 *
 * @property-read array $niz Niz za čitanje dot zapisa
 *
 * @method $this niz(array $niz) Postavi niz za čitanje dot zapisa
 *
 * @package Sustav\Jezgra
 */
final class Dot extends Servis_Posluzitelj {

    /**
     * ### Niz za čitanje dot zapisa
     * @var array<string, mixed>
     */
    protected array $niz = [];

    /**
     * {@inheritDoc}
     *
     * @return Dot_Interface Objekt Dot servisa.
     */
    public function napravi ():object {

        return (new Servis_Kontejner($this))->dohvati();

    }

}