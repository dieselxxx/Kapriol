<?php declare(strict_types=1);

/**
 * Datoteka za interface dostavljača log-a
 * @since 0.3.1.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\Log\Servisi;

/**
 * ### Interface za dostavljača log-a
 * @since 0.3.1.pre-alpha.M3
 *
 * @package Sustav\Jezgra
 */
interface Dostavljac {

    /**
     * ### Otvori dostavljača
     * @since 0.3.1.pre-alpha.M3
     *
     * @return $this Instanca Dostavljaca.
     */
    public function otvori ():self;

    /**
     * ### Zapiši zapis u dostavljača
     * @since 0.3.1.pre-alpha.M3
     *
     * @param string $vrsta_objekta <p>
     * Vrsta greške. Može biti FQN objekta ili ručni naziv greške.
     * </p>
     * @param int $level <p>
     * Level log zapisa.
     * </p>
     * @param string $level_naziv <p>
     * Naziv levela log zapisa.
     * </p>
     * @param int $kod <p>
     * Unikatni kod log zapisa.
     * </p>
     * @param string $datoteka <p>
     * Naziv datoteke u kojoj je log nastao.
     * </p>
     * @param int $linija <p>
     * Linija u datoteci u kojoj je log nastao.
     * </p>
     * @param string $poruka <p>
     * Poruka log zapisa
     * </p>
     * @param array $debug <p>
     * Niz radnji koje su dovele do greške.
     * </p>
     *
     * @return $this Instanca Dostavljaca.
     */
    public function zapisi (string $vrsta_objekta, int $level, string $level_naziv, int $kod, string $datoteka, int $linija, string $poruka, array $debug):self;

    /**
     * ### Zatvori dostavljača
     * @since 0.3.1.pre-alpha.M3
     *
     * @return $this Instanca Dostavljaca.
     */
    public function zatvori ():self;

}