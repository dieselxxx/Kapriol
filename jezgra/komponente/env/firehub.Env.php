<?php declare(strict_types = 1);

/**
 * Datoteka za čitanje iz .env datoteka
 * @since 0.3.3.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\Env;

use FireHub\Jezgra\Komponente\Servis_Kontejner;
use FireHub\Jezgra\Komponente\Servis_Posluzitelj;

/**
 * ### Poslužitelj za čitanje iz .env datoteka
 * @since 0.3.3.pre-alpha.M3
 *
 * @property-read array $datoteka Lista punih putanja .env datoteke
 *
 * @method static string|int|bool|null procitaj (string $env, string|int|bool|null $zadano = null) Pročitaj env zapis
 *
 * @package Sustav\Jezgra
 */
final class Env extends Servis_Posluzitelj {

    /**
     * ### Lista punih putanja .env datoteke
     * @var string[]
     */
    protected array $datoteka = [];

    /**
     * ### Dodaj .env datoteku
     * @since 0.3.3.pre-alpha.M3
     *
     * @param string $datoteka <p>
     * Puna putanja .env datoteke.
     * </p>
     *
     * @return $this Trenutni objekt.
     */
    public function datoteka (string $datoteka):self {

        $this->datoteka[] = $datoteka;

        return $this;

    }

    /**
     * {@inheritDoc}
     *
     * @return Env_Interface Objekt Env servisa.
     */
    public function napravi ():object {

        return (new Servis_Kontejner($this))->dohvati();

    }

}