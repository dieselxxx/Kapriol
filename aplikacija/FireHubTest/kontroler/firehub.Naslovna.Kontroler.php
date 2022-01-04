<?php declare(strict_types = 1);

namespace FireHub\Aplikacija\FireHubTest\Kontroler;

use FireHub\Jezgra\Sadrzaj\Sadrzaj;
use FireHub\Jezgra\HTTP\Atributi\Zaglavlja;
use FireHub\Jezgra\HTTP\Enumeratori\Predmemorija;
use FireHub\Jezgra\HTTP\Enumeratori\Vrsta;
use FireHub\Jezgra\Kontroler\Kontroler;
use FireHub\Jezgra\Atributi\Posrednici;
use FireHub\Jezgra\Posrednici\Test3_Posrednik;

#[Posrednici([Test3_Posrednik::class])]
final class Naslovna_Kontroler extends Kontroler {

    #[Posrednici([Test3_Posrednik::class])]
    #[Zaglavlja(vrsta: Vrsta::HTML, predmemorija: [Predmemorija::BEZ_SPREMANJA], predmemorija_vrijeme: 400)]
    public function index (\FireHub\Jezgra\Komponente\Dot\Dot $dot = null, $par1 = 'test', int $par2 = 5):Sadrzaj {

        return sadrzaj();

    }

    public function index2 () {


    }

    public function index3 () {

    }

}