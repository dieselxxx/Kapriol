<?php declare(strict_types = 1);

/**
 * Slika
 * @since 0.1.1.pre-alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2022 Kapriol Web Trgovina
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Aplikacija\Kontroler
 */

namespace FireHub\Aplikacija\Kapriol\Kontroler;

use FireHub\Jezgra\Kontroler\Kontroler;
use FireHub\Jezgra\Sadrzaj\Sadrzaj;
use FireHub\Jezgra\Komponente\Slika\Slika;
use FireHub\Jezgra\HTTP\Atributi\Zaglavlja;
use FireHub\Jezgra\HTTP\Enumeratori\Vrsta;
use FireHub\Jezgra\HTTP\Enumeratori\Predmemorija;
use FireHub\Jezgra\Komponente\Slika\Slika_Interface;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;

/**
 * ### Slika
 *
 * @since 0.1.1.pre-alpha.M1
 *
 * @package Aplikacija\Kontroler
 */
final class Slika_Kontroler extends Kontroler {

    /**
     * ### index
     * @since 0.1.1.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function index ():Sadrzaj {

        return sadrzaj();

    }

    /**
     * ### Mala slika
     * @since 0.1.1.pre-alpha.M1
     *
     * @param string $kontroler [optional] <p>
     * Trenutni kontroler.
     * </p>
     * @param string $metoda [optional] <p>
     * Trenutna metoda.
     * </p>
     * @param string $slika [optional] <p>
     * Trenutna slika.
     * </p>
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Slike.
     *
     * @return Slika_Interface Objekt slike.
     */
    #[Zaglavlja(vrsta: Vrsta::JPEG, predmemorija: [Predmemorija::JAVNO])]
    public function malaSlika (string $kontroler = '', string $metoda = '', string $slika = ''):Slika_Interface {

        return (new Slika())->slika(FIREHUB_ROOT.'web\kapriol\resursi\grafika\artikli\\'.$slika)->dimenzije(300, 400)->napravi();

    }

}