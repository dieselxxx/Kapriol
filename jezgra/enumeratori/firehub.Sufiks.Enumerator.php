<?php declare(strict_types = 1);

/**
 * Datoteka za enumerator za dostupne HTTP metode
 * @since 0.2.2.pre-alpha.M2
 *
 * @author Danijel Galić
 * @copyright 2021 Grafotisak d.o.o.
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\HTTP
 */

namespace FireHub\Jezgra\Enumeratori;

use FireHub\Jezgra\Enumerator;

/**
 * Enumerator za dostupne HTTP metode
 * @since 0.2.2.pre-alpha.M2
 *
 * @package Sustav\HTTP
 */
class Sufiks_Enumerator extends Enumerator {

    public const TVORNICA = 'Tvornica';
    public const GRADITELJ = 'Graditelj';
    public const KONTEJNER = 'Kontejner';
    public const SUPERKLASA = 'SuperKlasa';
    public const MODUL = 'Modul';
    public const FUNKCIJA = 'Funkcija';
    public const ABSTRAKT = 'Abstrakt';
    public const INTERFACE = 'Interface';
    public const ADAPTER = 'Adapter';
    public const ATRIBUT = 'Atribut';
    public const GENERATOR = 'Generator';
    public const ENUMERATOR = 'Enumerator';
    public const KOLEKCIJA = 'Kolekcija';
    public const KONTROLER = 'Kontroler';
    public const OSOBINA = 'Osobina';
    public const POSREDNIK = 'Posrednik';
    public const POSLUZITELJ = 'Posluzitelj';
    public const MODEL = 'Model';
    public const SERVIS = 'Servis';
    public const POSSERVIS = 'PodServis';
    public const GRESKA = 'Greska';

}