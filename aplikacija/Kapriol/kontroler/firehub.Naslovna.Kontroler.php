<?php declare(strict_types = 1);

namespace FireHub\Aplikacija\Kapriol\Kontroler;

use FireHub\Jezgra\Kontroler\Kontroler;
use FireHub\Jezgra\HTTP\Atributi\Zaglavlja;
use FireHub\Jezgra\HTTP\Enumeratori\Predmemorija;
use FireHub\Jezgra\HTTP\Enumeratori\Vrsta;
use FireHub\Jezgra\Sadrzaj\Sadrzaj;
use FireHub\Jezgra\Sadrzaj\Enumeratori\Vrsta as Sadrzaj_Vrsta;

final class Naslovna_Kontroler extends Kontroler {

    #[Zaglavlja(vrsta: Vrsta::HTML, predmemorija: [Predmemorija::BEZ_SPREMANJA], predmemorija_vrijeme: 400)]
    public function index ():Sadrzaj {

        return sadrzaj()->datoteka('naslovna.html')->format(Sadrzaj_Vrsta::HTML)->podatci([
            'prvi_podatak' => 'naslovna-index',
            'drugi_podatak' => 'naslovna-index',
            'treći_podatak' => 'naslovna-index'
        ]);

    }

}