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
     * ### Konstruktor
     * @since 0.5.1.pre-alpha.M5
     *
     * @param BazaPodataka $posluzitelj <p>
     * Poslužitelj servisa.
     * </p>
     *
     * @throws BazaPodataka_Greska Ukoliko se ne može spojiti na MSSQL server.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a ili konfiguracije.
     */
    public function __construct (
        private BazaPodataka $posluzitelj
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

    }

    public function redak ():array
    {
        // TODO: Implement redak() method.
    }

    public function objekt ():object
    {
        // TODO: Implement objekt() method.
    }

    public function niz ():array
    {
        // TODO: Implement niz() method.
    }

    public function rezultat ():array
    {
        // TODO: Implement rezultat() method.
    }

}