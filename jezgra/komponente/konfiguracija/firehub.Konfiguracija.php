<?php declare(strict_types = 1);

/**
 * Datoteka za konfiguracijske postavke sustava
 * @since 0.3.5.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Konfiguracija
 */

namespace FireHub\Jezgra\Komponente\Konfiguracija;

use FireHub\Jezgra\Komponente\Servis_Kontejner;
use FireHub\Jezgra\Komponente\Servis_Posluzitelj;

/**
 * ### Poslužitelj za konfiguracijske postavke sustava
 * @since 0.3.5.pre-alpha.M3
 *
 * @package Sustav\Konfiguracija
 */
final class Konfiguracija extends Servis_Posluzitelj {

    /**
     * {@inheritDoc}
     *
     * @return Konfiguracija_Interface Singleton objekt Konfiguracija servisa.
     */
    public function napravi ():object {

        return (new Servis_Kontejner($this))->singleton();

    }

}