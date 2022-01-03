<?php declare(strict_types = 1);

/**
 * Osnovna datoteka za pokretanje upita
 * @since 0.2.3.pre-alpha.M2
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra;

use FireHub\Jezgra\Posrednici\Posrednik_Kontejner;
use FireHub\Jezgra\Komponente\Env\Env;
use FireHub\Jezgra\Komponente\Konfiguracija\Konfiguracija;
use FireHub\Jezgra\Komponente\Log\Log;
use FireHub\Jezgra\Komponente\Log\Enumeratori\Level;
use FireHub\Jezgra\Greske\Kernel_Greska;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;
use Generator;

/**
 * ### Osnovna klasa Kernel za pokretanje upita
 * @since 0.2.3.pre-alpha.M2
 *
 * @package Sustav\Jezgra
 */
abstract class Kernel {

    /**
     * ### Konstruktor.
     * @since 0.2.3.pre-alpha.M2
     *
     * @param Zahtjev $zahtjev <p>
     * Zahtjev.
     * </p>
     */
    abstract function __construct (Zahtjev $zahtjev);

    /**
     * ### Pokreni Kernel
     *
     * Ova metoda služi za pokretanje sustava i jedina je
     * metoda izložena datotekama koje pokreću sustav.
     * @since 0.2.3.pre-alpha.M2
     *
     * @return Odgovor Instanca Odgovora.
     */
    abstract public function pokreni ():Odgovor;

    /**
     * ### Obrada posrednika za kernel
     * @since 0.4.0.pre-alpha.M4
     *
     * @param array $posrednici <p>
     * Lista posrednika.
     * </p>
     * @param string $kljuc <p>
     * Ključ sa listom posrednika koje se trebaju obraditi.
     * </p>
     *
     * @throws Kernel_Greska Ukoliko ne postoji ključ u nizu parametara za posrednika.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     *
     * @return static Kernel objekt.
     */
    protected function posrednici (array $posrednici, string $kljuc):self {

        if (!array_key_exists($kljuc, $posrednici)) {

            (new Log)->level(Level::KRITICNO)->poruka(sprintf(_('Ne postoji ključ: %s, u nizu parametara za posrednika'), $kljuc))->napravi()->posalji();
            throw new Kernel_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru.'));

        }

        // pokeni posrednike
        $generator = $this->obradiPosrednike($posrednici[$kljuc]);

        // obradi posrednike
        while ($generator->valid()) {

            $generator->next();

        }

        return $this;

    }

    /**
     * ### Učitaj datoteku sa pomoćnim funkcijama
     * @since 0.3.1.pre-alpha.M3
     *
     * @throws Kernel_Greska Ukoliko se ne mogu učitati pomagači.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     *
     * @return static Kernel objekt.
     */
    protected function pomagaci ():self {

        if (!include(FIREHUB_ROOT . 'jezgra' . RAZDJELNIK_MAPE . 'firehub.Pomagaci.php')) {

            (new Log)->level(Level::KRITICNO)->poruka(sprintf(_('Pomagači: %s, se ne mogu učitati'), FIREHUB_ROOT . 'jezgra' . RAZDJELNIK_MAPE . 'firehub.Pomagaci.php'))->napravi()->posalji();
            throw new Kernel_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru.'));

        }

        return $this;

    }

    /**
     * ### Učitaj .env datoteku
     * @since 0.3.5.pre-alpha.M3
     *
     * @param string $putanja <p>
     * Puna putanja do .env datoteke.
     * </p>
     *
     * @throws Kontejner_Greska Ukoliko se ne može napraviti Env objekt.
     *
     * @return static Kernel objekt.
     */
    protected function ucitajEnv (string $putanja):self {

        (new Env())->datoteka($putanja)->napravi();

        return $this;

    }

    /**
     * ### Učitaj konfiguracijske postavke
     *
     * Pozivanje svih konfiguracijskih objekata sustava i trenutne aplikacije.
     * @since 0.3.5.pre-alpha.M3
     *
     * @throws Kernel_Greska Ukoliko se ne može učitati konfiguracijska datoteka.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     *
     * @return static Kernel objekt.
     */
    protected function konfiguracija ():self {

        if (!(new Konfiguracija())->napravi()) {

            zapisnik(Level::UZBUNA, _('Ne mogu učitati konfiguraciju sustava!'));
            throw new Kernel_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru.'));

        }

        return $this;

    }

    /**
     * ### Obradi sve posrednike
     *
     * Pokreće svim posrednicima metodu {obradi} koja
     * vraća stanje posrednika. Posrednici se učitavaju redosljedom iz
     * konfiguracijske datoteke posrednika, te prvi koji vrati stanje false
     * zaustavlja se daljnje obrađivanje posrednika.
     * @since 0.4.0.pre-alpha.M4
     *
     * @param string[] $posrednici <p>
     * Lista posrednika za obradu.
     * </p>
     *
     * @throws Kernel_Greska Ukoliko ne mogu obraditi posrednika.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca objekta.
     *
     * @return Generator Pokreće posrednika.
     */
    private function obradiPosrednike (array $posrednici):Generator {

        foreach ($posrednici as $posrednik) {

            // napravi posrednika
            $obradi_posrednika = (new Posrednik_Kontejner($posrednik))->dohvati()->obradi();

            if ($obradi_posrednika === false) {

                zapisnik(Level::KRITICNO, sprintf(_('Ne mogu obraditi posrednika %s!'), $posrednik));
                throw new Kernel_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru.'));

            }

            yield $obradi_posrednika;

        }

    }

}