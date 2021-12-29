<?php declare(strict_types = 1);

/**
 * Datoteka za abstraktni kontejner
 *
 * Sadrži sve potrebne metode za proširavanje na child kontejnere.
 * @since 0.3.0.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Kontejner;

use FireHub\Jezgra\Atributi\Atribut;
use FireHub\Jezgra\Atributi\Singleton;
use FireHub\Jezgra\Komponente\Log\Enumeratori\Level;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;
use ReflectionClass, ReflectionMethod, ReflectionNamedType, ReflectionParameter, ReflectionAttribute, ReflectionException;
use Generator;

/**
 * ### Osnovna abstraktna klasa za kontenjere
 *
 * Sadrži sve potrebne metode za proširavanje na child kontejnere.
 * @since 0.3.0.pre-alpha.M3
 *
 * @package Sustav\Jezgra
 */
abstract class Kontejner {

    /**
     * ### Lista instanci objekata
     * @var object[]
     */
    protected static array $instance = [];

    /**
     * ### Konstruktor
     * @since 0.3.0.pre-alpha.M3
     *
     * @param string $naziv <p>
     * Naziv objekta iz kontejnera.
     * </p>
     */
    public function __construct (
        protected string $naziv
    ) {}

    /**
     * ### Dohvati novi objekt iz kontejnera
     * @since 0.3.0.pre-alpha.M3
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca objekta.
     *
     * @return object Objekt iz kontajnera.
     */
    final public function dohvati ():object {

        // ukoliko je objekt označen kao singleton vrati isti objekt
        if ($this->jeSingleton()) {

            return $this->singleton();

        }

        // napravi novu instancu objekta
        if (!$this->spremiNovuInstancu()) {

            zapisnik(Level::KRITICNO, sprintf(_('Ne mogu spremiti instancu: %s, u kontejner!'), $this->naziv));
            throw new Kontejner_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru'));

        }

        return self::$instance[$this->naziv];

    }

    /**
     * ### Uzimanje postojećeg objekta iz memorije ukoliko postoji
     * @since 0.3.0.pre-alpha.M3
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca objekta.
     *
     * @return object Objekt iz kontajnera.
     */
    final public function singleton ():object {

        // ako ne postoji instanca objekta i ne može se spremiit nova
        if (!isset(self::$instance[$this->naziv]) && !$this->spremiNovuInstancu()) {

            zapisnik(Level::KRITICNO, sprintf(_('Ne mogu spremiti instancu: %s, u kontejner!'), $this->naziv));
            throw new Kontejner_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru'));

        }

        return self::$instance[$this->naziv];

    }

    /**
     * ### Dohvati novi objekt kao generator
     * @since 0.3.0.pre-alpha.M3
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca objekta.
     *
     * @return Generator Objekt kao generator.
     */
    final public function generator ():Generator {

        yield $this->dohvati();

    }

    /**
     * ### Ovisni parametri konstruktora na objektu
     *
     * Niz ovisnih parametara konstruktora koje se trebaju
     * pokrenuti i prenijeti uz objekt.
     * @since 0.3.0.pre-alpha.M3
     *
     * @return object[] Niz objekta za parametre.
     */
    abstract protected function parameteri ():array;

    /**
     * ### Spremi instancu objekta
     * @since 0.3.0.pre-alpha.M3
     *
     * @return bool Da li je spremljena instanca objekta.
     */
    private function spremiNovuInstancu ():bool {

        if (!self::$instance[$this->naziv] = new $this->naziv(...$this->parameteri())) {

            return false;

        }

        return true;

    }

    /**
     * ### Autožica za objekt
     * @since 0.3.0.pre-alpha.M3
     *
     * @throws Kontejner_Greska Ako ne postoji objekt sa nazivom klase.
     *
     * @return object[] Lista objekata iz konstruktora.
     */
    final protected function autozicaObjekt ():array {

        return $this->autozica(
            $this->refleksijaParametriFiltar($this->refleksija()?->getConstructor()?->getParameters() ?? [])
        );

    }

    /**
     * ### Autožica za metodu
     *
     * Prikupljanje zavisnih parametara objekta iz metode te automatsko pozivanje istih.
     * @since 0.3.0.pre-alpha.M3
     *
     * @param string $metoda <p>
     * Metoda za koju je namijenjena autožica.
     * </p>
     *
     * @throws ReflectionException Ako ne postoji objekt sa nazivom klase.
     *
     * @return object[] Lista objekata iz trenutne metode.
     */
    final public function autozicaMetoda (string $metoda):array {

        return $this->autozica(
            $this->refleksijaParametriFiltar((new ReflectionMethod($this->naziv, $metoda))->getParameters())
        );

    }

    /**
     * ### Autožica
     *
     * Automatsko pokretanje objekata iz parametra pronađenih u refleksiji
     * @since 0.3.0.pre-alpha.M3
     *
     * @param ReflectionParameter[] $podatci <p>
     * Lista parametara za objekt.
     * </p>
     *
     * @return object[] Lista objekata.
     */
    private function autozica (array $podatci):array {

        return array_map(
            static function (ReflectionParameter $parametar):object {

                // tip parametra
                $tip_parametra = $parametar->getType()?->getName();

                return new ($tip_parametra);

            }, $podatci
        );

    }

    /**
     * ### Filtiranje parametara refleksije
     * @since 0.3.0.pre-alpha.M3
     *
     * @param ReflectionParameter[] $parametri <p>
     * Parameteri autožice.
     * </p>
     *
     * @return ReflectionParameter[] Filtrirani parametri.
     */
    private function refleksijaParametriFiltar (array $parametri):array {

        return array_filter(
            $parametri, // parameteri autozice
            static function ($parametar):ReflectionParameter|array {

                // tip parametra
                $tip_parametra = $parametar->getType();

                // nedozvoljeni tipovi parametra
                // tip parametra koje su instanca ReflectionUnionType ne ulaze u filtar, te se on prvi validira. Metoda getName ne postoji u ReflectionUnionType!!
                // funkcija is_subclass_of poziva sve objekta na prvom parametru, te je potrebno unaprijed filtrirati nepostojeće objekte
                if ($tip_parametra instanceof ReflectionNamedType && str_starts_with($tip_parametra->getName(), 'FireHub')) {

                    return $parametar;

                }

                return [];

            }
        );

    }

    /**
     * ### Provjera da li je trenutni objekt singleton
     * @since 0.3.0.pre-alpha.M3
     *
     * @throws Kontejner_Greska Ako ne postoji objekt sa nazivom klase ili ukoliko nije uspješno obrađen atribut
     *
     * @return bool Da li je objekt singleton.
     */
    private function jeSingleton ():bool {

        // ukoliko objekt sadrži singleton osobinu
        if (!array_key_exists(Singleton::class, $this->atributiObjekt())) {

            return false;

        }

        return true;

    }

    /**
     * ### Atributi objekta
     * @since 0.3.0.pre-alpha.M3
     *
     * @throws Kontejner_Greska Ako ne postoji objekt sa nazivom klase ili ukoliko nije uspješno obrađen atribut.
     *
     * @return Atribut[] Atributi objekta koji implementiraju Atribut interface.
     */
    private function atributiObjekt ():array {

        return $this->obradiAtribute(
            $this->refleksija()->getAttributes(Atribut::class, ReflectionAttribute::IS_INSTANCEOF)
        );

    }

    /**
     * ### Atributi metode objekta
     * @since 0.3.0.pre-alpha.M3
     *
     * @param string $metoda <p>
     * Naziv metode objekta.
     * </p>
     *
     * @throws Kontejner_Greska Ako ne postoji objekt sa nazivom klase ili ukoliko nije uspješno obrađen atribut.
     * @throws ReflectionException Ukoliko ne postoji klasa ili metoda.
     *
     * @return Atribut[] Lista atributa metode koji implementiraju Atribut interface.
     */
    final public function atributiMetoda (string $metoda):array {

        return $this->obradiAtribute(
            (new ReflectionMethod($this->naziv, $metoda))->getAttributes(Atribut::class, ReflectionAttribute::IS_INSTANCEOF)
        );

    }

    /**
     * ### Obradi atribute
     * @since 0.3.0.pre-alpha.M3
     *
     * @param ReflectionAttribute[] $atributi <p>
     * Lista atributa za obradu koji bi se trebali pozvati preko refleksije.
     * </p>
     *
     * @throws Kontejner_Greska Ukoliko nije uspješno obrađen atribut.
     *
     * @return object[] Lista atributa objekta.
     */
    private function obradiAtribute (array $atributi):array {

        try {

            $obradeni_atributi = [];
            array_walk(
                $atributi,
                function(ReflectionAttribute $atribut) use (&$obradeni_atributi):object {

                    // napravi novu instancu atributa
                    $atribut_instanca = $atribut->newInstance();

                    // obradi atribut
                    $obradi_atribut = $atribut_instanca->obradi(
                        ...$this->autozica(
                            $this->refleksijaParametriFiltar((new ReflectionMethod($atribut_instanca, 'obradi'))->getParameters())
                        )
                    );

                    if ($obradi_atribut === false) {

                        zapisnik(Level::KRITICNO, sprintf(_('Ne mogu obraditi atribut: %s u objektu: %s!'), $atribut, $this->naziv));
                        throw new Kontejner_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru'));

                    }

                    return $obradeni_atributi[$atribut_instanca::class] = $atribut_instanca;

                }
            );


        } catch (ReflectionException) {

            zapisnik(Level::KRITICNO, sprintf(_('Ne mogu obraditi atribute objekta: %s, radi greške u čitanju refleksije!'), $this->naziv));
            throw new Kontejner_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru'));

        }

        return $obradeni_atributi;

    }

    /**
     * ### Refleksija trenutnog objekta
     * @since 0.3.0.pre-alpha.M3
     *
     * @throws Kontejner_Greska Ako ne postoji objekt sa nazivom klase.
     *
     * @return ReflectionClass Reflekcijska klasa objekta.
     */
    private function refleksija ():ReflectionClass {

        try {

            $refleksija = new ReflectionClass($this->naziv);

        } catch (ReflectionException) {

            zapisnik(Level::KRITICNO, sprintf(_('Ne mogu učitati refleksiju objekta: %s!'), $this->naziv));
            throw new Kontejner_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru'));

        }

        return $refleksija;

    }

}