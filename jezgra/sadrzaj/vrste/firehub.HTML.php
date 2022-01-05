<?php declare(strict_types = 1);

/**
 * Datoteka za HTML sadržaj
 * @since 0.4.4.pre-alpha.M4
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Sadrzaj
 */

namespace FireHub\Jezgra\Sadrzaj\Vrste;

use FireHub\Jezgra\Sadrzaj\Vrsta_Interface;
use FireHub\Jezgra\Komponente\Log\Enumeratori\Level;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;
use FireHub\Jezgra\Sadrzaj\Greske\Sadrzaj_Greska;
use JsonException;
use Generator;

/**
 * ### Klasa za HTML sadržaj
 * @since 0.4.4.pre-alpha.M4
 *
 * @package Sustav\Sadrzaj
 */
final class HTML implements Vrsta_Interface {

    /**
     * ### Sadržaj za ispis
     * @var string
     */
    private string $sadrzaj = '';

    /**
     * {@inheritDoc}
     *
     * @param string $datoteka [optional] <p>
     * Datoteka za učitavanja.
     * </p>
     * @param string $baza_sustav [optional] <p>
     * Bazna datoteka sustava.
     * </p>
     * @param string $baza_app [optional] <p>
     * Bazna datoteka aplikacije.
     * </p>
     * @param string $predlozak_putanja [optional] <p>
     * Putanje do datoteka za preložak aplikacije.
     * </p>
     * @param string $tema [optional] <p>
     * Naziv teme.
     * </p>
     * @param string $konfiguracija_teme [optional] <p>
     * Konfiguracijska JSON datoteka za temu.
     * </p>
     */
    public function __construct (
        private array $podatci,
        private string $datoteka = '',
        private string $baza_sustav = '',
        private string $baza_app = '',
        private string $predlozak_putanja = '',
        private string $tema = '',
        private string $json_konfiguracija_teme = ''
    ) {

    }

    /**
     * {@inheritDoc}
     *
     * @throws Sadrzaj_Greska Ukoliko se ne mogu obraditi podatci na datoteci, nema podataka predloška, ne mogu učitati konfiguracijsku json datoteku ili je datoteka prazna.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     * @throws JsonException Ukoliko se ne može dekodirati JSON sadržaj iz konfiguracijske datoteke teme ili je datoteka prazna.
     *
     * @todo Dodati predmemoriju
     */
    public function ispisi ():string {

        //return '<br><b>'.round(memory_get_peak_usage()/1048576, 2) . ' mb</b>';

        // učitaj statički i dinamički sadržaj iz datoteka
        return $this->sadrzajSve();

    }

    /**
     * ### Učitaj dinamički sadržaj stranice
     * @since 0.4.4.pre-alpha.M4
     *
     * @throws Sadrzaj_Greska Ukoliko se ne može učitati datoteka sa sadržajem.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     *
     * @return string Sadržaj.
     */
    private function sadrzajDinamicki ():string {

        $this->ucitajDatoteku();
        $this->obradiPodatke();

        return $this->sadrzaj;

    }

    /**
     * ### Učitaj statički i dinamički sadržaj stranice
     * @since 0.4.4.pre-alpha.M4
     *
     * @throws Sadrzaj_Greska Ukoliko se ne mogu obraditi podatci na datoteci, nema podataka predloška, ne mogu učitati konfiguracijsku json datoteku ili je datoteka prazna.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     * @throws JsonException Ukoliko se ne može dekodirati JSON sadržaj iz konfiguracijske datoteke teme ili je datoteka prazna.
     *
     * @return string Sadržaj.
     */
    private function sadrzajSve ():string {

        $this->dodajBazu();
        $this->dodajKomponente();
        $this->ucitajTemu();
        $this->ucitajDatoteku();
        $this->obradiPodatke();

        return $this->sadrzaj;

    }

    /**
     * ### Dodaj baznu HTML datoteku
     * @since 0.4.4.pre-alpha.M4
     *
     * @throws Sadrzaj_Greska Ukoliko se ne može učitati bazni HTML sustava ili aplikacije.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     *
     * @return string Sadržaj.
     */
    private function dodajBazu ():string {

        switch (true) {

            // pokušaj učitati bazni HTML aplikacije
            case file_exists($this->baza_app) && $this->baza_app = file_get_contents($this->baza_app) :

                // dodaj sadrzaj aplikcije u bazu
                return $this->sadrzaj = $this->baza_app;

            // pokušaj učitati bazni HTML sustava
            case file_exists($this->baza_sustav) && $this->baza_sustav = file_get_contents($this->baza_sustav) :

                // dodaj sadrzaj sustava u bazu
                return $this->sadrzaj = $this->baza_sustav;

            // nedostaje bazni HTML sustava i aplikacije
            default :

                zapisnik(Level::KRITICNO, sprintf(_('Ne mogu učitati baznu datoteku sustava: %s, ili aplikacije: %s!'), $this->baza_sustav, $this->baza_app));
                throw new Sadrzaj_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru.'));

        }

    }

    /**
     * ### Dodaj statičke komponente preloška u sadržaj
     * @since 0.4.4.pre-alpha.M4
     *
     * @throws Sadrzaj_Greska Ukoliko nema podataka predloška.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     *
     * @return string Sadržaj.
     */
    private function dodajKomponente ():string {

        // potraži sve riječi koji počimaju sa <## i završavaju sa ##>
        preg_match_all('/<##(.+?)##>/', $this->sadrzaj, $podatci_predloska);

        if (!isset($podatci_predloska[1]) || !is_array($podatci_predloska[1])) {

            zapisnik(Level::KRITICNO, _('Ne mogu pronaći podatke predloška!'));
            throw new Sadrzaj_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru.'));

        }

        // za sve pronađene riječi učitaj odgovorajuću html komponentu
        array_walk(
            $podatci_predloska[1],
            function ($komponenta):string {

                if (!file_exists($this->predlozak_putanja . '' . $komponenta . '.html') || !$predlozak = file_get_contents($this->predlozak_putanja . '' . $komponenta . '.html')) {

                    zapisnik(Level::KRITICNO, sprintf(_('Ne mogu učitati datoteku predloška: %s!'), $komponenta));
                    throw new Sadrzaj_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru.'));

                }

                return $this->sadrzaj = str_replace('<##'.$komponenta.'##>', $predlozak, $this->sadrzaj);

            }
        );

        return $this->sadrzaj;

    }

    /**
     * ### Dodaj statičke komponente teme u sadržaj
     * @since 0.4.4.pre-alpha.M4
     *
     * @throws Sadrzaj_Greska Ukoliko nema podataka predloška, ne mogu učitati konfiguracijsku json datoteku ili je datoteka prazna.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     * @throws JsonException Ukoliko se ne može dekodirati JSON sadržaj iz konfiguracijske datoteke teme ili je datoteka prazna.
     *
     * @return string Sadržaj.
     */
    private function ucitajTemu ():string {

        if (
            !file_exists($this->json_konfiguracija_teme) // ako putanja ne postoji
            ||
            !$konfiguracija = json_decode(
                file_get_contents($this->json_konfiguracija_teme),
                true, 512, JSON_THROW_ON_ERROR
            )
        ) {

            zapisnik(Level::KRITICNO, sprintf(_('Ne mogu učitati konfiguracijsku json datoteku za temu: %s, ili je datoteka prazna!'), $this->json_konfiguracija_teme));
            throw new Sadrzaj_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru.'));

        }

        // potraži sve riječi koji počimaju sa <%% i završavaju sa %%>
        preg_match_all('/<%%(.+?)%%>/', $this->sadrzaj, $podatci_teme);

        if (!isset($podatci_teme[1]) || !is_array($podatci_teme[1])) {

            zapisnik(Level::KRITICNO, _('Ne mogu pronaći podatke teme!'));
            throw new Sadrzaj_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru.'));

        }

        // dodaj komponente teme
        array_walk(
            $podatci_teme[1],
            function ($komponenta) use ($konfiguracija):string {

                if (!isset($konfiguracija[$komponenta]) || is_null($konfiguracija[$komponenta])) {

                    zapisnik(Level::KRITICNO, sprintf(_('Ne postoji ključ: %s u konfiguracijskog datoteci za temu!'), $komponenta));
                    throw new Sadrzaj_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru.'));

                }

                array_walk(
                    $konfiguracija[$komponenta],
                    function ($vrijednost) use ($komponenta, &$css, &$javascript):string {

                        return match ($komponenta) {
                            'css' => $css .= '<link rel="stylesheet" href="/'.APLIKACIJA.'/resursi/teme/'.$this->tema.'/'.$vrijednost.'" type="text/css" media="all">'."\r\n",
                            'javascript' => $javascript .= '<script type="text/javascript" src="/'.APLIKACIJA.'/resursi/teme/'.$this->tema.'/'.$vrijednost.'"></script>'."\r\n",
                            default => ''
                        };

                    }
                );

                return match($komponenta) {
                    'css' => $this->sadrzaj = str_replace('<%%'.$komponenta.'%%>', $css ?? '', $this->sadrzaj),
                    'javascript' => $this->sadrzaj = str_replace('<%%'.$komponenta.'%%>', $javascript ?? '', $this->sadrzaj),
                    default => $this->sadrzaj = str_replace('<%%'.$komponenta.'%%>', '', $this->sadrzaj)
                };

            }
        );

        return $this->sadrzaj;

    }

    /**
     * ### Datoteka sa HTML sadržajem
     * @since 0.4.4.pre-alpha.M4
     *
     * @throws Sadrzaj_Greska Ukoliko se ne može učitati datoteka sa sadržajem.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     *
     * @return string Sadržaj.
     */
    private function ucitajDatoteku ():string {

        $sadrzaj = APLIKACIJA_ROOT . 'sadrzaj' . RAZDJELNIK_MAPE . $this->datoteka;

        if (!file_exists($sadrzaj) || !$sadrzaj = file_get_contents($sadrzaj)) {

            zapisnik(Level::KRITICNO, sprintf(_('Ne mogu učitati datoteku sa sadržajem: %s!'), $this->datoteka));
            throw new Sadrzaj_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru.'));

        }

        return $this->sadrzaj = str_replace('<{{sadrzaj}}>', $sadrzaj, $this->sadrzaj);

    }

    /**
     * ### Zamijeni sve varijable sa podatcima
     * @since 0.4.4.pre-alpha.M4
     *
     * @return bool Da li se mogu obraditi podatci.
     */
    private function obradiPodatke ():bool {

        // dodaj novi sadržaj sa zamijenjenim podatcima
        foreach ($this->generator($this->sadrzaj, $this->podatci) as $sadrzaj) {

            if (!is_string($sadrzaj)) { // sadržaj nije string

                return false;

            }

            // dodaj sadržaj
            $this->sadrzaj = $sadrzaj;

        }

        return true;

    }

    /**
     * ### Zamijeni sve varijable sa podatcima preko generatora
     * @since 0.4.4.pre-alpha.M4
     *
     * @param string $sadrzaj <p>
     * Sadržaj za ispis.
     * </p>
     * @param array<string, string|int> $podatci <p>
     * Podatci koje treba prenijeti u sadržaj.
     * </p>
     *
     * @return Generator Izmijenjeni sadržaj.
     */
    private function generator (string $sadrzaj, array $podatci):Generator {

        foreach ($podatci as $kljuc => $vrijednost) {

            // zamijeni ključeve sa vrijednosti
            yield $sadrzaj = str_replace('{{'.$kljuc.'}}', $vrijednost, $sadrzaj);

        }

    }

}