<?php declare(strict_types = 1);

/**
 * Datoteka za poslužitelja slika
 * @since 0.6.1.alpha.M6
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\Slika;

use FireHub\Jezgra\Komponente\Servis_Kontejner;
use FireHub\Jezgra\Komponente\Servis_Posluzitelj;
use FireHub\Jezgra\Komponente\Slika\Enumeratori\Vrsta;
use FireHub\Jezgra\Atributi\Zadano;

/**
 * ### Poslužitelj za slike
 * @since 0.6.1.alpha.M6
 *
 * @property-read string $slika Puna putanja do slike
 * @property-read Vrsta $vrsta Vrsta slike
 * @property-read int $visina Visina slike
 * @property-read int $sirina Širina slike
 * @property-read int $kvaliteta Kvaliteta slike u postocima, od 1 do 100
 *
 * @method $this slika (string $slika) Puna putanja do slike
 * @method $this vrsta (Vrsta $vrsta) Vrsta slike
 * @method $this kvaliteta (int $postotak) Kvaliteta slike u postocima, od 1 do 100
 */
final class Slika extends Servis_Posluzitelj {

    /**
     * @inheritdoc
     */
    #[Zadano('slika.servis')]
    protected ?string $servis = null;

    /**
     * ### Puna putanja do slike
     * @var string
     */
    protected string $slika;

    /**
     * ### Vrsta slike
     * @var Vrsta
     */
    protected Vrsta $vrsta = Vrsta::JPEG;

    /**
     * ### Visina slike
     * @var int
     */
    protected int $visina = 300;

    /**
     * ### Širina slike
     * @var int
     */
    protected int $sirina = 300;

    /**
     * ### Kvaliteta slike u postocima, od 1 do 100
     * @var int
     */
    #[Zadano('slika.kompresija')]
    protected int $kvaliteta;

    /**
     * ### Širina i visina slike
     * @since 0.6.0.alpha.M1
     *
     * @param int $visina <p>
     * Broj redaka koje odabiremo.
     * </p>
     * @param int $sirina <p>
     * Pomak od kojeg se limitiraju zapisi.
     * </p>
     *
     * @return $this Instanca Slike.
     */
    public function dimenzije (int $visina, int $sirina):self {

        $this->visina = $visina;
        $this->sirina = $sirina;

        return $this;

    }

    /**
     * {@inheritDoc}
     *
     * @return Slika_Interface Objekt Slika servisa.
     */
    public function napravi ():object {

        return (new Servis_Kontejner($this))->dohvati();

    }

}