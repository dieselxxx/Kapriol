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
use FireHub\Jezgra\Komponente\Rute\Rute;
use FireHub\Jezgra\HTTP\Odgovor as HTTP_Odgovor;
use FireHub\Jezgra\Komponente\Datoteka\Datoteka;
use FireHub\Jezgra\Komponente\Log\Log;
use FireHub\Jezgra\Komponente\Log\Servisi\AutoPosalji;
use FireHub\Jezgra\Komponente\Log\Enumeratori\Level;
use FireHub\Jezgra\Greske\Kernel_Greska;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;
use FireHub\Jezgra\Komponente\Datoteka\Greske\Datoteka_Greska;
use FireHub\Jezgra\HTTP\Greske\Ruter_Greska;
use ReflectionException;
use Throwable;

/**
 * ### Osnovna klasa Kernel za pokretanje HTTP upita
 * @since 0.2.3.pre-alpha.M2
 *
 * @package Sustav\HTTP
 */
final class Kernel extends OsnovniKernel {

    /**
     * ### HTTP Ruter
     * @var Ruter
     */
    private Ruter $ruter;

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
                ->ruter()
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
     * @throws Kernel_Greska Ukoliko se ne može pročitati zadana aplikacija.
     * @throws Kontejner_Greska Ukoliko se ne može napraviti objekt Log-a.
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
     * @throws Kernel_Greska Ukoliko ne postoji informacija o zadanoj aplikaciji.
     * @throws Kontejner_Greska Ukoliko se ne može napraviti objekt Log-a.
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
            throw new Kernel_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru.'));

        }

        return ltrim(strtolower(env('APP_ZADANA', '')), 'app_');

    }

    /**
     * ### Pokreni ruter
     *
     * Sustav za rutiranje HTTP zahtjeva i odgovora aplikacije.
     * @since 0.4.1.pre-alpha.M4
     *
     * @throws Kernel_Greska Ukoliko se ne može učitati ruter.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Rute.
     *
     * @return $this Instanca Kernel-a.
     */
    private function ruter ():self {

        if (!$this->ruter = new Ruter($this->zahtjev, new Rute())) {

            (new Log)->level(Level::HITNO)->poruka('Ne mogu učitati ruter');
            throw new Kernel_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru.'));

        }

        return $this;

    }

    /**
     * ### HTTP odgovor.
     * @since 0.2.6.pre-alpha.M2
     *
     * @throws Datoteka_Greska Ukoliko se ne može pročitati naziv datoteke.
     * @throws Kontejner_Greska Ukoliko se ne može napraviti objekt Log-a.
     * @throws Ruter_Greska Ukoliko objekt nije instanca kontrolera.
     * @throws ReflectionException Ako ne postoji objekt sa nazivom klase.
     *
     * @return HTTP_Odgovor Odgovor za HTTP.
     */
    private function odgovor ():HTTP_Odgovor {

        return (new HTTP_Odgovor(
            datoteka: (new Datoteka())->datoteka($_SERVER['SCRIPT_FILENAME']),
            sadrzaj: $this->ruter->kontroler()
        ));

    }

}