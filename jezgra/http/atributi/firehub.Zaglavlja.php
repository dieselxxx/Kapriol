<?php declare(strict_types = 1);

/**
 * Datoteka za atribut HTTP zaglavlja
 * @since 0.4.3.pre-alpha.M4
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\HTTP
 */

namespace FireHub\Jezgra\HTTP\Atributi;

use FireHub\Jezgra\HTTP\Enumeratori\StatusKod as HTTP_StatusKod;
use FireHub\Jezgra\HTTP\Enumeratori\Vrsta as HTTP_Vrsta;
use FireHub\Jezgra\HTTP\Enumeratori\Predmemorija as HTTP_Predmemorija;
use FireHub\Jezgra\Atributi\Atribut;
use Attribute;

/**
 * ### Atribut HTTP zaglavlja
 * @since 0.4.3.pre-alpha.M4
 *
 * @package Sustav\HTTP
 */
#[Attribute(Attribute::TARGET_METHOD)]
final class Zaglavlja implements Atribut {

    /**
     * ### Konstruktor
     * @since 0.4.3.pre-alpha.M4
     *
     * @param ?HTTP_StatusKod $kod [optional] <p>
     * Status kod HTTP odgovora.
     * </p>
     * @param ?HTTP_Vrsta $vrsta [optional] <p>
     * Media tip vrsta HTTP odgovora.
     * </p>
     * @param ?string $karakteri [optional] <p>
     * Standard enkodiranje karaktera.
     * </p>
     * @param ?string $jezik [optional] <p>
     * Skraćeni naziv jezika.
     * </p>
     * @param ?HTTP_Predmemorija[] $predmemorija [optional] <p>
     * Lista naredbi za predmemoriju.
     * https://www.iana.org/assignments/language-subtag-registry/language-subtag-registry.
     * </p>
     * @param ?int $predmemorija_vrijeme [optional] <p>
     * Maksimalno vrijeme trajanja predmemorije.
     * </p>
     */
    public function __construct (
        public readonly ?HTTP_StatusKod $kod = null,
        public readonly ?HTTP_Vrsta $vrsta = null,
        public readonly ?string $karakteri = null,
        public readonly ?string $jezik = null,
        public readonly ?array $predmemorija = null,
        public readonly ?int $predmemorija_vrijeme = null
    ) {}

    /**
     * @inheritDoc
     */
    public function obradi ():bool {

        return true;

    }

}