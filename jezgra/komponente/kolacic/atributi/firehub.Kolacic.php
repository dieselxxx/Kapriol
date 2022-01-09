<?php declare(strict_types = 1);

/**
 * Datoteka za atribut kolačića
 * @since 0.5.2.pre-alpha.M5
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\Kolacic\Atributi;

use FireHub\Jezgra\Atributi\Atribut;;
use FireHub\Jezgra\Komponente\Kolacic\Kolacic as Kolacic_Servis;
use FireHub\Jezgra\Komponente\Kolacic\Enumeratori\IstaStranica;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;
use Attribute;

/**
 * ### Atribut kolačića
 * @since 0.5.2.pre-alpha.M5
 *
 * @package Sustav\Jezgra
 */
#[Attribute(Attribute::TARGET_METHOD)]
final class Kolacic implements Atribut {

    /**
     * ### Konstruktor
     * @since 0.5.2.pre-alpha.M5
     *
     * @param string $naziv <p>
     * Naziv kolačića.
     * </p>
     * @param string $vrijednost <p>
     * Vrijednost kolačića.
     * </p>
     * @param ?int $vrijeme [optional] <p>
     * Maksimalno vrijeme kolačića.
     * </p>
     * @param ?string $putanja [optional] <p>
     * Putanja za koju vrijedi kolačić.
     * </p>
     * @param ?string $domena [optional] <p>
     * Naziv domene ili poddomene na koji je kolačić dostupan.
     * </p>
     * @param ?bool $ssl [optional] <p>
     * Da li kolačić zahtjeva SSL enkriptiranu vezu.
     * </p>
     * @param ?bool $http [optional] <p>
     * Da li je kolačić dostupan samo u HTTP protokolu.
     * </p>
     * @param ?IstaStranica $ista_stranica [optional] <p>
     * Za koje stranice vrijedi kolačić.
     * </p>
     */
    public function __construct (
        private string $naziv,
        private string $vrijednost,
        private ?int $vrijeme = null,
        private ?string $putanja = null,
        private ?string $domena = null,
        private ?bool $ssl = null,
        private ?bool $http = null,
        private ?IstaStranica $ista_stranica = null
    ) {}

    /**
     * {@inheritDoc}
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Konfiguracije.
     */
    public function obradi (Kolacic_Servis $kolacic = null):bool {

        return $kolacic
            ->naziv($this->naziv)
            ->vrijednost($this->vrijednost)
            ->vrijeme($this->vrijeme ?? konfiguracija('kolacic.vrijeme'))
            ->putanja($this->putanja ?? konfiguracija('kolacic.putanja'))
            ->domena($this->domena ?? konfiguracija('kolacic.domena'))
            ->ssl($this->ssl ?? konfiguracija('kolacic.ssl'))
            ->http($this->http ?? konfiguracija('kolacic.http'))
            ->ista_stranica($this->ista_stranica ?? konfiguracija('kolacic.ista_stranica'))
            ->napravi()->spremi();

    }

}