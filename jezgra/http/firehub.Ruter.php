<?php declare(strict_types = 1);

/**
 * Datoteka za rutiranje HTTP zahtjeva i odgovora aplikacije
 * @since 0.4.1.pre-alpha.M4
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\HTTP
 */

namespace FireHub\Jezgra\HTTP;

use FireHub\Jezgra\HTTP\Zahtjev as HTTP_Zahtjev;
use FireHub\Jezgra\Komponente\Log\Enumeratori\Level;
use FireHub\Jezgra\Komponente\Rute\Rute;
use FireHub\Jezgra\Kontroler\Kontroler_Kontejner;
use FireHub\Jezgra\Kontroler\Kontroler;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;
use FireHub\Jezgra\HTTP\Greske\Ruter_Greska;
use ReflectionException;

/**
 * ### Klasa Ruter za rutiranje HTTP zahtjeva i odgovora aplikacije
 * @since 0.4.1.pre-alpha.M4
 *
 * @package Sustav\HTTP
 */
final class Ruter {

    /**
     * ### Trenutna ruta
     * @var array<string, string>|false
     */
    private array|false $ruta;

    /**
     * ### Naziv zadanog kontrolera
     * @var string
     */
    private string $kontroler = 'FireHub\\Aplikacija\\'.APLIKACIJA.'\\Kontroler\\Naslovna_Kontroler';

    /**
     * ### Naziv zadane metode
     * @var string
     */
    private string $metoda = 'index';

    /**
     * ### Zadani parameteri
     * @var array<int, string>
     */
    private array $parametri = [];

    /**
     * ### Konstruktor
     * @since 0.4.1.pre-alpha.M4
     *
     * @param HTTP_Zahtjev $http_zahtjev <p>
     * HTTP Zahtjev.
     * </p>
     * @param Rute $rute <p>
     * Poslužitelj HTTP ruta.
     * </p>
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Rute ili Log-a.
     */
    public function __construct (
        private HTTP_Zahtjev $http_zahtjev,
        private Rute $rute
    ) {

        // trenutna ruta
        $this->ruta = $this->ruta();

    }

    /**
     * ### Pokreni kontroler
     * @since 0.4.2.pre-alpha.M4
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     * @throws Ruter_Greska Ukoliko objekt nije instanca kontrolera.
     * @throws ReflectionException Ako ne postoji objekt sa nazivom klase.
     *
     * @return string Sadržaj kontroler.
     */
    public function kontroler ():string {

        // kontroler kontejner
        $kontroler_kontejner = new Kontroler_Kontejner($this->kontrolerNaziv());

        // kontroler
        $kontroler = $kontroler_kontejner->singleton();

        // kontroler mora biti instanca abstraktnog kontrolera
        if (!$kontroler instanceof Kontroler) {

            zapisnik(Level::KRITICNO, sprintf(_('Objekt: "%s" nije instanca kontrolera!'), $kontroler::class));
            throw new Ruter_Greska(sprintf(_('Ne mogu pokrenuti sustav, obratite se administratoru.'), $kontroler::class));

        }

        // pozovi metodu
        $metoda = $this->metoda();

        // autožica metode
        $autozica_metoda = $kontroler_kontejner->autozicaMetoda($metoda);

        // atributi metode
        $kontroler_kontejner->atributiMetoda($metoda);

        // pokreni metodu kontrolera sa parametrima
        return $kontroler->$metoda(...array_merge($autozica_metoda, $this->parametri($this->url())));

    }

    /**
     * ### Provjeri rutu iz trenutnog URL-a kod poslužitelja
     * @since 0.4.1.pre-alpha.M4
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Rute.
     *
     * @return array<string, string>|false Niz podataka rute.
     */
    private function ruta ():array|false {

        // filtrirani url bez aplikacije u nazivu
        $url = $this->url();

        // vrati podatke rute
        return $this->rute->napravi()->provjeri(
            $this->http_zahtjev->metoda(),
            implode('/', array_splice($url, 0, 2))
        );

    }

    /**
     *
     * ### Filtiraj URL bez aplikacije u nazivu
     *
     * Ruter treba čisti URL bez aplikacije u nazivu kako bi mogao
     * proslijediti ispravnu informaciju poslužitelju za rute, te
     * registraciju komponenti za kontroler.
     * @since 0.4.1.pre-alpha.M4
     *
     * @return array<int, string>
     */
    private function url ():array {

        $url_komponente = $this->http_zahtjev->urlKomponente();

        if (isset($url_komponente[0]) && $url_komponente[0] === APLIKACIJA) {

            return array_splice($url_komponente,1);

        }

        return array_splice($url_komponente,0);

    }

    /**
     * ### Naziv trenutnog kontrolera
     * @since 0.4.2.pre-alpha.M4
     *
     * @return string FQN naziv kontrolera.
     */
    private function kontrolerNaziv ():string {

        $aplikacija_kontroler_putanja = 'FireHub\\Aplikacija\\'.APLIKACIJA.'\\Kontroler\\';

        return match (true) {
            ($this->ruta) && (isset($this->ruta[0])) && (is_a($this->ruta[0], Kontroler::class, true)) => $this->ruta[0], // provjera da li postoji prvi ključ u nizu rute i ključ je kontroler
            isset($this->url()[0]) && class_exists($aplikacija_kontroler_putanja . $this->url()[0]. '_Kontroler') => $aplikacija_kontroler_putanja . $this->url()[0]. '_Kontroler', // provjera da li postoji klasa sa prvom vrijednosti iz URL-a
            default => $this->kontroler // pozovi zadani kontroler
        };

    }

    /**
     * ### Naziv metode
     * @since 0.4.2.pre-alpha.M4
     *
     * @return string Naziv metode.
     */
    private function metoda ():string {

        return match (true) {
            ($this->ruta) && (isset($this->ruta[1])) && (method_exists($this->kontrolerNaziv(), $this->ruta[1])) => $this->ruta[1], // provjera da li postoji drugi ključ u nizu rute i metoda u kontroleru
            default => $this->metoda // pozovi zadanu metodu
        };

    }

    /**
     * ### Niz parametara
     * @since 0.4.2.pre-alpha.M4
     *
     * @param string[] $url <p>
     * Lista url parametara.
     * </p>
     *
     * @return string[] Niz parametara.
     */
    private function parametri (array $url):array {

        // ukoliko je prva vrijednost url-a naziv trenutnog kontrolera makni je
        if (isset($url[0]) && strtolower('FireHub\\Aplikacija\\'.APLIKACIJA.'\\Kontroler\\'.$url[0].'_Kontroler') === strtolower($this->kontroler)) {

            array_shift($url);

        }

        // ukoliko je prva vrijednost url-a naziv trenutne metode makni je
        if (isset($url[0]) && $url[0] == $this->metoda) {

            array_shift($url);

        }

        return !empty($url) ? $url : $this->parametri;

    }

}