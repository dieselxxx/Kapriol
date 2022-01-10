<?php declare(strict_types = 1);

namespace FireHub\Aplikacija\FireHubTest\Kontroler;

use FireHub\Jezgra\Komponente\BazaPodataka\BazaPodataka;
use FireHub\Jezgra\Sadrzaj\Sadrzaj;
use FireHub\Jezgra\HTTP\Atributi\Zaglavlja;
use FireHub\Jezgra\HTTP\Enumeratori\Predmemorija;
use FireHub\Jezgra\HTTP\Enumeratori\Vrsta;
use FireHub\Jezgra\Kontroler\Kontroler;
use FireHub\Jezgra\Atributi\Posrednici;
use FireHub\Jezgra\Posrednici\Test3_Posrednik;
use FireHub\Jezgra\Sadrzaj\Enumeratori\Vrsta as Sadrzaj_Vrsta;

#[Posrednici([Test3_Posrednik::class])]
final class Naslovna_Kontroler extends Kontroler {

    #[Posrednici([Test3_Posrednik::class])]
    #[Zaglavlja(vrsta: Vrsta::HTML, predmemorija: [Predmemorija::BEZ_SPREMANJA], predmemorija_vrijeme: 400)]
    //#[\FireHub\Jezgra\Komponente\Kolacic\Atributi\Kolacic('test', 'yxx', http: false)]
    public function index (BazaPodataka $bazaPodataka = null, $par1 = 'test', int $par2 = 5):Sadrzaj {

        //$x = (new BazaPodataka())->upit('SELECT * FROM test2')->napravi();
        //var_dump($x->niz());

        $y = (new BazaPodataka())->upit('SELECT * FROM test LIMIT 3')->napravi();
        var_dump($y->niz());

        $z = (new BazaPodataka())->upit(
        )->napravi();
        //var_dump($z->niz());

        return sadrzaj()->datoteka('test.html')->format(Sadrzaj_Vrsta::HTML)->podatci([
            'prvi_podatak' => 'naslovna-index',
            'drugi_podatak' => 'naslovna-index',
            'treći_podatak' => 'naslovna-index'
        ]);

    }

    #[Zaglavlja(vrsta: Vrsta::JSON)]
    public function index2 ():Sadrzaj {

        return sadrzaj()->datoteka('test.html')->format(Sadrzaj_Vrsta::JSON)->podatci([
            'prvi_podatak' => 'naslovna-index2',
            'drugi_podatak' => 'naslovna-index2',
            'treći_podatak' => 'naslovna-index2'
        ]);


    }

    public function index3 ():Sadrzaj {

        return sadrzaj()->datoteka('test.html')->format(Sadrzaj_Vrsta::HTML)->podatci([
            'prvi_podatak' => 'naslovna-index3',
            'drugi_podatak' => 'naslovna-index3',
            'treći_podatak' => 'naslovna-index3'
        ]);

    }

}