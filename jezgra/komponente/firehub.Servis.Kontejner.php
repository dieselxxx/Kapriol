<?php declare(strict_types = 1);

/**
 * Datoteka za servis kontejner
 *
 * Servis Kontejner je klasa namjenjena za upravljanje ovisnostima definicija i
 * dependency injection za servise.
 * @since 0.3.0.pre-alpha.M3
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente;

use FireHub\Jezgra\Kontejner\Kontejner;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;

/**
 * ### Upravljanje ovisnostima definicija i dependency injection za servise
 * @since 0.3.0.pre-alpha.M3
 *
 * @package Sustav\Jezgra
 */
final class Servis_Kontejner extends Kontejner {

    /**
     * Putanja do konfiguracijske datoteke posluzitelja
     * @var array<string, array<string[]>>
     */
    private const POSLUZITELJI = FIREHUB_ROOT . 'konfiguracija' . RAZDJELNIK_MAPE . 'posluzitelji.php';

    /**
     * ### Lista posluzitelja
     * @var array<string, array<string[]>>
     */
    private array $posluzitelji = [];

    /**
     * ### Konstruktor
     * @since 0.3.0.pre-alpha.M3
     *
     * @param Servis_Posluzitelj $posluzitelj <p>
     * Poslužitelj servisa za trenutni servis servise.
     * </p>
     *
     * @throws Kontejner_Greska Ukoliko se ne može učitati datoteka sa poslužiteljima, postavljeni servisi nije ispravan, ne postoji poslužitelj u konfiguraciji poslužitelja ili ne postoji servis za poslužitelja u konfiguraciji poslužitelja.
     */
    public function __construct (
        private Servis_Posluzitelj $posluzitelj
    ) {

        // učitaj datoteku sa poslužiteljima
        $this->posluzitelji = include self::POSLUZITELJI ?? throw new Kontejner_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru'));

        // provjeri da li postoji poslužitelj u konfiguraciji poslužitelja
        if (!array_key_exists($this->posluzitelj::class, $this->posluzitelji)) {

            throw new Kontejner_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru'));

        }

        parent::__construct($this->servis());

    }

    /**
     * {@inheritDoc}
     *
     * @throws Kontejner_Greska Ako ne postoji objekt sa nazivom klase.
     */
    protected function parameteri ():array {

        return [
            $this->posluzitelj, // prvi parametar je poslužitelj servisa
            ...array_filter( // ostali parametri su svi parametri iz konstruktora bez poslužitelja servisa
                $this->autozicaObjekt(),
                function (object $objekt):?object {

                    if (!is_a($objekt, $this->posluzitelj::class, true)) {

                        return $objekt;

                    }

                    return null;

                }
            )
        ];

    }

    /**
     * ### FQN naziv trenutog servisa
     *
     * Prvo pokušaj pronaći naziv servisa iz postavljene statičke varijable
     * poslužitelja, zatim pronađi prvi servis iz datoteke servisa.
     * @since 0.3.0.pre-alpha.M3
     *
     * @throws Kontejner_Greska Ukoliko postavljeni servisi nije ispravan, ne postoji poslužitelj u konfiguraciji poslužitelja ili ne postoji servis za poslužitelja u konfiguraciji poslužitelja.
     *
     * @return string FQN trenutnog servisa.
     */
    private function servis ():string {

        // provjeri da li je ispravan postavljeni servis ukoliko je naveden u poslužitelju
        if (
            !is_null($this->posluzitelj->postavljeniServis()) && // postoji unaprijed postavljeni servis
            (
                array_key_exists($this->posluzitelj->postavljeniServis(), $this->posluzitelji[$this->posluzitelj::class]['servisi']) !== true // postavljeni servis postoji kao servis kod trenutnog poslužitelja
                || !is_a($this->posluzitelj->postavljeniServis(), Servis_Interface::class, true) // postavljeni servis ima interface za servise
            )
        ) {

            throw new Kontejner_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru'));

        }

        // vrati postavljeni servis ili zadani servis iz konfiguracije
        return $this->posluzitelj->postavljeniServis() ?? $this->zadaniServis();

    }

    /**
     * ### Pročitaj zadani servis iz konfiguracije servisa
     * @since 0.3.0.pre-alpha.M3
     *
     * @throws Kontejner_Greska Ukoliko ne postoji servis za poslužitelja u konfiguraciji poslužitelja, ako prvi zapis poslužitelja nije niz vrijednosti ili ključ prvog zapis poslužitelja nije string.
     *
     * @return string FQN zadanog servisa.
     */
    private function zadaniServis ():string {

        // ako poslužitelj u datoteci sa poslužiteljima nema niti jednog servisa
        if (empty($this->posluzitelji[$this->posluzitelj::class])) {

            throw new Kontejner_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru'));

        }

        // prvi zapis (servis) poslužitelja
        $prvi_element = reset($this->posluzitelji[$this->posluzitelj::class]);

        // ako prvi zapis poslužitelja nije niz vrijednosti ili ključ prvog zapis poslužitelja nije string
        if (!is_array($prvi_element) || !is_string(key($prvi_element))) {

            throw new Kontejner_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru'));

        }

        if (!is_a(key($prvi_element), Servis_Interface::class, true)) { // postavljeni servis ima interface za servise)

            throw new Kontejner_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru'));

        }

        // vrati prvi servis iz datoteke poslužitelja
        return key($prvi_element);

    }

}