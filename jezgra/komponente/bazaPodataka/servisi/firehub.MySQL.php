<?php declare(strict_types = 1);

/**
 * Datoteka za MySQL bazu podataka
 * @since 0.5.1.pre-alpha.M5
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\BazaPodataka\Servisi;

use FireHub\Jezgra\Komponente\BazaPodataka\BazaPodataka;
use FireHub\Jezgra\Komponente\BazaPodataka\BazaPodataka_Interface;
use FireHub\Jezgra\Komponente\BazaPodataka\Servisi\Jezik\MySQL as MySQL_Jezik;
use FireHub\Jezgra\Komponente\Log\Enumeratori\Level;
use FireHub\Jezgra\Komponente\BazaPodataka\Greske\BazaPodataka_Greska;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;

/**
 * ### Servis MySQL baze podataka
 * @since 0.5.1.pre-alpha.M5
 *
 * @package Sustav\Jezgra
 */
final class MySQL implements BazaPodataka_Interface {

    /**
     * ### Konekcija na MySQL server
     * @var mixed
     */
    private mixed $konekcija;

    /**
     * ### Upit prema MySQL serveru
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
     * @param MySQL_Jezik $jezik <p>
     * Jezik baze podataka.
     * </p>
     *
     * @throws BazaPodataka_Greska Ukoliko se ne može spojiti na MSSQL server, ne mogu obraditi MySQL upit ili transakciju.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     */
    public function __construct (
        private BazaPodataka $posluzitelj,
        private MySQL_Jezik $jezik
    ) {

        $this->konekcija = mysqli_connect(
            $this->posluzitelj->host,
            $this->posluzitelj->korisnicko_ime,
            $this->posluzitelj->lozinka,
            $this->posluzitelj->baza,
            $this->posluzitelj->port
        );

        if (mysqli_connect_errno()) {

            zapisnik(Level::UZBUNA, _('Ne mogu se spojiti na MySQL server!'));
            throw new BazaPodataka_Greska(_('Ne mogu se spojiti na MySQL server!'));

        }

        // provjera vrste upita
        if (isset($this->posluzitelj->upit->sirovi)) {

            $this->upit($this->posluzitelj->upit->sirovi);

        } else if (isset($this->posluzitelj->upit->vrsta)) {

            $this->upit(
                $this->jezik->obradi($this->posluzitelj->baza, $this->posluzitelj->tabela, $this->posluzitelj->upit)
            );

        } else if (!is_null($this->posluzitelj->transakcija)) {

            $this->transakcija();

        } else {

            throw new BazaPodataka_Greska(_('Ne postoji niti upit niti transakcija prema bazi podataka!'));

        }

    }

    /**
     * @inheritDoc
     */
    public function redak ():array|false {

        // dohvati podatke
        $redak = mysqli_fetch_row($this->upit);

        // preuzmi meta podatke
        $meta_podatci = mysqli_fetch_fields($this->upit);

        $broj_kolumne = 0;
        array_walk (
            $meta_podatci,
            static function ($kolumna) use (&$rezultat, &$redak, &$broj_kolumne):mixed {
                if (!isset($redak)) {
                    return $rezultat = false;
                }
                return $rezultat[$kolumna->name] = $redak[$broj_kolumne++];
            }
        );

        return $rezultat;

    }

    /**
     * {@inheritDoc}
     *
     * @throws BazaPodataka_Greska Ukoliko ne mogu napraviti upit kao objekt.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     */
    public function objekt ():object|false {

        if (($objekt = mysqli_fetch_object($this->upit)) === false) {

            zapisnik(Level::KRITICNO, _('Ne se napraviti upit: %s, kao objekt!'));
            throw new BazaPodataka_Greska((_('Ne mogu pokrenuti sustav, obratite se administratoru.')));

        }

        return !is_null($objekt) ? $objekt : false;

    }

    /**
     * @inheritDoc
     */
    public function niz ():array|false {

        while ($redak = mysqli_fetch_assoc($this->upit)) {

            $rezultat[] = $redak;

        }

        return $rezultat ?? false;

    }

    /**
     * @inheritDoc
     */
    public function rezultat ():array {

        return $this->lista_upita;

    }

    /**
     * ### Pošalji upit prema MySQL serveru
     * @since 0.5.1.pre-alpha.M5
     *
     * @param string $upit <p>
     * Upit prema bazi podataka.
     * </p>
     *
     * @throws BazaPodataka_Greska Ukoliko ne mogu obraditi MySQL upit.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     *
     * @return void
     */
    private function upit (string $upit):void {

        if (
            !$this->upit = mysqli_query(
                $this->konekcija,
                $upit
            )
        ) {

            zapisnik(Level::KRITICNO, sprintf(_('Ne mogu obraditi MySQL upit: %s!'), $this->posluzitelj->upit));
            throw new BazaPodataka_Greska((_('Ne mogu pokrenuti sustav, obratite se administratoru.')));

        }

    }

    /**
     * ### Pošalji transakciju prema MySQL serveru
     * @since 0.5.1.pre-alpha.M5
     *
     * @throws BazaPodataka_Greska Ukoliko ne mogu pokreniti transakciju na MySQL serveru.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     *
     * @return void
     */
    private function transakcija ():void {

        if (!mysqli_begin_transaction($this->konekcija)) {

            zapisnik(Level::KRITICNO, _('Ne mogu pokreniti transakciju na MySQL serveru!'));
            throw new BazaPodataka_Greska((_('Ne mogu pokrenuti sustav, obratite se administratoru.')));

        }

        // pripremi sve upite
        array_walk(
            $this->posluzitelj->transakcija,
            function ($transakcija) {

                if (
                    !$upit = mysqli_prepare(
                        $this->konekcija,
                        $this->jezik->obradi($transakcija->baza, $transakcija->tabela, $transakcija->upit)
                    )
                ) {

                    zapisnik(Level::KRITICNO, _('Upit na transakciju na MySQL serveru se ne može pokrenuti!'));
                    throw new BazaPodataka_Greska((_('Ne mogu pokrenuti sustav, obratite se administratoru.')));

                }

                $this->lista_upita[] = mysqli_stmt_execute($upit);

            }
        );

        // ako padne neki upit vrati transakciju
        array_walk(
            $this->lista_upita,
            function ($upit):void {
                if (!$upit) {mysqli_rollback($this->konekcija);}
            }
        );

        // završi transakciju
        mysqli_commit($this->konekcija);

    }

    /**
     * ### Zatvori konekciju baze podataka
     * @since 0.5.1.pre-alpha.M5
     *
     * @return void
     */
    public function __destruct () {

        mysqli_close($this->konekcija);

    }

}