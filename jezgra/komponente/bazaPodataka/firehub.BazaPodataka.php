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
use FireHub\Jezgra\Komponente\BazaPodataka\Servisi\Upit;
use FireHub\Jezgra\Atributi\Zadano;

/**
 * ### Poslužitelj za bazu podataka
 * @since 0.5.1.pre-alpha.M5
 *
 * @property-read string $host IP adresa servera baze podataka
 * @property-read int $port Port servera baze podataka
 * @property-read string $instanca Instanca servera baze podataka
 * @property-read string $baza Baza baze podataka
 * @property-read string $shema Shema baze podataka
 * @property-read string $korisnicko_ime Korisnicko ime za spajanje na server baze podataka
 * @property-read string $lozinka Lozinka za spajanje na server baze podataka
 * @property-read string $karakteri Enkodiranje karaketera iz baze podataka
 * @property-read int $odziv Maksimalni odziv servera u sekundama prilikom upita
 * @property-read bool $posalji_stream_pri_izvrsavanju Slanje svih podataka pri izvršavanju u upita ili u dijelovima
 * @property-read Kursor_Interface $kursor Način redoslijeda odabiranja redaka
 * @property-read null|Upit $upit Upit prema bazi podataka
 * @property-read null|string[] $transakcija Niz upita prema bazi podataka u obliku transakcije
 * @property-read string $tabela Tabela za upit
 *
 * @method $this baza (string $naziv) Baza baze podataka
 * @method $this shema (string $naziv) Shema baze podataka
 * @method $this karakteri (string $vrsta) Enkodiranje karaketera iz baze podataka
 * @method $this odziv (int $sekundi) Maksimalni odziv servera u sekundama prilikom upita
 * @method $this posalji_stream_pri_izvrsavanju (bool $ukljuceno) Slanje svih podataka pri izvršavanju u upita ili u dijelovima
 * @method $this kursor (Kursor_Interface $vrsta) Način redoslijeda odabiranja redaka
 * @method $this tabela (string $naziv) Tabela za upit
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
     * @var Kursor_Interface
     */
    #[Zadano('baza_podataka.kursor')]
    protected Kursor_Interface $kursor;

    /**
     * ### Upit prema bazi podataka
     * @var null|Upit
     */
    protected ?Upit $upit = null;

    /**
     * ### Niz upita prema bazi podataka u obliku transakcije
     * @var null|string[]
     */
    protected ?array $transakcija = null;

    /**
     * ### Tabela za upit
     * @var string
     */
    protected string $tabela;

    /**
     * ### Server baze podataka
     * @since 0.5.1.pre-alpha.M5
     *
     * @param string $host <p>
     * IP adresa servera baze podataka.
     * </p>
     * @param int $port <p>
     * Port servera baze podataka.
     * </p>
     * @param string $instanca [optional] <p>
     * Instanca servera baze podataka.
     * </p>
     *
     * @return $this Instanca Baze Podataka.
     */
    public function server (string $host, int $port, string $instanca = ''):self {

        $this->host = $host;
        $this->port = $port;
        $this->instanca = $instanca;

        return $this;

    }

    /**
     * ### Vjerodajnice za spajanje na server baze podataka
     * @since 0.5.1.pre-alpha.M5
     *
     * @param string $korisnicko_ime <p>
     * Korisničko ime za spajanje na server baze podataka.
     * </p>
     * @param string $lozinka <p>
     * Lozinka za spajanje na server baze podataka.
     * </p>
     *
     * @return $this Instanca Baze Podataka.
     */
    public function vjerodajnice (string $korisnicko_ime, string $lozinka):self {

        $this->korisnicko_ime = $korisnicko_ime;
        $this->lozinka = $lozinka;

        return $this;

    }

    /**
     * ### Opcije za spajanje na server baze podataka
     * @since @since 0.6.0.alpha.M1
     *
     * @param int $odziv <p>
     * Maksimalni odziv servera u sekundama prilikom upita.
     * </p>
     * @param bool $posalji_stream_pri_izvrsavanju <p>
     * KSlanje svih podataka pri izvršavanju u upita ili u dijelovima.
     * </p>
     * @param Kursor_Interface $kursor <p>
     * Način redoslijeda odabiranja redaka.
     * </p>
     *
     * @return $this
     */
    public function opcije (int $odziv, bool $posalji_stream_pri_izvrsavanju, Kursor_Interface $kursor):self {

        $this->odziv = $odziv;
        $this->posalji_stream_pri_izvrsavanju = $posalji_stream_pri_izvrsavanju;
        $this->kursor = $kursor;

        return $this;

    }

    /**
     * ### Slanje sirovog upita prema servisu baze podataka
     * @since 0.6.0.alpha.M1
     *
     * @param string $upit  <p>
     * Sirovi upit prema bazi podataka.
     * </p>
     *
     * @return $this
     */
    public function sirovi (string $upit):self {

        $this->upit = new Upit();
        $this->upit->sirovi = $upit;

        return $this;

    }

    /**
     * ### Niz upita prema bazi podataka u obliku transakcije
     * @since 0.5.1.pre-alpha.M5
     *
     * @param BazaPodataka ...$upit <p>
     * Instanca BazePodataka.
     * </p>
     *
     * @return $this Instanca Baze Podataka.
     */
    public function transakcija (BazaPodataka ...$upit):self {

        $this->transakcija = $upit;

        return $this;

    }

    /**
     * ### Odaberi kolumne iz tabele
     * @since 0.6.0.alpha.M1
     *
     * @param array $kolumne <p>
     * Lista kolumni za odabir.
     * </p>
     *
     * @return $this Instanca Baze Podataka.
     */
    public function odaberi (array $kolumne):self {

        $this->upit = new Upit();
        $this->upit->vrsta = 'odaberi';
        $this->upit->kolumne = $kolumne;

        return $this;

    }

    /**
     * ### Izbriši redak iz tabele
     * @since 0.6.0.alpha.M1
     *
     * @param array $podatci <p>
     * Lista podataka za umetanje.
     * </p>
     *
     * @return $this Instanca Baze Podataka.
     */
    public function umetni (array $podatci):self {

        $this->upit = new Upit();
        $this->upit->vrsta = 'umetni';
        $this->upit->podatci = $podatci;

        return $this;

    }

    /**
     * ### Ažuriraj redak iz tabele
     * @since 0.6.0.alpha.M1
     *
     * @param array $podatci <p>
     * Lista podataka za ažuriranje.
     * </p>
     *
     * @return $this Instanca Baze Podataka.
     */
    public function azuriraj (array $podatci):self {

        $this->upit = new Upit();
        $this->upit->vrsta = 'azuriraj';
        $this->upit->podatci = $podatci;

        return $this;

    }

    /**
     * ### Izbriši redak iz tabele
     * @since 0.6.0.alpha.M1
     *
     * @return $this Instanca Baze Podataka.
     */
    public function izbrisi ():self {

        $this->upit = new Upit();
        $this->upit->vrsta = 'izbrisi';

        return $this;

    }

    /**
     * ### Filtar vrijednost po nazivu kolumne
     * @since 0.6.0.alpha.M1
     *
     * @param string $naziv <p>
     * Naziv filtra.
     * </p>
     * @param string $operator <p>
     * Vrsta operatora za uspoređivanje.
     * <, > ili =
     * </p>
     * @param mixed $vrijednost <p>
     * Vrijednost za usporediti.
     * </p>
     *
     * @return $this Instanca Baze Podataka.
     */
    public function gdje (string $naziv, string $operator, mixed $vrijednost):self {

        $this->upit->gdje[] = ['naziv' => $naziv, 'operator' => $operator, 'vrijednost' => $vrijednost];

        return $this;

    }

    /**
     * ### Redanje zapisa
     * @since 0.6.0.alpha.M1
     *
     * @param string $poredaj <p>
     * Kolumna po kojoj redamo zapise.
     * </p>
     * @param string $redoslijed <p>
     * Redolijed po kojoj redamo zapise.
     * ASC ili DESC.
     * </p>
     *
     * @return $this Instanca Baze Podataka.
     */
    public function poredaj (string $poredaj, string $redoslijed):self {

        $this->upit->poredaj = $poredaj;
        $this->upit->poredaj_redoslijed = strtoupper($redoslijed);

        return $this;

    }

    /**
     * ### Limit broja zapisa iz baze podataka
     * @since 0.6.0.alpha.M1
     *
     * @param int $pomak <p>
     * Pomak od kojeg se limitiraju zapisi.
     * </p>
     * @param int $broj_redaka <p>
     * Broj redaka koje odabiremo.
     * </p>
     *
     * @return $this Instanca Baze Podataka.
     */
    public function limit (int $pomak, int $broj_redaka):self {

        $this->upit->limit_pomak = $pomak;
        $this->upit->limit_broj_redaka = $broj_redaka;

        return $this;

    }

    /**
     * {@inheritDoc}
     *
     * @return BazaPodataka_Interface Objekt Predmemorije servisa.
     */
    public function napravi ():object {

        return (new Servis_Kontejner($this))->dohvati();

    }

}