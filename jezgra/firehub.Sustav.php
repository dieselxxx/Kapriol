<?php declare(strict_types = 1);

/**
 * Osnovna datoteka za pokretanje sustava
 *
 * Datoteka sadrži sve definicije, konstante i zavisne komponente potrebne
 * za pokretanje sustava.
 * @since 0.2.2.pre-alpha.M2
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra;

use FireHub\Jezgra\Enumeratori\Kernel AS Kernel_Enumerator;
use FireHub\Jezgra\HTTP\Zahtjev as HTTP_Zahtjev;
use Error;

/**
 * ### Sistemska definicija za razdvajanje mapa u strukturi
 *
 * Automatski konvertira "\" i "/" na različitim operativnim sustavima.
 * @since 0.2.2.pre-alpha.M2
 *
 * @name string
 */
define('RAZDJELNIK_MAPE', DIRECTORY_SEPARATOR);

/**
 * ### Putanja do početne mapa sustava
 * @since 0.2.2.pre-alpha.M2
 *
 * @name string
 */
define('FIREHUB_ROOT', realpath($_SERVER['DOCUMENT_ROOT']) . RAZDJELNIK_MAPE);

/**
 * ### Osnovna klasa za pokretanje sustava
 * @since 0.2.2.pre-alpha.M2
 *
 * @package Sustav\Jezgra
 */
final class Sustav {

    /**
     * ### Pokreni sustav
     *
     * Ova metoda služi za pokretanje sustava i jedina je
     * metoda izložena datotekama koje pokreću sustav.
     * @since 0.2.2.pre-alpha.M2
     *
     * @param Kernel_Enumerator $kernel <p>
     * Odabrai Kernel iz enumeratora.
     * </p>
     *
     * @return Kernel Instanca Kernela.
     */
    public function pokreni (Kernel_Enumerator $kernel):Kernel {

        return $this->autoload()
            ->kernel($kernel);

    }

    /**
     * ### Učitaj autoload datoteku
     *
     * Datoteka sadrži definicije i funkcije za automatsko učitavanje svih
     * pozvanih objekata.
     * @since 0.2.2.pre-alpha.M2
     *
     * @throws Error Ako se ne može učitati autoload datoteka.
     *
     * @return $this Trenutni objekt.
     */
    private function autoload ():self {

        if (!include(FIREHUB_ROOT . 'jezgra' . RAZDJELNIK_MAPE . 'firehub.Autoload.php')) {

            throw new Error(_('Ne mogu pokrenuti sustav, obratite se administratoru.'));

        }

        return $this;

    }

    /**
     * ### Pokreni Kernel
     * @since 0.2.3.pre-alpha.M2
     *
     * @param Kernel_Enumerator $kernel <p>
     * Odabrai Kernel iz enumeratora.
     * </p>
     *
     * @return Kernel Instanca Kernela.
     */
    private function kernel (Kernel_Enumerator $kernel):Kernel {

        // pokreni Kernel
        return (new $kernel->value (
            new ($kernel->zahtjev()))
        )->pokreni();

    }

}