<?php declare(strict_types = 1);

/**
 * Datoteka koleckija za potrebe upravljanja nizovima podataka
 * @since 0.3.5.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Kolekcije;

/**
 * ### Koleckija za potrebe upravljanja nizovima podataka
 * @since 0.3.5.pre-alpha.M3
 *
 * @package Sustav\Jezgra
 */
final class Niz_Kolekcija {

    /**
     * ### Filtriranje vrijednosti niza koje nisu prazne
     * @since 0.3.5.pre-alpha.M3
     *
     * @param array $niz <p>
     * Niz za filtitranje.
     * </p>
     *
     * @return array Filtrirani niz bez praznih zapisa.
     */
    public static function filterNisuPrazni (array $niz):array {

        foreach ($niz as &$vrijednost) {

            if (is_array($vrijednost)){

                $vrijednost = self::filterNisuPrazni($vrijednost);

            }

        }

        return array_filter($niz);

    }

}