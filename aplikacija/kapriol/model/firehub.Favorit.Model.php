<?php declare(strict_types = 1);

/**
 * Favorit model
 * @since 0.1.2.pre-alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2022 Kapriol Web Trgovina
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Aplikacija\Model
 */

namespace FireHub\Aplikacija\Kapriol\Model;

/**
 * ### Favorit model
 * @since 0.1.2.pre-alpha.M1
 *
 * @package Aplikacija\Model
 */
final class Favorit_Model extends Master_Model {

    /**
     * @param int $id
     */
    public function dodaj (int $id) {

        if (!isset($this->sesija->procitaj('favorit')[$id])) {

            $this->sesija->dodaj('favorit', (string)$id, $id);

        }

    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function izbrisi (string $id = ''):bool {

        if (!isset($this->sesija->procitaj('favorit')[$id])) {

            return false;

        }

        $this->sesija->izbrisiNiz('favorit', $id);

        return true;

    }

    /**
     * @return int
     */
    public function artikli ():int {

        if ($this->sesija->procitaj('favorit')) {

            return count($this->sesija->procitaj('favorit'));

        }

        return 0;

    }

}