<?php declare(strict_types = 1);

/**
 * Datoteka za čitanje detalja o datotekama
 * @since 0.3.4.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\Datoteka\Servisi;

use FireHub\Jezgra\Komponente\Datoteka\Datoteka;
use FireHub\Jezgra\Komponente\Datoteka\Datoteka_Interface;
use FireHub\Jezgra\Komponente\Log\Enumeratori\Level;
use FireHub\Jezgra\Komponente\Datoteka\Greske\Datoteka_Greska;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;

/**
 * ### Servis za čitanje detalja o datotekama
 * @since 0.3.4.pre-alpha.M3
 *
 * @package Sustav\Jezgra
 */
final class Datoteka_Servis implements Datoteka_Interface {

    /**
     * ### Kontruktor
     * @since 0.3.4.pre-alpha.M3
     *
     * @param Datoteka $posluzitelj <p>
     * Poslužitelj servisa.
     * </p>
     *
     * @throws Datoteka_Greska Ukoliko se ne može učitati datoteka.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     */
    public function __construct (
        private Datoteka $posluzitelj
    ) {

        // provjeri dali je ispravna env datoteka
        if (!is_file($this->posluzitelj->datoteka)) {

            zapisnik(Level::GRESKA, sprintf(_('Ne mogu učitati datoteku %s'), $this->posluzitelj->datoteka));
            throw new Datoteka_Greska(_('Ne mogu pročitati datoteku potrebnu za rad sustava, obratite se administratoru'));

        }

    }

    /**
     * ### Vrijeme zadnje izmjene datoteke
     * @since 0.3.4.pre-alpha.M3
     *
     * @throws Datoteka_Greska Ukoliko se ne može pročitati vrijeme zadnje izmjene datoteke.
     * @throws Kontejner_Greska Ukoliko može spremiti instanca Log-a.
     *
     * @return int Broj sekundi zadnje izmjene datoteke.
     */
    public function zadnjeIzmijenjen ():int {

        if (!filemtime($this->posluzitelj->datoteka)) {

            zapisnik(Level::UPOZORENJE, sprintf(_('Ne mogu pročitati vrijeme zadnje izmijene datoteke %s'), $this->posluzitelj->datoteka));
            throw new Datoteka_Greska(_('Ne mogu pročitati vrijeme zadnje izmijene datoteke potrebne za rad sustava, obratite se administratoru'));

        }

        return filemtime($this->posluzitelj->datoteka);

    }

    /**
     * ### Pretvaranje imena datoteke u md5 hash algaritam
     * @since 0.3.4.pre-alpha.M3
     *
     * @throws Datoteka_Greska Ukoliko se ne može pročitati naziv datoteke.
     * @throws Kontejner_Greska Ukoliko se može spremiti instanca Log-a.
     *
     * @return string MD5 hash od naziva datoteke.
     *
     * @todo $_server svojstvo
     */
    public function eTag ():string {

        if (!md5($this->posluzitelj->datoteka)) {

            zapisnik(Level::UPOZORENJE, sprintf(_('Ne mogu pročitati naziv datoteke %s'), $this->posluzitelj->datoteka));
            throw new Datoteka_Greska(_('Ne mogu pročitati naziv datoteke potrebne za rad sustava, obratite se administratoru'));

        }

        return md5($this->posluzitelj->datoteka);

    }

    /**
     * ### Datum i vrijeme zadnje promjene datoteke
     * @since 0.3.4.pre-alpha.M3
     *
     * @return string Vrijeme zadnje izmjene datoteke.
     *
     * @todo $_server svojstvo
     */
    public function izmijenjenod ():string {

        return $_SERVER['HTTP_IF_MODIFIED_SINCE'] ?? '';

    }

    /**
     * ### Pretvaranje datuma i vremena datoteke u md5 hash algaritam
     * @since 0.3.4.pre-alpha.M3
     *
     * @return string eTag zaglavlja datoteke.
     *
     * @todo $_server svojstvo
     */
    public function eTagZaglavlje ():string {

        return $_SERVER['HTTP_IF_NONE_MATCH'] ?? '';

    }

}