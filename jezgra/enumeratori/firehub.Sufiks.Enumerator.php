<?php declare(strict_types = 1);

/**
 * Datoteka za enumerator za dostupne HTTP metode
 * @since 0.6.0.alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2021 Grafotisak d.o.o.
 * @license GNU General protected License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\HTTP
 */

namespace FireHub\Jezgra\Enumeratori;

use FireHub\Jezgra\Enumerator;

/**
 * Enumerator za dostupne HTTP metode
 * @since 0.6.0.alpha.M1
 *
 * @method static self TVORNICA ()
 * @method static self GRADITELJ ()
 * @method static self KONTEJNER ()
 * @method static self SUPERKLASA ()
 * @method static self MODUL ()
 * @method static self FUNKCIJA ()
 * @method static self ABSTRAKT ()
 * @method static self INTERFACE ()
 * @method static self ADAPTER ()
 * @method static self ATRIBUT ()
 * @method static self GENERATOR ()
 * @method static self ENUMERATOR ()
 * @method static self KOLEKCIJA ()
 * @method static self KONTROLER ()
 * @method static self OSOBINA ()
 * @method static self POSREDNIK ()
 * @method static self POSLUZITELJ ()
 * @method static self MODEL ()
 * @method static self SERVIS ()
 * @method static self POSSERVIS ()
 * @method static self GRESKA ()
 *
 * @package Sustav\HTTP
 */
final class Sufiks_Enumerator extends Enumerator {

    protected const TVORNICA = 'Tvornica';
    protected const GRADITELJ = 'Graditelj';
    protected const KONTEJNER = 'Kontejner';
    protected const SUPERKLASA = 'SuperKlasa';
    protected const MODUL = 'Modul';
    protected const FUNKCIJA = 'Funkcija';
    protected const ABSTRAKT = 'Abstrakt';
    protected const INTERFACE = 'Interface';
    protected const ADAPTER = 'Adapter';
    protected const ATRIBUT = 'Atribut';
    protected const GENERATOR = 'Generator';
    protected const ENUMERATOR = 'Enumerator';
    protected const KOLEKCIJA = 'Kolekcija';
    protected const KONTROLER = 'Kontroler';
    protected const OSOBINA = 'Osobina';
    protected const POSREDNIK = 'Posrednik';
    protected const POSLUZITELJ = 'Posluzitelj';
    protected const MODEL = 'Model';
    protected const SERVIS = 'Servis';
    protected const POSSERVIS = 'PodServis';
    protected const GRESKA = 'Greska';

}