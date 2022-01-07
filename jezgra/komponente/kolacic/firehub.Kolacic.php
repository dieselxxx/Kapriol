<?php declare(strict_types = 1);

/**
 * Datoteka za upravljanje kolačićima
 * @since 0.5.2.pre-alpha.M5
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\Kolacic;

use FireHub\Jezgra\Komponente\Servis_Kontejner;
use FireHub\Jezgra\Komponente\Servis_Posluzitelj;
use FireHub\Jezgra\Atributi\Zadano;

/**
 * ### Poslužitelj za upravljanje kolačićima
 * @since 0.5.2.pre-alpha.M5
 *
 * @property-read string $naziv Naziv kolačića
 * @property-read string $vrijednost Vrijednost kolačića
 * @property-read int $vrijeme Maksimalno vrijeme kolačića
 * @property-read string $putanja Putanja za koju vrijedi kolačić
 * @property-read string $domena Naziv domene ili poddomene na koji je kolačić dostupan
 * @property-read bool $ssl Da li kolačić zahtjeva SSL enkriptiranu vezu
 * @property-read bool $http Da li je kolačić dostupan samo u HTTP protokolu
 * @property-read string $ista_stranica Za koje stranice vrijedi kolačić
 *
 * @method $this naziv(string $naziv) Naziv kolačića
 * @method $this vrijednost(string $vrijednost) Vrijednost kolačića
 * @method $this vrijeme(int $vrijeme) Maksimalno vrijeme kolačića
 * @method $this putanja(string $putanja) Putanja za koju vrijedi kolačić
 * @method $this domena(string $naziv) Naziv domene ili poddomene na koji je kolačić dostupna
 * @method $this ssl(bool $ukljuceno) Da li kolačić zahtjeva SSL enkriptiranu vezu
 * @method $this http(bool $ukljuceno) Da li je kolačić dostupan samo u HTTP protokolu
 * @method $this ista_stranica(string $ista_stranica) Za koje stranice vrijedi kolačić
 *
 * @package Sustav\Jezgra
 */
final class Kolacic extends Servis_Posluzitelj {

    /**
     * ### Naziv kolačića
     * @var string
     */
    protected string $naziv;

    /**
     * ### Vrijednost kolačića
     * @var string
     */
    protected string $vrijednost;

    /**
     * ### Maksimalno vrijeme kolačića
     * @var int
     */
    #[Zadano('kolacic.vrijeme')]
    protected int $vrijeme;

    /**
     * ### Putanja za koju vrijedi kolačić
     * '/' za sve mape, npr. '/foo/' samo za 'foo' mapu
     * @var string
     */
    #[Zadano('kolacic.putanja')]
    protected string $putanja;

    /**
     * ### Naziv domene ili poddomene na koji je kolačić dostupan
     * @var string
     */
    #[Zadano('kolacic.domena')]
    protected string $domena;

    /**
     * ### Da li kolačić zahtjeva SSL enkriptiranu vezu
     * @var bool
     */
    #[Zadano('kolacic.ssl')]
    protected bool $ssl;

    /**
     * ### Da li je kolačić dostupan samo u HTTP protokolu
     * @var bool
     */
    #[Zadano('kolacic.http')]
    protected bool $http;

    /**
     * ### Za koje stranice vrijedi kolačić
     * @var string
     */
    #[Zadano('kolacic.ista_stranica')]
    protected string $ista_stranica;

    /**
     * {@inheritDoc}
     *
     * @return Kolacic_Interface Objekt Kolačić servisa.
     */
    public function napravi ():object {

        return (new Servis_Kontejner($this))->dohvati();

    }

}