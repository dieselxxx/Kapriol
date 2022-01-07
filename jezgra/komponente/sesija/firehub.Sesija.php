<?php declare(strict_types = 1);

/**
 * Datoteka za upravljanje sesijama
 * @since 0.5.3.pre-alpha.M5
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\Sesija;


use FireHub\Jezgra\Komponente\Servis_Kontejner;
use FireHub\Jezgra\Komponente\Servis_Posluzitelj;
use FireHub\Jezgra\Komponente\Kolacic\Enumeratori\IstaStranica;
use FireHub\Jezgra\Atributi\Zadano;

/**
 * ### Poslužitelj za upravljanje sesijama
 * @since 0.5.3.pre-alpha.M5
 *
 * @property-read string $naziv Naziv kolačića sesije
 * @property-read int $vrijeme Maksimalno vrijeme kolačića
 * @property-read string $putanja Putanja za koju vrijedi kolačić
 * @property-read string $domena Naziv domene ili poddomene na koji je kolačić dostupan
 * @property-read bool $ssl Da li kolačić zahtjeva SSL enkriptiranu vezu
 * @property-read bool $http Da li je kolačić dostupan samo u HTTP protokolu
 * @property-read IstaStranica $ista_stranica Za koje stranice vrijedi kolačić
 * @property-read string $lokacija Lokacija za spremanje podatkovne sesije
 *
 * @method $this naziv(string $naziv) Naziv kolačića sesije
 * @method $this vrijeme(int $sekundi) Maksimalno vrijeme kolačića
 * @method $this putanja(string $putanja) Putanja za koju vrijedi kolačić
 * @method $this domena(string $naziv) Naziv domene ili poddomene na koji je kolačić dostupna
 * @method $this ssl(bool $ukljuceno) Da li kolačić zahtjeva SSL enkriptiranu vezu
 * @method $this http(bool $ukljuceno) Da li je kolačić dostupan samo u HTTP protokolu
 * @method $this ista_stranica(IstaStranica $ista_stranica) Za koje stranice vrijedi kolačić
 * @method $this lokacija(string $lokacija) Lokacija za spremanje podatkovne sesije
 *
 * @package Sustav\Jezgra
 */
final class Sesija extends Servis_Posluzitelj {

    /**
     * @inheritdoc
     */
    #[Zadano('sesija.servis')]
    protected ?string $servis = null;

    /**
     * ### Naziv kolačića sesije
     * @var string
     */
    #[Zadano('sesija.naziv')]
    protected string $naziv;

    /**
     * ### Maksimalno vrijeme kolačića
     * @var int
     */
    #[Zadano('sesija.vrijeme')]
    protected int $vrijeme;

    /**
     * ### Putanja za koju vrijedi kolačić
     * '/' za sve mape, npr. '/foo/' samo za 'foo' mapu
     * @var string
     */
    #[Zadano('sesija.putanja')]
    protected string $putanja;

    /**
     * ### Naziv domene ili poddomene na koji je kolačić dostupan
     * @var string
     */
    #[Zadano('sesija.domena')]
    protected string $domena;

    /**
     * ### Da li kolačić zahtjeva SSL enkriptiranu vezu
     * @var bool
     */
    #[Zadano('sesija.ssl')]
    protected bool $ssl;

    /**
     * ### Da li je kolačić dostupan samo u HTTP protokolu
     * @var bool
     */
    #[Zadano('sesija.http')]
    protected bool $http;

    /**
     * ### Za koje stranice vrijedi kolačić
     * @var IstaStranica
     */
    #[Zadano('kolacic.ista_stranica')]
    protected IstaStranica $ista_stranica;

    /**
     * ### Lokacija za spremanje podatkovne sesije
     * @var string
     */
    #[Zadano('kolacic.lokacija')]
    protected string $lokacija;

    /**
     * {@inheritDoc}
     *
     * @return Sesija_Interface Objekt Sesija servisa.
     */
    public function napravi ():object {

        return (new Servis_Kontejner($this))->dohvati();

    }

}