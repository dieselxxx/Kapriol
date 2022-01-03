<?php declare(strict_types = 1);

/**
 * Datoteka za upravljanje ovisnostima definicija i dependency injection za kontrolere
 *
 * Kontroler Kontejner je klasa namjenjena za upravljanje ovisnostima definicijama i
 * dependency injection za posrednike.
 * @since 0.4.2.pre-alpha.M4
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Kontroler;

use FireHub\Jezgra\Kontejner\Kontejner;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;

/**
 * ### Upravljanje ovisnostima definicija i dependency injection za kontrolere
 * @since 0.4.2.pre-alpha.M4
 *
 * @package Sustav\Jezgra
 */
final class Kontroler_Kontejner extends Kontejner {

    /**
     * {@inheritDoc}
     *
     * @throws Kontejner_Greska Ako ne postoji objekt sa nazivom klase.
     */
    protected function parameteri ():array {

        return $this->autozicaObjekt();

    }

}