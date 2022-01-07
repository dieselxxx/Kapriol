<?php declare(strict_types = 1);

/**
 * Datoteka za MongoDB bazu podataka
 * @since 0.5.1.pre-alpha.M5
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 *
 * @todo Završiti kada stigne driver za PHP 8.1
 */

namespace FireHub\Jezgra\Komponente\BazaPodataka\Servisi;

use FireHub\Jezgra\Komponente\BazaPodataka\BazaPodataka;
use FireHub\Jezgra\Komponente\BazaPodataka\BazaPodataka_Interface;
use FireHub\Jezgra\Komponente\Log\Enumeratori\Level;
use FireHub\Jezgra\Komponente\BazaPodataka\Greske\BazaPodataka_Greska;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;
use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Query;
use Throwable;

/**
 * ### Servis MongoDB baze podataka
 * @since 0.5.1.pre-alpha.M5
 *
 * @package Sustav\Jezgra
 */
final class MongoDB implements BazaPodataka_Interface {

    /**
     * ### Konekcija na MongoDB server
     * @var mixed
     */
    private mixed $konekcija;

    /**
     * ### Upit prema MongoDB serveru
     * @var mixed
     */
    private mixed $upit;

    /**
     * ### Lista upita transakcije
     * @var array
     */
    private array $lista_upita = [];

    /**
     * ### Konstruktor
     * @since 0.5.1.pre-alpha.M5
     *
     * @param BazaPodataka $posluzitelj <p>
     * Poslužitelj servisa.
     * </p>
     *
     * @throws BazaPodataka_Greska Ukoliko se ne može spojiti na MongoDB server, ne mogu obraditi MongoDB upit ili transakciju.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     */
    public function __construct (
        private BazaPodataka $posluzitelj
    ) {

        try {

            $this->konekcija = new Manager(
                "mongodb://{$this->posluzitelj->korisnicko_ime}:{$this->posluzitelj->lozinka}@{$this->posluzitelj->host}:{$this->posluzitelj->port}/{$this->posluzitelj->baza}"
            );

        } catch (Throwable) {

            zapisnik(Level::KRITICNO, _('Ne mogu se spojiti na MongoDB server!'));
            throw new BazaPodataka_Greska(_('Ne mogu se spojiti na MongoDB server!'));

        }

        match (null) {
            $this->posluzitelj->upit => $this->transakcija(),
            $this->posluzitelj->transakcija => $this->upit(),
            default => throw new BazaPodataka_Greska(_('Ne postoji niti upit niti transakcija prema bazi podataka!'))
        };

    }

    public function redak ():array|false {
    }

    public function objekt ():object|false {
    }

    public function niz ():array|false {
    }

    public function rezultat ():array {
    }

}