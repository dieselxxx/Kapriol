<?php declare(strict_types = 1);

/**
 * Datoteka za servis sesije
 * @since 0.5.3.pre-alpha.M5
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\Sesija\Servisi;

use FireHub\Jezgra\Komponente\Sesija\Sesija;
use FireHub\Jezgra\Komponente\Sesija\Sesija_Interface;
use FireHub\Jezgra\Komponente\Log\Enumeratori\Level;
use FireHub\Jezgra\Komponente\Sesija\Greske\Sesija_Greska;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;

/**
 * ### Servis za upravljanje sesijama
 * @since 0.5.3.pre-alpha.M5
 *
 * @package Sustav\Jezgra
 */
final class Datoteka implements Sesija_Interface {

    /**
     * ### Kontruktor
     * @since 0.5.3.pre-alpha.M5
     *
     * @param Sesija $posluzitelj <p>
     * Poslužitelj servisa.
     * </p>
     *
     * @throws Sesija_Greska Ukoliko su isključene sesije,ili se ne može postaviti naziv, lokacija za spremanje datoteka ili parametri sesije.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     */
    public function __construct (
        private Sesija $posluzitelj
    ) {

        // ukoliko su sesije isključene prekini program
        if (session_status() == PHP_SESSION_DISABLED) {

            zapisnik(Level::HITNO, _('Ne mogu pokrenuti sesiju, sesije su isključene!'));
            throw new Sesija_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru'));

        }

        // uključi sesiju samo ukoliko ne postoji
        if (session_status() === PHP_SESSION_NONE) {

            // Postavi naziv sesije
            if (!$this->postaviNaziv()) {

                zapisnik(Level::UZBUNA, sprintf(_('Ne mogu postaviti naziv sesije: %s!'), $this->posluzitelj->naziv));
                throw new Sesija_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru'));

            }

            // Postavi lokaciju za spremanje datoteka
            if (!$this->postaviLokaciju()) {

                zapisnik(Level::UZBUNA, sprintf(_('Ne mogu spremiti sesiju na lokaciju %s!'), $this->posluzitelj->lokacija));
                throw new Sesija_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru'));

            }

            // Postavi parametre sesije
            if (!$this->postaviParametere()) {

                zapisnik(Level::UZBUNA, _('Ne mogu postaviti parametre sesije!'));
                throw new Sesija_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru'));

            }

            session_start();

        }

    }

    /**
     * @inheritDoc
     */
    public function status ():bool {

        if (session_status() !== PHP_SESSION_ACTIVE) {

            return false;

        }

        return true;

    }

    /**
     * @inheritDoc
     */
    public function zapisi (string $kljuc, mixed $vrijednost):bool {

        // zapiši vrijednost
        $_SESSION[$kljuc] = $vrijednost;

        return true;

    }

    /**
     * @inheritDoc
     */
    public function procitaj (string $kljuc):mixed {

        if (!isset($_SESSION[$kljuc])) {

            return false;

        }

        // pročitaj vrijednost
        return $_SESSION[$kljuc];

    }

    /**
     * @inheritDoc
     */
    public function uredi (string $kljuc, mixed $vrijednost):bool {

        if (!isset($_SESSION[$kljuc])) {

            return false;

        }

        // uredi vrijednost
        $_SESSION[$kljuc] = $vrijednost;

        return true;

    }

    /**
     * @inheritDoc
     */
    public function izbrisi (string $kljuc):bool {

        if (!isset($_SESSION[$kljuc])) {

            return false;

        }

        unset($_SESSION[$kljuc]);

        return true;

    }

    /**
     * @inheritDoc
     */
    public function unisti ():bool {

        return session_destroy();

    }

    /**
     * ### Postavi naziv sesije
     * @since 0.5.3.pre-alpha.M5
     *
     * @return string|false Naziv sesije ili False ako se sesija ne može postaviti naziv.
     */
    private function postaviNaziv ():string|false {

        return session_name($this->posluzitelj->naziv);

    }

    /**
     * ### Postavi lokaciju za spremanje datoteka
     * @since 0.5.3.pre-alpha.M5
     *
     * @return string|false Lokacija sesije ili False ako se sesija ne može spremiti na tu lokaciju.
     */
    private function postaviLokaciju ():string|false {

        return session_save_path($this->posluzitelj->lokacija);

    }

    /**
     * ### Postavi parametre sesije
     * @since 0.5.3.pre-alpha.M5
     *
     * @return bool Da li su postavljeni parametri sesije.
     */
    private function postaviParametere():bool {

        return session_set_cookie_params(
            [
                'lifetime' => $this->posluzitelj->vrijeme,
                'path' => $this->posluzitelj->putanja,
                'domain' => $this->posluzitelj->domena,
                'secure' => $this->posluzitelj->ssl,
                'httponly' => $this->posluzitelj->http,
                'samesite' => $this->posluzitelj->ista_stranica->value
            ]
        );

    }

    /**
     * ### Zatvori sesiju
     * @since 0.5.3.pre-alpha.M5
     */
    public function __destruct () {

        session_write_close();

    }

}