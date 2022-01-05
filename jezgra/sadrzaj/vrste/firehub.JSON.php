<?php declare(strict_types = 1);

/**
 * Datoteka za JSON sadržaj
 * @since 0.4.4.pre-alpha.M4
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Sadrzaj\Vrste;

use FireHub\Jezgra\Sadrzaj\Vrsta_Interface;
use JsonException;

/**
 * ### Klasa ya JSON sadržaj
 * @since 0.4.4.pre-alpha.M4
 *
 * @package Sustav\Sadrzaj
 */
final class JSON implements Vrsta_Interface {

    /**
     * @inheritDoc
     */
    public function __construct (
        private array $podatci
    ) {}

    /**
     * {@inheritDoc}
     *
     * @throws JsonException Ukoliko se dogodila greška sa čitanjem JSON formata.
     */
    public function ispisi ():string {

        return json_encode(
            $this->podatci,
            JSON_THROW_ON_ERROR
        );

    }

}