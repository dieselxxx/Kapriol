<?php declare(strict_types = 1);

/**
 * Datoteka za log poslužitelja
 * @since 0.3.1.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\Log;

use FireHub\Jezgra\Komponente\Servis_Kontejner;
use FireHub\Jezgra\Komponente\Servis_Posluzitelj;
use FireHub\Jezgra\Komponente\Log\Servisi\Dostavljac;
use FireHub\Jezgra\Komponente\Log\Enumeratori\Level;
use Throwable;

/**
 * ### Poslužitelj za log
 * @since 0.3.1.pre-alpha.M3
 *
 * @property-read array $dostavljaci Lista dostavljača na koje se log zapisuje
 * @property-read Level $level Level log zapisa
 * @property-read string $poruka Poruka log zapisa
 * @property-read int $kod Unikatni kod log zapisa
 * @property-read ?Throwable $greska Greške koje dolaze preko Throwable interface-a
 *
 * @method $this level(Level $level) Level log zapisa
 * @method $this poruka(string $poruka) Poruka loga zapisa
 * @method $this kod(int $kod) Unikatni kod log zapisa
 * @method $this greska(Throwable $greska) Greške koje dolaze preko Throwable interface-a
 *
 * @package Sustav\Jezgra
 */
final class Log extends Servis_Posluzitelj {

    /**
     * @var Dostavljac[]
     */
    protected array $dostavljaci = [];

    /**
     * ### Level log zapisa
     * @var Level
     */
    protected Level $level = Level::BILJESKA;

    /**
     * ### Poruka log zapisa
     * @var string
     */
    protected string $poruka = '';

    /**
     * ### Unikatni kod log zapisa
     * @var int
     */
    protected int $kod = 0;

    /**
     * ### Greške koje dolaze preko Throwable interface-a
     * @var ?Throwable
     */
    protected ?Throwable $greska = null;

    /**
     * ### Kontruktor
     * @since 0.3.1.pre-alpha.M3
     */
    public function __construct () {

        // zadane opcije
        $this->dostavljaci($this->level->dostavljaci());

    }

    /**
     * ### Lista dostavljača na koje se log zapisuje
     * @since 0.3.1.pre-alpha.M3
     *
     * @param string[] $lista <p>
     * Lista FQN naziva dostavljača na koje se log zapisuje.
     * </p>
     *
     * @return $this Trenutni objekt.
     */
    public function dostavljaci (array $lista):self {

        $this->dostavljaci = array_map(
            function (string $dostavljac):Dostavljac {

                return new $dostavljac;

            },
            $lista
        );

        return $this;

    }

    /**
     * {@inheritDoc}
     *
     * @return Log_Interface Objekt Log servisa.
     */
    public function napravi ():object {

        return (new Servis_Kontejner($this))->dohvati();

    }

}