<?php declare(strict_types = 1);

/**
 * Datoteka za enumerator za dostupne levela log-a
 * @since 0.3.1.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\Log\Enumeratori;

use FireHub\Jezgra\Komponente\Log\Servisi\Dostavljac\Ispisi;

/**
 * ### Enumerator za dostupne levela log-a
 * @since 0.3.1.pre-alpha.M3
 *
 * @package Sustav\Jezgra
 */
enum Level:int {

    /**
     * ### Sve detaljne informacije korisnika i aplikacije
     */
    case DEBUG = 10;

    /**
     * ### Bitniji događaji u radu korisnika
     */
    case INFO = 20;

    /**
     * ### Bilješke o bitnijim događajima unutar aplikacije
     */
    case BILJESKA = 30;

    /**
     * ### Upozorenja korisnika, ne zaustavljaju daljnu izvedbu aplikacije
     */
    case UPOZORENJE = 40;

    /**
     * ### Greške aplikacije koje ne zaustavljaju daljnju izvedbu procesa
     */
    case GRESKA = 50;

    /**
     * ### Kritični događaji aplikacije koji zaustavljaju rad komponente aplikacije
     */
    case KRITICNO = 60;

    /**
     * ### Komponente koje zaustavljaju aplikaciju (baza ne radi, aplikacija ne reagira)
     */
    case UZBUNA = 70;

    /**
     * ### Hitna uzbuna za posebne greške
     */
    case HITNO = 80;

    /**
     * ### Lista dostavljaca enumeratora
     * @since 0.3.1.pre-alpha.M3
     *
     * @return string[] Lista FQN naziva dostavljača.
     *
     * @todo Prebaciti opciju u konfiguraciju
     */
    public function dostavljaci ():array {

        return match ($this) {
            self::BILJESKA => [Ispisi::class],
            self::DEBUG => [Ispisi::class],
            self::INFO => [Ispisi::class],
            self::UPOZORENJE => [Ispisi::class],
            self::GRESKA => [Ispisi::class],
            self::KRITICNO => [Ispisi::class],
            self::UZBUNA => [Ispisi::class],
            self::HITNO => [Ispisi::class],
            default => [Ispisi::class]
        };

    }

}