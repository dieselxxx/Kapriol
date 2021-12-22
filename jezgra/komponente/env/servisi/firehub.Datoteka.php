<?php declare(strict_types = 1);

/**
 * Datoteka za čitanje .env datoteka
 * @since 0.3.3.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\Env\Servisi;

use FireHub\Jezgra\Komponente\Env\Env;
use FireHub\Jezgra\Komponente\Env\Env_Interface;
use FireHub\Jezgra\Komponente\Log\Enumeratori\Level;
use FireHub\Jezgra\Komponente\Env\Greske\Env_Greska;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;

/**
 * ### Servis za čitanje .env datoteka
 * @since 0.3.3.pre-alpha.M3
 *
 * @package Sustav\Jezgra
 */
final class Datoteka implements Env_Interface {

    /**
     * ### Kontruktor
     * @since 0.3.3.pre-alpha.M3
     *
     * @param Env $posluzitelj <p>
     * Poslužitelj servisa.
     * </p>
     *
     * @throws Env_Greska Ukoliko nije ispravna env datoteka ili se dogodila greška prilikom čitanja env datoteke.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     */
    public function __construct (
        private Env $posluzitelj
    ) {

        foreach ($this->posluzitelj->datoteka as $datoteka) {

            // provjeri dali je ispravna env datoteka
            if (!is_file($datoteka) || pathinfo($datoteka)['extension'] !== 'env') {

                zapisnik(Level::HITNO, sprintf(_('Ne mogu učitati .env datoteku %s'), $datoteka));
                throw new Env_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru'));

            }

            // postavi postavke iz poslane env datoteke
            if (!$this->postavi($datoteka)) {

                zapisnik(Level::HITNO, sprintf(_('Dogodila se greška prilikom čitanja podataka iz env datoteke: %s'), $datoteka));
                throw new Env_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru'));

            }

        }

    }

    /**
     * @inheritDoc
     */
    public static function procitaj (string $env, string|int|float|bool|null $zadano = null):string|int|float|bool|null {

        // dohvati env postavku
        $env_varijabla = getenv($env);

        switch ($env_varijabla) {

            case false : // nije postavljena varijabla

                return $zadano;

            case 'true' : // string vrijednost 'true' pretvara se u boolean
            case 'false' : // string vrijednost 'false' pretvara se u boolean

                return filter_var($env_varijabla, FILTER_VALIDATE_BOOLEAN);

            case is_numeric($env_varijabla) && str_contains($env_varijabla, '.') : // ako je string vrijednosti decimalni broj, pretvori u decimalni broj

                return (float)$env_varijabla;

            case is_numeric($env_varijabla) : // ako je string vrijednosti broj, pretvori u pravi broj

                return (int)$env_varijabla;

            default : // zadano prepisujemo string

                return $env_varijabla;

        }

    }

    /**
     * ### Postavi postavke iz poslane env datoteke
     * @since 0.3.3.pre-alpha.M3
     *
     * @param string $datoteka <p>
     * Env datoteka od poslužitelja.
     * </p>
     *
     * @throws Env_Greska Ukoliko je metoda {datotekaLinije} javila grešku.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     *
     * @return bool True ako je uspješno postavljano, false ako jedna od linija datoteke preko pozvane funkcije "str_starts_with" vrati false.
     */
    private function postavi (string $datoteka):bool {

        $datoteka_linije = $this->datotekaLinije($datoteka);

        return array_walk(
            $datoteka_linije,
            static function ($linija):bool {

                // sve linije bez početnog karaktera # (komentara)
                if (!str_starts_with(trim($linija), '#')) {

                    [$opcija, $vrijednost] = explode('=', $linija, 2);
                    $opcija = trim($opcija);
                    $vrijednost = trim($vrijednost);

                    return putenv(sprintf('%s=%s', $opcija, $vrijednost));

                }

                return false;

            }
        );

    }

    /**
     * ### Pretvori poslanu datoteku u niz linija
     * @since 0.3.3.pre-alpha.M3
     *
     * @param string $datoteka <p>
     * Env datoteka od poslužitelja.
     * </p>
     *
     * @throws Env_Greska Ukoliko se ne može pretvoriti env datoteka u linije.
     * @throws Kontejner_Greska Ukoliko se ne može napraviti objekt Log-a.
     *
     * @return string[] Lista linija iz env datoteke.
     */
    private function datotekaLinije (string $datoteka):array {

        $linije = file($datoteka, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        // provjeri dali je datoteka učitana, ne dali je array prazan
        if ($linije === false) {

            zapisnik(Level::KRITICNO, sprintf(_('Dogodila se greška prilikom pretvaranje .env datoteke "%s" u linije'), $datoteka));
            throw new Env_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru'));

        }

        return $linije;

    }

}