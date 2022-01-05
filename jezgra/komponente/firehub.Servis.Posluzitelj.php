<?php declare(strict_types = 1);

/**
 * Osnovna datoteka za sve poslužitelje servisa
 * @since 0.3.0.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente;

use FireHub\Jezgra\Atributi\Atribut;
use FireHub\Jezgra\Komponente\Log\Enumeratori\Level;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;
use FireHub\Jezgra\Kontejner\Greske\Servis_Posluzitelj_Greska;
use ReflectionClass, ReflectionAttribute;
use Generator;

/**
 * ### Osnovna abstraktna klasa za sve poslužitelje servisa
 *
 * - Protected metode su metode koje se mogu samo pročitati, te se upisuje property-read DocBlock za njih radi čitanja IDEA-a. Ovo omogućava magična metoda __get.
 * - Public metode su metode koje se mogu promijeniti bilo gdje unutar aplikacije, uključujući i servis pripadajućeg poslužitelja.
 * - Private metode su namijenjene samo za pripadajućeg poslužitelja.
 * - Sva protected i public svojstva automatski dobivaju metode za osnovno postavljanje svojstva, Za njih se upisuje method DocBlock radi čitanja IDEA-a.
 * - Statičke metode servisa se mogu pozivati preko poslužitelja ili preko servisa.
 * @since 0.3.0.pre-alpha.M3
 *
 * @method $this servis(string $servis) Postavi servis na poslužitelju
 *
 * @package Sustav\Jezgra
 */
abstract class Servis_Posluzitelj {

    /**
     * ### Ručno postavljeni servis na poslužitelju
     * @var ?string $servis
     */
    protected ?string $servis = null;

    /**
     * ### Konstruktor
     * @since 0.3.5.pre-alpha.M3
     *
     * @throws Servis_Posluzitelj_Greska Ukoliko se ne mogu postaviti zadana svojstva poslužitelja.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Konfiguracije.
     */
    public function __construct () {

        // postavi zadana svojstva poslužitelja
        if (!$this->postaviZadanaSvojstva()) {

            zapisnik(Level::KRITICNO, sprintf(_('Ne mogu postaviti zadana svojstva poslužitelja: %s!'), static::class));
            throw new Servis_Posluzitelj_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru'));

        }

    }

    /**
     * ### Pročitaj ručno postavljeni servis na poslužitelju
     *
     * Ručno postavljeni servis ukoliko je napisan prilikom
     * pozivanja poslužitelja.
     * @since 0.3.0.pre-alpha.M3
     *
     * @return ?string FQN naziv servisa.
     */
    public function postavljeniServis ():?string {

        return $this->servis;

    }

    /**
     * ### Napravi servis
     * @since 0.3.0.pre-alpha.M3
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca objekta.
     *
     * @return Servis_Interface Objekt servisa.
     */
    public function napravi ():object {

        return (new Servis_Kontejner($this))->dohvati();

    }

    /**
     * ### Lijeno čitanje liste servisa
     * @since 0.3.0.pre-alpha.M3
     *
     * @param static[] $posluzitelji <p>
     * Lista ovog poslužitelja.
     * </p>
     *
     * @throws Servis_Posluzitelj_Greska Ukoliko ne postoji ili nije inicializirano svojstvo u poslužitelju.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca objekta.
     *
     * @return object[] Lista servisa.
     */
    final public static function lijeno (array $posluzitelji):array {

        foreach (self::generator($posluzitelji) as $posluzitelj) {

            if ($posluzitelj !== false) {

                $servisi[] = $posluzitelj;

            }

        }

        return $servisi ?? [];

    }

    /**
     * ### Automatsko postavljanje statičkih metoda servisa
     * @since 0.3.0.pre-alpha.M3
     *
     * @param string $metoda <p>
     * Naziv metode.
     * </p>
     * @param array $argumenti <p>
     * Argumenti metode.
     * </p>
     *
     * @throws Servis_Posluzitelj_Greska Ukoliko ne postoji svojstvo u poslužitelju.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     *
     * @return self Poslužitelj objekt.
     */
    public function __call (string $metoda, array $argumenti):self {

        // ako ne postoji svojstvo
        if (!property_exists($this, $metoda)) {

            zapisnik(Level::KRITICNO, sprintf(_('Ne postoji metoda: %s, u poslužitelju servisa: %s!'), $metoda, static::class));
            throw new Servis_Posluzitelj_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru'));

        }

        array_walk(
            $argumenti,
            function ($argument) use ($metoda):mixed {
                return $this->$metoda = $argument;
            }
        );

        return $this;

    }

    /**
     * ### Automatsko postavljanje statičkih metoda servisa
     * @since 0.3.0.pre-alpha.M3
     *
     * @param string $metoda <p>
     * Naziv metode.
     * </p>
     * @param array $argumenti <p>
     * Argumenti metode.
     * </p>
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca objekta.
     *
     * @return mixed Vrijednost iz statičke metode servisa.
     */
    public static function __callStatic (string $metoda, array $argumenti):mixed {

        return (new Servis_Kontejner(new static()))->singleton()::$metoda(...$argumenti);

    }

    /**
     * ### Pročitaj svojstvo poslužitelja
     *
     * Daje servisima mogućnost da čitaju protected atribute svojih poslužitelja.
     * @since 0.3.0.pre-alpha.M3
     *
     * @param string $svojstvo_naziv <p>
     * Naziv svojstva.
     * </p>
     *
     * @throws Servis_Posluzitelj_Greska Ukoliko ne postoji ili nije inicializirano svojstvo u poslužitelju.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     *
     * @return mixed Vrijednost svojstva.
     */
    public function &__get (string $svojstvo_naziv):mixed {

        if (!isset($this->$svojstvo_naziv)) {

            zapisnik(Level::KRITICNO, sprintf(_('Ne postoji svojstvo: %s, u poslužitelju servisa: %s!'), $svojstvo_naziv, static::class));
            throw new Servis_Posluzitelj_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru'));

        }

        return $this->$svojstvo_naziv;

    }

    /**
     * ### Generator
     * @since 0.3.0.pre-alpha.M3
     *
     * @param static[] $posluzitelji <p>
     * Lista ovog poslužitelja.
     * </p>
     *
     * @throws Servis_Posluzitelj_Greska Ukoliko poslužitelj nije instanca ovog poslužitelja.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     *
     * @return Generator Objekt generatora.
     */
    private static function generator (array $posluzitelji):Generator {

        foreach ($posluzitelji as $posluzitelj) {

            if (!$posluzitelj instanceof static) {

                zapisnik(Level::KRITICNO, sprintf(_('Poslužitelj: %s, nije instanca: %s!'), $posluzitelj, static::class));
                throw new Servis_Posluzitelj_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru'));

            }

            yield from (new Servis_Kontejner($posluzitelj))->generator();

        }

    }

    /**
     * ### Refleksija trenutnog objekta
     * @since 0.3.5.pre-alpha.M3
     *
     * @return ReflectionClass Reflekcijska klasa objekta.
     */
    private function refleksija ():ReflectionClass {

        return new ReflectionClass(static::class);

    }

    /**
     * ### Postavi zadana svojstva poslužitelja
     * @since 0.3.5.pre-alpha.M3
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Konfiguracije.
     *
     * @return bool True ako su postavljena zadana svojstva, False u suprotnome.
     */
    private function postaviZadanaSvojstva ():bool {

        $svojstva = $this->refleksija()->getProperties();

        return array_walk(
            $svojstva,
            function ($svojstvo) {

                $atributi = $svojstvo->getAttributes(Atribut::class, ReflectionAttribute::IS_INSTANCEOF);

                array_walk(
                    $atributi,
                    function(ReflectionAttribute $atribut) use ($svojstvo):bool {

                        // napravi novu instancu atributa
                        $atribut_instanca = $atribut->newInstance();

                        // dohvati naziv svojstva
                        $naziv_svojstva = $svojstvo->getName();

                        // dohvati vrijednosti iz konfiguracije
                        $vrijednost_konfiguracije = konfiguracija($atribut_instanca->vrijednost);

                        // postavi vrijednost ukoliko postoji vrijednost iz konfiguracije
                        if (!is_null($vrijednost_konfiguracije)) {
                            $this->$naziv_svojstva = $vrijednost_konfiguracije;
                        }

                        // obradi atribut
                        return $atribut_instanca->obradi();

                    }
                );

            }
        );

    }

}