<?php declare(strict_types = 1);

/**
 * Datoteka za osnovnu abstraktnu klasu enumeratora
 * @since 0.6.0.alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra;

use FireHub\Jezgra\Greske\Greska;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;
use FireHub\Jezgra\Komponente\Log\Log;
use FireHub\Jezgra\Komponente\Log\Enumeratori\Level;
use ReflectionClass;
use ReflectionClassConstant;

/**
 * ### Osnovna abstraktna klasa enumeratora
 * @since 0.6.0.alpha.M1
 *
 * @package Sustav\Jezgra
 */
abstract class Enumerator {

    /**
     * ### Konstruktor
     * @since 0.6.0.alpha.M1
     *
     * @param string $name <p>
     * Ime enumeratora.
     * </p>
     * @param string|int $value <p>
     * Vrijednost enumeratora.
     * </p>
     */
    public function __construct (
        private string $name,
        private string|int $value
    ) {}

    /**
     * ### Prikaži sve dostupne vrijednosti enumratora
     * @since 0.6.0.alpha.M1
     *
     * @return array<string, string>
     */
    public static function cases ():array {

        return array_values(self::dohvatiKonstante());

    }

    /**
     * ### Potraži vrijednosti enumeratora.
     * @since 0.6.0.alpha.M1
     *
     * @param string $scalar <p>
     * Vrijednost enumeratora.
     * </p>
     *
     * @throws Greska Ukoliko ne postoji vrijednosti enumeratora.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     *
     * @return static Trenutni enumerator.
     */
    public static function from (string $scalar):static {

        // potraži konstantu
        $konstanta = array_search($scalar, static::dohvatiKonstante(), true);

        // ako konstanta ne postoji
        if (!$konstanta) {

            (new Log)->level(Level::KRITICNO)->poruka(sprintf(_('Ne postoji vrijednosti enumeratora: %s'), $konstanta))->napravi()->posalji();
            throw new Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru.'));

        }

        $name = $konstanta;
        $value = $scalar;

        return new static($name, $value);

    }

    /**
     * ### Pokušaj potražiti vrijednosti enumeratora.
     * @since 0.6.0.alpha.M1
     *
     * @param string $scalar <p>
     * Vrijednost enumeratora.
     * </p>
     *
     * @return static|null Trenutni enumerator ili null.
     */
    public static function tryFrom (string $scalar):?static {

        // potraži konstantu
        $konstanta = array_search($scalar, static::dohvatiKonstante(), true);

        // ako konstanta ne postoji
        if (!$konstanta) {

            return null;

        }

        $name = $konstanta;
        $value = $scalar;

        return new static($name, $value);

    }

    /**
     * ### Pretraži public konstante u enumeratoru
     * @since 0.6.0.alpha.M1
     *
     * @return string[]
     */
    private static function dohvatiKonstante ():array {

        return (new ReflectionClass(static::class))->getConstants(ReflectionClassConstant::IS_PUBLIC);

    }

}