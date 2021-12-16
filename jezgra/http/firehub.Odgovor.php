<?php declare(strict_types = 1);

/**
 * Datoteka za HTTP odgovor
 *
 * Ova datoteka prikuplja i obrađuje sve informacije o HTTP odgovoru.
 * @since 0.2.6.pre-alpha.M2
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\HTTP
 */

namespace FireHub\Jezgra\HTTP;

use FireHub\Jezgra\Odgovor as Odgovor_Interface;
use FireHub\Jezgra\HTTP\Enumeratori\StatusKod as HTTP_StatusKod;
use FireHub\Jezgra\HTTP\Enumeratori\Vrsta as HTTP_Vrsta;
use FireHub\Jezgra\HTTP\Enumeratori\Predmemorija as HTTP_Predmemorija;
use FireHub\Jezgra\HTTP\Greske\Odgovor_Greska as HTTP_Odgovor_Greska;

/**
 * ### HTTP odgovor
 *
 * Klasa namjenjana za upravljenje i obrađivanje svih
 * HTTP odgovora od sustava i aplikacije.
 * @since 0.2.6.pre-alpha.M2
 *
 * @package Sustav\HTTP
 */
final class Odgovor implements Odgovor_Interface {

    /**
     * ### Konstruktor
     * @since 0.2.6.pre-alpha.M2
     *
     * @param string $datoteka [optional] <p>
     * Trenutna radna datoteka.
     * </p>
     * @param HTTP_StatusKod $kod [optional] <p>
     * Status kod HTTP odgovora.
     * </p>
     * @param HTTP_Vrsta $vrsta [optional] <p>
     * Media tip vrsta HTTP odgovora.
     * </p>
     * @param string $karakteri [optional] <p>
     * Standard enkodiranje karaktera.
     * </p>
     * @param string $jezik [optional] <p>
     * Skraćeni naziv jezika.
     * </p>
     * @param HTTP_Predmemorija[] $predmemorija [optional] <p>
     * Lista naredbi za predmemoriju.
     * https://www.iana.org/assignments/language-subtag-registry/language-subtag-registry.
     * </p>
     * @param int $predmemorija_vrijeme [optional] <p>
     * Maksimalno vrijeme trajanja predmemorije.
     * </p>
     * @param string $sadrzaj [optional] <p>
     * Sadržaj HTTP odgovora.
     * </p>
     */
    public function __construct (
        public readonly string $datoteka = '',
        public readonly HTTP_StatusKod $kod = HTTP_StatusKod::HTTP_OK,
        public readonly HTTP_Vrsta $vrsta = HTTP_Vrsta::HTML,
        public readonly string $karakteri = 'UTF-8',
        public readonly string $jezik = 'hr',
        public readonly array $predmemorija = [HTTP_Predmemorija::BEZ_SPREMANJA, HTTP_Predmemorija::BEZ_PREDMEMORIJE, HTTP_Predmemorija::MORA_PONOVNO_POTVRDITI],
        public readonly int $predmemorija_vrijeme = 31536000,
        public readonly string $sadrzaj = ''
    ) {}

    /**
     * @inheritDoc
     */
    public function sadrzaj ():string {

        return '<b>'.round(memory_get_peak_usage()/1048576, 2) . ' mb</b>';

    }

}