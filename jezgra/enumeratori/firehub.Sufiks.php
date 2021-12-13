<?php declare(strict_types = 1);

/**
 * Datoteka za enumerator za dostupne vrste sufiksa datoteka
 * @since 0.2.1.pre-alpha.M2
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Enumeratori;

/**
 * ### Enumerator za dostupne vrste sufiksa datoteka
 * @since 0.2.1.pre-alpha.M2
 *
 * @package Sustav\Jezgra
 */
enum Sufiks:string {

    case TVORNICA = 'Tvornica';
    case GRADITELJ = 'Graditelj';
    case KONTEJNER = 'Kontejner';
    case SUPERKLASA = 'SuperKlasa';
    case MODUL = 'Modul';
    case FUNKCIJA = 'Funkcija';
    case ABSTRAKT = 'Abstrakt';
    case INTERFACE = 'Interface';
    case ADAPTER = 'Adapter';
    case ATRIBUT = 'Atribut';
    case GENERATOR = 'Generator';
    case ENUMERATOR = 'Enumerator';
    case KOLEKCIJA = 'Kolekcija';
    case KONTROLER = 'Kontroler';
    case OSOBINA = 'Osobina';
    case POSREDNIK = 'Posrednik';
    case POSLUZITELJ = 'Posluzitelj';
    case MODEL = 'Model';
    case SERVIS = 'Servis';
    case POSSERVIS = 'PodServis';
    case GRESKA = 'Greska';

}