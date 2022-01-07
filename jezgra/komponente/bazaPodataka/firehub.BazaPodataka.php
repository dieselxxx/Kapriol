<?php declare(strict_types = 1);

/**
 * Datoteka za poslužitelja baze podataka
 * @since 0.5.1.pre-alpha.M5
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\BazaPodataka;

use FireHub\Jezgra\Komponente\Servis_Kontejner;
use FireHub\Jezgra\Komponente\Servis_Posluzitelj;
use FireHub\Jezgra\Atributi\Zadano;

/**
 * ### Poslužitelj za bazu podataka
 * @since 0.5.1.pre-alpha.M5
 */
final class BazaPodataka extends Servis_Posluzitelj {

    /**
     * @inheritdoc
     */
    #[Zadano('baza_podataka.servis')]
    protected ?string $servis = null;

    /**
     * ### IP adresa servera baze podataka
     * @var string
     */
    #[Zadano('baza_podataka.host')]
    protected string $host;

    /**
     * ### Port servera baze podataka
     * @var int
     */
    #[Zadano('baza_podataka.port')]
    protected int $port;

    /**
     * ### Instanca servera baze podataka
     * @var string
     */
    #[Zadano('baza_podataka.instanca')]
    protected string $instanca;

    /**
     * ### Baza baze podataka
     * @var string
     */
    #[Zadano('baza_podataka.baza')]
    protected string $baza;

    /**
     * ### Shema baze podataka
     * @var string
     */
    #[Zadano('baza_podataka.shema')]
    protected string $shema;

    /**
     * ### Korisnicko ime za spajanje na server baze podataka
     * @var string
     */
    #[Zadano('baza_podataka.korisnicko_ime')]
    protected string $korisnicko_ime;

    /**
     * ### Lozinka za spajanje na server baze podataka
     * @var string
     */
    #[Zadano('baza_podataka.lozinka')]
    protected string $lozinka;

    /**
     * ### Enkodiranje karaketera iz baze podataka
     * @var string
     */
    #[Zadano('baza_podataka.karakteri')]
    protected string $karakteri;

    /**
     * ### Maksimalni odziv servera u sekundama prilikom upita
     * @var int
     */
    #[Zadano('baza_podataka.odziv')]
    protected int $odziv;

    /**
     * ### Slanje svih podataka pri izvršavanju u upita ili u dijelovima
     * @var bool
     */
    #[Zadano('baza_podataka.posalji_stream_pri_izvrsavanju')]
    protected bool $posalji_stream_pri_izvrsavanju;

    /**
     * ### Način redoslijeda odabiranja redaka
     * @var string
     */
    #[Zadano('baza_podataka.kursor')]
    protected string $kursor;

    /**
     * {@inheritDoc}
     *
     * @return BazaPodataka_Interface Objekt Predmemorije servisa.
     */
    public function napravi ():object {

        return (new Servis_Kontejner($this))->singleton();

    }

}