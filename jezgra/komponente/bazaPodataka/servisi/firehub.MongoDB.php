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
use FireHub\Jezgra\Komponente\BazaPodataka\Servisi\Jezik\NoSQLDokument;
use FireHub\Jezgra\Komponente\Log\Enumeratori\Level;
use FireHub\Jezgra\Komponente\BazaPodataka\Greske\BazaPodataka_Greska;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;
use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Query;
use MongoDB\Driver\Exception\Exception;
use stdClass;
use Throwable;
use JsonException;

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
     * @param NoSQLDokument $jezik <p>
     * Jezik baze podataka.
     * </p>
     *
     * @throws BazaPodataka_Greska Ukoliko se ne može spojiti na MongoDB server, ne mogu obraditi MongoDB upit ili transakciju.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     * @throws Exception Argument ili uri format konekcije nije ispravan.
     */
    public function __construct (
        private BazaPodataka $posluzitelj,
        private NoSQLDokument $jezik
    ) {

        try {

            $this->konekcija = new Manager(
                "mongodb://{$this->posluzitelj->korisnicko_ime}:{$this->posluzitelj->lozinka}@{$this->posluzitelj->host}:{$this->posluzitelj->port}/{$this->posluzitelj->baza}"
            );

        } catch (Throwable) {

            zapisnik(Level::KRITICNO, _('Ne mogu se spojiti na MongoDB server!'));
            throw new BazaPodataka_Greska(_('Ne mogu se spojiti na MongoDB server!'));

        }

        // provjera vrste upita
        if (isset($this->posluzitelj->upit->vrsta)) {

            $this->upit(
                unserialize($this->jezik->obradi($this->posluzitelj->baza, $this->posluzitelj->tabela, $this->posluzitelj->upit), [Jezik_Interface::class])
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
     * @throws JsonException Ukoliko se ne mogu enkodirati ili dekodirati JSON podatci.
     */
    public function redak ():array|false {

        $rezultat = [];
        foreach ($this->upit as $dokument) {

            $rezultat = json_decode(json_encode($dokument, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);

        }

        return $rezultat;

    }

    /**
     * @inheritDoc
     */
    public function objekt ():object|false {

        $objekt = new stdClass();

        foreach ($this->upit as $dokument) {

            $objekt = $dokument;

        }

        return $objekt;

    }

    /**
     * {@inheritDoc}
     *
     * @throws JsonException Ukoliko se ne mogu enkodirati ili dekodirati JSON podatci.
     */
    public function niz ():array|false {

        $rezultat = [];
        foreach ($this->upit as $dokument) {

            $rezultat[] = json_decode(json_encode($dokument, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);

        }

        return $rezultat;

    }

    /**
     * @inheritDoc
     */
    public function rezultat ():array {

        return $this->lista_upita;

    }

    /**
     * ### Pošalji upit prema MongoDB serveru
     * @since 0.6.0.alpha.M1
     *
     * @param array[] $upit <p>
     * Niz upita prema bazi podataka.
     * </p>
     *
     * @throws BazaPodataka_Greska Ukoliko ne mogu obraditi MongoDB upit.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     * @throws Exception Greške MongoDB servera.
     *
     * @return void
     */
    private function upit (array $upit):void {

        if ($this->posluzitelj->upit->vrsta === 'odaberi') {

            if (!$this->upit = new Query($upit[0], $upit[1])) {

                zapisnik(Level::KRITICNO, sprintf(_('Ne mogu obraditi MongoDB upit: %s!'), $this->jezik->obradi($this->posluzitelj->baza, $this->posluzitelj->tabela, $this->posluzitelj->upit)));
                throw new BazaPodataka_Greska((_('Ne mogu pokrenuti sustav, obratite se administratoru.')));

            }

            $this->upit = $this->konekcija->executeQuery($this->posluzitelj->baza.'.'.$this->posluzitelj->tabela, $this->upit);

        } else {

            if (!$this->upit = new BulkWrite()) {

                zapisnik(Level::KRITICNO, sprintf(_('Ne mogu obraditi MongoDB upit: %s!'), $this->jezik->obradi($this->posluzitelj->baza, $this->posluzitelj->tabela, $this->posluzitelj->upit)));
                throw new BazaPodataka_Greska((_('Ne mogu pokrenuti sustav, obratite se administratoru.')));

            }

            if ($this->posluzitelj->upit->vrsta === 'umetni') {

                $this->upit->insert($upit);

            } else if ($this->posluzitelj->upit->vrsta === 'azuriraj') {

                $this->upit->update($upit[0], $upit[1], array('upsert' => true, 'multi' => true));

            } else if ($this->posluzitelj->upit->vrsta === 'izbrisi') {

                $this->upit->delete($upit);

            }

            $this->konekcija->executeBulkWrite($this->posluzitelj->baza.'.'.$this->posluzitelj->tabela, $this->upit);

        }

    }

    /**
     * ### Pošalji transakciju prema MongoDB serveru
     * @since 0.6.0.alpha.M1
     *
     * @throws BazaPodataka_Greska Ukoliko ne mogu pokreniti transakciju na MongoDB serveru.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     *
     * @return void
     */
    private function transakcija ():void {

        if (!$this->upit = new BulkWrite()) {

            zapisnik(Level::KRITICNO, sprintf(_('Ne mogu obraditi MongoDB upit: %s!'), $this->jezik->obradi($this->posluzitelj->baza, $this->posluzitelj->tabela, $this->posluzitelj->upit)));
            throw new BazaPodataka_Greska((_('Ne mogu pokrenuti sustav, obratite se administratoru.')));

        }

        // pripremi sve upite
        array_walk(
            $this->posluzitelj->transakcija,
            function ($objekt):void {

                $upit = unserialize($this->jezik->obradi($objekt->baza, $objekt->tabela, $objekt->upit), [Jezik_Interface::class]);

                if ($objekt->upit->vrsta === 'umetni') {

                    $this->upit->insert($upit);

                } else if ($objekt->upit->vrsta === 'azuriraj') {

                    $this->upit->update($upit[0], $upit[1], array('upsert' => true, 'multi' => true));

                } else if ($objekt->upit->vrsta === 'izbrisi') {

                    $this->upit->delete($upit);

                }

            }
        );

        $this->konekcija->executeBulkWrite($this->posluzitelj->baza.'.'.$this->posluzitelj->tabela, $this->upit);

    }

}