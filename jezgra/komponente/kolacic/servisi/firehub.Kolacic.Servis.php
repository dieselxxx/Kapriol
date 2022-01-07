<?php declare(strict_types = 1);

/**
 * Datoteka za servis kolačića
 * @since 0.5.2.pre-alpha.M5
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\Kolacic\Servisi;

use FireHub\Jezgra\Komponente\Kolacic\Kolacic;
use FireHub\Jezgra\Komponente\Kolacic\Kolacic_Interface;

/**
 * ### Servis za upravljanje kolačićima
 * @since 0.5.2.pre-alpha.M5
 *
 * @package Sustav\Jezgra
 */
final class Kolacic_Servis implements Kolacic_Interface {

    /**
     * ### Kontruktor
     * @since 0.5.2.pre-alpha.M5
     *
     * @param Kolacic $posluzitelj <p>
     * Poslužitelj servisa.
     * </p>
     */
    public function __construct (
        private Kolacic $posluzitelj
    ) {}
    /**
     * @inheritDoc
     */
    public function spremi ():bool {

        return setcookie(
            $this->posluzitelj->naziv,
            $this->posluzitelj->vrijednost,
            [
                'expires' => time() + $this->posluzitelj->vrijeme,
                'path' => $this->posluzitelj->putanja,
                'domain' => $this->posluzitelj->domena,
                'secure' => $this->posluzitelj->ssl,
                'httponly' => $this->posluzitelj->http,
                'samesite' => $this->posluzitelj->ista_stranica
            ]
        );

    }

    /**
     * @inheritDoc
     */
    public function procitaj ($naziv):string|false {

        if (!isset($_COOKIE[$naziv])) {

            return false;

        }

        return $_COOKIE[$naziv];

    }

    /**
     * @inheritDoc
     */
    public function izbrisi ($naziv):bool {

        if (!isset($_COOKIE[$naziv])) {

            return false;

        }

        return setcookie($naziv, '', time() - 3600, '/');

    }

}