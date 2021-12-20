<?php declare(strict_types = 1);

/**
 * Osnovna datoteka za sve poslužitelje servisa
 * @since 0.3.0.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente;

use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;
use FireHub\Jezgra\Kontejner\Greske\Servis_Posluzitelj_Greska;

/**
 * ### Osnovna abstraktna klasa za sve poslužitelje servisa
 * @since 0.3.0.pre-alpha.M3
 *
 * @package Sustav\Jezgra
 */
abstract class Servis_Posluzitelj {

    /**
     * ### Ručno postavljeni servis na poslužitelju
     * @var ?string $servis
     */
    protected ?string $servis = null;

    /**
     * ### Postavi servis na poslužitelju
     * @since 0.3.0.pre-alpha.M3
     *
     * @param $servis <p>
     * Ručno postavljeni servis na poslužitelju
     * </p>
     *
     * @return $this Trenutni objekt.
     */
    public function servis ($servis):self {

        $this->servis = $servis;

        return $this;

    }

    /**
     * ### Pročitaj ručno postavljeni servis na poslužitelju
     *
     * Ručno postavljeni servis ukoliko je napisan prilikom
     * pozivanja poslužitelja.
     * @since 0.3.0.pre-alpha.M3
     *
     * @return ?string FQN naziv servisa.
     */
    public function postavljeniServis ():?string {

        return $this->servis;

    }

    /**
     * ### Napravi servis
     * @since 0.3.0.pre-alpha.M3
     *
     * @throws Kontejner_Greska Ako ne postoji objekt sa nazivom klase ili ukoliko nije uspješno obrađen atribut.
     *
     * @return Servis_Interface Objekt servisa.
     */
    public function napravi ():object {

        return (new Servis_Kontejner($this))->dohvati();

    }

    /**
     * ### Automatsko postavljanje statičkih metoda servisa
     * @since 0.3.0.pre-alpha.M3
     *
     * @param string $metoda <p>
     * Naziv metode.
     * </p>
     * @param array $argumenti <p>
     * Argumenti metode.
     * </p>
     *
     * @throws Servis_Posluzitelj_Greska Ukoliko ne postoji svojstvo u poslužitelju.
     *
     * @return mixed Vrijednost iz statičke metode servisa.
     */
    public function __call (string $metoda, array $argumenti):self {

        // ako ne postoji svojstvo
        if (!property_exists($this, $metoda)) {

            throw new Servis_Posluzitelj_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru'));

        }

        array_walk(
            $argumenti,
            function ($argument) use ($metoda):mixed {
                return $this->$metoda = $argument;
            }
        );

        return $this;

    }

    /**
     * ### Pročitaj svojstvo poslužitelja
     *
     * Daje servisima mogućnost da čitaju protected atribute svojih poslužitelja.
     * @since 0.3.0.pre-alpha.M3
     *
     * @param string $svojstvo_naziv <p>
     * Naziv svojstva.
     * </p>
     *
     * @return mixed Vrijednost svojstva.
     */
    public function &__get (string $svojstvo_naziv):mixed {

        return $this->$svojstvo_naziv;

    }

}