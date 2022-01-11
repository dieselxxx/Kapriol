<?php declare(strict_types = 1);

/**
 * Datoteka za MSSQL bazu podataka
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
use FireHub\Jezgra\Komponente\BazaPodataka\Servisi\Jezik\SQL;
use FireHub\Jezgra\Komponente\Log\Enumeratori\Level;
use FireHub\Jezgra\Komponente\BazaPodataka\Greske\BazaPodataka_Greska;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;

/**
 * ### Servis MSSQL baze podataka
 * @since 0.5.1.pre-alpha.M5
 *
 * @package Sustav\Jezgra
 */
final class MSSQL implements BazaPodataka_Interface {

    /**
     * ### Konekcija na MSSQL server
     * @var mixed
     */
    private mixed $konekcija;

    /**
     * ### Upit prema MSSQL serveru
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
     * @param SQL $jezik <p>
     * Jezik baze podataka.
     * </p>
     *
     * @throws BazaPodataka_Greska Ukoliko se ne može spojiti na MSSQL server, ne mogu obraditi MySQL upit ili transakciju.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a ili konfiguracije.
     */
    public function __construct (
        private BazaPodataka $posluzitelj,
        private SQL $jezik
    ) {

        if (
            !$this->konekcija = sqlsrv_connect(
                $this->posluzitelj->host . '\\' . $this->posluzitelj->instanca . ',' . $this->posluzitelj->port,
                [
                    'Database' => $this->posluzitelj->baza,
                    'UID' => $this->posluzitelj->korisnicko_ime,
                    'PWD' => $this->posluzitelj->lozinka,
                    'CharacterSet' => $this->posluzitelj->karakteri
                ]
            )
        ) {

            zapisnik(Level::UZBUNA, _('Ne mogu se spojiti na MSSQL server!'));
            throw new BazaPodataka_Greska(_('Ne mogu se spojiti na MSSQL server!'));

        }

        // dodatni postavke MSSQL-a
        sqlsrv_configure('LogSeverity', konfiguracija('baza_podataka.greske'));
        sqlsrv_configure('LogSubsystems', konfiguracija('baza_podataka.vrste_greski'));

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
     * {@inheritDoc}
     *
     * @todo Provjeriti kursor ponašanje za više upita prema ovoj funkciji.
     * @todo Provjeriti ponašanje nakon što nema retka u sqlsrv_get_field funkciji.
     */
    public function redak ():array|false {

        // dohvati podatke
        sqlsrv_fetch($this->upit);

        // preuzmi meta podatke
        $meta_podatci = sqlsrv_field_metadata($this->upit);

        $broj_kolumne = 0;
        array_walk (
            $meta_podatci,
            function ($kolumna) use (&$rezultat, &$broj_kolumne):mixed {
                return $rezultat[$kolumna['Name']] = sqlsrv_get_field($this->upit, $broj_kolumne++);
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

        if (($objekt = sqlsrv_fetch_object($this->upit)) === false) {

            zapisnik(Level::KRITICNO, _('Ne se napraviti upit: %s, kao objekt!'));
            throw new BazaPodataka_Greska((_('Ne mogu pokrenuti sustav, obratite se administratoru.')));

        }

        return !is_null($objekt) ? $objekt : false;

    }

    /**
     * @inheritDoc
     */
    public function niz ():array|false {

        while ($redak = sqlsrv_fetch_array($this->upit, SQLSRV_FETCH_ASSOC)) {

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
     * ### Pošalji upit prema MSSQL serveru
     * @since 0.5.1.pre-alpha.M5
     *
     * @param string $upit <p>
     * Upit prema bazi podataka.
     * </p>
     *
     * @throws BazaPodataka_Greska Ukoliko ne mogu obraditi MSSQL upit.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     *
     * @return void
     */
    private function upit (string $upit):void {

        if (
            !$this->upit = sqlsrv_query(
                $this->konekcija,
                $upit,
                [],
                [
                    'QueryTimeout' => $this->posluzitelj->odziv,
                    'SendStreamParamsAtExec' => $this->posluzitelj->posalji_stream_pri_izvrsavanju,
                    'Scrollable' => $this->posluzitelj->kursor
                ]
            )
        ) {

            zapisnik(Level::KRITICNO, sprintf(_('Ne mogu obraditi MSSQL upit: %s!'), $this->posluzitelj->upit));
            throw new BazaPodataka_Greska((_('Ne mogu pokrenuti sustav, obratite se administratoru.')));

        }

    }

    /**
     * ### Pošalji transakciju prema MSSQL serveru
     * @since 0.5.1.pre-alpha.M5
     *
     * @throws BazaPodataka_Greska Ukoliko ne mogu pokreniti transakciju na MSSQL serveru.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     *
     * @return void
     */
    private function transakcija ():void {

        if (!sqlsrv_begin_transaction($this->konekcija)) {

            zapisnik(Level::KRITICNO, _('Ne mogu pokreniti transakciju na MSSQL serveru!'));
            throw new BazaPodataka_Greska((_('Ne mogu pokrenuti sustav, obratite se administratoru.')));

        }

        // pripremi sve upite
        array_walk(
            $this->posluzitelj->transakcija,
            function ($transakcija) {

                $this->lista_upita[] = sqlsrv_query(
                    $this->konekcija,
                    $this->jezik->obradi($transakcija->baza, $transakcija->tabela, $transakcija->upit),
                    [],
                    [
                        'QueryTimeout' => $transakcija->odziv,
                        'SendStreamParamsAtExec' => $transakcija->posalji_stream_pri_izvrsavanju,
                        'Scrollable' => $transakcija->kursor
                    ]
                );

            }
        );

        // ako padne neki upit vrati transakciju
        array_walk(
            $this->lista_upita,
            function ($upit):void {

                if (!$upit) {
                    sqlsrv_rollback($this->konekcija);
                }

            }
        );

        // završi transakciju
        sqlsrv_commit($this->konekcija);

    }

    /**
     * ### Zatvori konekciju baze podataka
     * @since 0.5.1.pre-alpha.M5
     *
     * @return void
     */
    public function __destruct () {

        sqlsrv_close($this->konekcija);

    }

}