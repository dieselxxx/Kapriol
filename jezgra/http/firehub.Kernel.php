<?php declare(strict_types = 1);

/**
 * Osnovna datoteka za pokretanje HTTP upita
 * @since 0.2.3.pre-alpha.M2
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\HTTP
 */

namespace FireHub\Jezgra\HTTP;

use FireHub\Jezgra\Kernel as OsnovniKernel;
use FireHub\Jezgra\Zahtjev;
use FireHub\Jezgra\HTTP\Zahtjev as HTTP_Zahtjev;
use FireHub\Jezgra\HTTP\Odgovor as HTTP_Odgovor;
use FireHub\Jezgra\Komponente\Datoteka\Datoteka;
use FireHub\Jezgra\Komponente\Log\Log;
use FireHub\Jezgra\Komponente\Log\Servisi\AutoPosalji;
use FireHub\Jezgra\Komponente\Log\Enumeratori\Level;
use FireHub\Jezgra\Greske\Greska;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;
use FireHub\Jezgra\Komponente\Datoteka\Greske\Datoteka_Greska;
use Throwable;

/**
 * ### Osnovna klasa Kernel za pokretanje HTTP upita
 * @since 0.2.3.pre-alpha.M2
 *
 * @package Sustav\HTTP
 */
final class Kernel extends OsnovniKernel {

    /**
     * {@inheritDoc}
     *
     * @param HTTP_Zahtjev $zahtjev <p>
     * Zahtjev.
     * </p>
     */
    public function __construct (private Zahtjev $zahtjev) {}

    /**
     * {@inheritDoc}
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     */
    public function pokreni ():HTTP_Odgovor {

        try {

            return $this
                ->posrednici(include FIREHUB_ROOT . 'konfiguracija' . RAZDJELNIK_MAPE . 'posrednici.php', 'http')
                ->pomagaci()
                ->ucitajEnv(FIREHUB_ROOT . '.env')
                ->postaviAplikaciju()
                ->posrednici(include APLIKACIJA_ROOT . 'konfiguracija' . RAZDJELNIK_MAPE . 'posrednici.php', 'http')
                ->ucitajEnv(APLIKACIJA_ROOT . '.env')
                ->konfiguracija()
                ->odgovor();

        } catch (Throwable $objekt) {

            (new Log)->servis(AutoPosalji::class)->greska($objekt)->napravi()->posalji();

        }

    }

    /**
     * ### Postavljanje zadane aplikacije
     * @since 0.3.5.pre-alpha.M3
     *
     * @name string APLIKACIJA
     * @name string APLIKACIJA_ROOT
     *
     * @throws Greska Ukoliko se ne može pročitati zadana aplikacija.
     *
     * @return $this Instanca Kernel-a.
     */
    private function postaviAplikaciju ():self {

        define('APLIKACIJA', strtolower($this->trenutnaAplikacija()));

        define('APLIKACIJA_ROOT', FIREHUB_ROOT . 'aplikacija' . RAZDJELNIK_MAPE . APLIKACIJA . RAZDJELNIK_MAPE);

        return $this;

    }

    /**
     * ### Informacija o trenutnoj aplikacija u sustavu
     *
     * Zadana aplikacija, ukoliko je popunjena, otvara na početnom URL
     * dok ostale trebaju imati URL koji odgovara nazivu aplikacije i
     * mape za aplikaciju.
     * @since 0.3.5.pre-alpha.M3
     *
     * @throws Greska Ukoliko ne postoji informacija o zadanoj aplikaciji.
     *
     * @return string Naziv zadane aplikacije.
     */
    private function trenutnaAplikacija ():string {

        // ako postoji url i vrijednost postoji u listi aplikacija u .env datoteci
        if (
            $this->zahtjev->url() !== '/'
            && env('APP_' . strtoupper($this->zahtjev->urlKomponente()[0]), false) === true
        ) {

            return $this->zahtjev->urlKomponente()[0];

        }

        // zadana aplikacija
        $zadana_aplikacija = env('APP_ZADANA', false);

        // ako ne postoji zapis o zadanoj aplikaciji ili je zadana aplikacija postavljena na false
        if (
            $zadana_aplikacija === false
            || env($zadana_aplikacija, false) === false
        ) {

            zapisnik(Level::KRITICNO, _('Ne mogu pronaći zadanu aplikaciju sustava!'));
            throw new Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru.'));

        }

        return ltrim(strtolower(env('APP_ZADANA', '')), 'app_');

    }

    /**
     * ### HTTP odgovor.
     * @since 0.2.6.pre-alpha.M2
     *
     * @throws Datoteka_Greska Ukoliko se ne može pročitati naziv datoteke.
     * @throws Kontejner_Greska Ukoliko se ne može napraviti objekt Log-a.
     *
     * @return HTTP_Odgovor Odgovor za HTTP.
     */
    private function odgovor ():HTTP_Odgovor {

        return (new HTTP_Odgovor(
            (new Datoteka())->datoteka($_SERVER['SCRIPT_FILENAME'])
        ));

    }

}