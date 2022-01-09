<?php declare(strict_types = 1);

/**
 * Datoteka za glavni kontroler kojeg svi ostali kontroleri trebaju nastaviti
 * @since 0.4.2.pre-alpha.M4
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Kontroler
 */

namespace FireHub\Jezgra\Kontroler;

use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;
use FireHub\Jezgra\Sadrzaj\Sadrzaj;
use FireHub\Jezgra\Model\Model;
use FireHub\Jezgra\Model\Model_Kontejner;
use FireHub\Jezgra\Komponente\Log\Enumeratori\Level;
use FireHub\Jezgra\Kontroler\Greske\Kontroler_Greska;

/**
 * ### Abstrakt klasa za glavni kontroler
 * @since 0.4.2.pre-alpha.M4
 *
 * @package Sustav\Kontroler
 */
abstract class Kontroler {

    /**
     * ### Index metoda
     *
     * Nužna metoda index koja služi za zadanu metodu
     * ukoliko URL ne sadrži parametre za nju.
     * @since 0.4.2.pre-alpha.M4
     *
     * @return Sadrzaj Instanca Sadrzaj-a.
     */
    abstract public function index ():Sadrzaj;

    /**
     * ### Pozovi model
     *
     * Pozivanje modela preko DI model kontejnera.
     * @since 0.4.2.pre-alpha.M4
     *
     * @param string $naziv <p>
     * FQN naziv modela.
     * </p>
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     * @throws Kontroler_Greska Ukoliko objekt nije validan model.
     *
     * @return Model Instanca Model-a.
     */
    final protected function model (string $naziv):Model {

        // model
        $model = (new Model_Kontejner($naziv))->dohvati();

        // model mora biti instanca abstraktnog kontrolera
        if (!$model instanceof Model) {

            zapisnik(Level::KRITICNO, sprintf(_('Objekt: "%s" nije validan model!'), $model::class));
            throw new Kontroler_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru.'));

        }

        return $model;

    }

}