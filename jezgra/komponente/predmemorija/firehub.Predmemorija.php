<?php declare(strict_types = 1);

/**
 * Datoteka za poslužitelja predmemorije
 * @since 0.5.0.pre-alpha.M5
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\Predmemorija;

use FireHub\Jezgra\Komponente\Servis_Kontejner;
use FireHub\Jezgra\Komponente\Servis_Posluzitelj;
use FireHub\Jezgra\Atributi\Zadano;
use FireHub\Jezgra\Kontejner\Greske\Servis_Posluzitelj_Greska;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;

/**
 * ### Poslužitelj za predmemoriju
 * @since 0.5.0.pre-alpha.M5
 *
 * @property-read string $host IP adresa servera predmemorije
 * @property-read int $port Port servera predmemorije
 * @property-read string $korisnicko_ime Korisnicko ime za spajanje na server predmemorije
 * @property-read string $lozinka Lozinka za spajanje na server predmemorije
 * @property-read bool $trajno Trajna konekcija na predmemoriju
 * @property-read int $tezina Težina u odnosu na ostale servere predmemorije
 * @property-read float $odziv Maksimalni odziv u sekundama na koji se čeka da server reagira
 * @property-read int $interval_ponovni_pokusaj Interval u kojem će server ponovno pokušati pronaći zapise
 * @property-read array $dodatni_serveri Dodatni serveri predmemorije
 *
 * @method $this trajno(bool $trajno) Trajna konekcija na predmemoriju
 * @method $this tezina(int $tezina) Težina u odnosu na ostale servere predmemorije
 * @method $this odziv(float $odziv) Maksimalni odziv u sekundama na koji se čeka da server reagira
 * @method $this interval_ponovni_pokusaj(int $interval_ponovni_pokusaj) Interval u kojem će server ponovno pokušati pronaći zapise
 * @method $this dodatni_serveri(array $dodatni_serveri) Dodatni serveri predmemorije
 *
 * @package Sustav\Jezgra
 */
final class Predmemorija extends Servis_Posluzitelj {

    #[Zadano('predmemorija.servis')]
    protected ?string $servis = null;

    /**
     * ### IP adresa servera predmemorije
     * @var string
     */
    #[Zadano('predmemorija.host')]
    protected string $host;

    /**
     * ### Port servera predmemorije
     * @var int
     */
    #[Zadano('predmemorija.port')]
    protected int $port;

    /**
     * ### Korisnicko ime za spajanje na server predmemorije
     * @var string
     */
    #[Zadano('predmemorija.korisnicko_ime')]
    protected string $korisnicko_ime;

    /**
     * ### Lozinka za spajanje na server predmemorije
     * @var string
     */
    #[Zadano('predmemorija.lozinka')]
    protected string $lozinka;

    /**
     * ### Trajna konekcija na predmemoriju
     * @var bool
     */
    #[Zadano('predmemorija.trajno')]
    protected bool $trajno;

    /**
     * ### Težina u odnosu na ostale servere predmemorije
     * @var int
     */
    #[Zadano('predmemorija.tezina')]
    protected int $tezina;

    /**
     * ### Maksimalni odziv u sekundama na koji se čeka da server reagira
     * @var float
     */
    #[Zadano('predmemorija.odziv')]
    protected float $odziv;

    /**
     * ### Interval u kojem će server ponovno pokušati pronaći zapise
     * @var int
     */
    #[Zadano('predmemorija.interval_ponovni_pokusaj')]
    protected int $interval_ponovni_pokusaj;

    /**
     * ### Dodatni serveri predmemorije
     * @var array<int, array<string, mixed>>
     */
    private array $dodatni_serveri;

    /**
     * ### Konstruktor
     * @since 0.5.0.pre-alpha.M5
     *
     * @throws Servis_Posluzitelj_Greska Ukoliko se ne mogu postaviti zadana svojstva poslužitelja.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Konfiguracije.
     */
    public function __construct () {

        parent::__construct();

        $this->dodatni_serveri = $this->konfiguracijaDodatniServeri();

    }

    /**
     * ### Server predmemorije
     * @since 0.5.0.pre-alpha.M5
     *
     * @param string $host <p>
     * Naziv hosta predmemorije.
     * </p>
     * @param int $port <p>
     * Port predmemorije.
     * </p>
     *
     * @return $this Instanca Predmemorije.
     */
    public function server (string $host, int $port):self {

        $this->host = $host;
        $this->port = $port;

        return $this;

    }

    /**
     * ### Vjerodajnice za spajanje na server predmemorije
     * @since 0.5.0.pre-alpha.M5
     *
     * @param string $korisnicko_ime <p>
     * Korisničko ime servera predmemorije.
     * </p>
     * @param string $lozinka <p>
     * Lozinka servera predmemorije.
     * </p>
     *
     * @return $this Instanca Predmemorije.
     */
    public function vjerodajnice (string $korisnicko_ime, string $lozinka):self {

        $this->korisnicko_ime = $korisnicko_ime;
        $this->lozinka = $lozinka;

        return $this;

    }

    /**
     * ### Dodatni serveri predmemorije
     * @since 0.5.0.pre-alpha.M5
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Konfiguracije.
     *
     * @return array<int, array<string, mixed>> Niz dodatnih servera predmemorije.
     */
    private function konfiguracijaDodatniServeri ():array {

        return array_map(
            function ($server):array {

                $serveri_lista = explode(',', $server);

                $serveri_lista_sa_nazivima = [];
                foreach ($serveri_lista as $serveri_lista_stavka) {

                    $serveri_lista_stavka = explode('=', $serveri_lista_stavka);
                    $serveri_lista_sa_nazivima[$serveri_lista_stavka[0]] = $serveri_lista_stavka[1];

                }

                return array_merge(
                    $serveri_lista_sa_nazivima,
                    [
                        'trajno' => $this->trajno,
                        'tezina' => $this->tezina,
                        'odziv' => $this->odziv,
                        'interval_ponovni_pokusaj' => $this->interval_ponovni_pokusaj
                    ]
                );

            },
            konfiguracija('predmemorija.dodatni_serveri') ? explode(';', konfiguracija('predmemorija.dodatni_serveri')) : []
        );

    }

    /**
     * {@inheritDoc}
     *
     * @return Predmemorija_Interface Objekt Predmemorije servisa.
     */
    public function napravi ():object {

        return (new Servis_Kontejner($this))->singleton();

    }

}