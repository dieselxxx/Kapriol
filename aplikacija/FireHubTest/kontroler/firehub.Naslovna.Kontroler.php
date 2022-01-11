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

        $x = $bazaPodataka->sirovi('SELECT * FROM test')->napravi();
        var_dump($x->niz());

        return sadrzaj()->datoteka('test.html')->format(Sadrzaj_Vrsta::HTML)->podatci([
            'prvi_podatak' => 'naslovna-index',
            'drugi_podatak' => 'naslovna-index',
            'treći_podatak' => 'naslovna-index'
        ]);

    }

    #[Zaglavlja(vrsta: Vrsta::HTML)]
    public function index2 ():Sadrzaj {

        $odabir_test = (new BazaPodataka())
            ->tabela('Test')
            ->odaberi(['test', 'test1'])
            ->gdje('test', '>=', 1)
            ->poredaj('test1', 'ASC')
            ->limit(0, 2)
            ->napravi();
        var_dump($odabir_test->niz());

        return sadrzaj()->datoteka('test.html')->format(Sadrzaj_Vrsta::HTML)->podatci([
            'prvi_podatak' => 'naslovna-index2',
            'drugi_podatak' => 'naslovna-index2',
            'treći_podatak' => 'naslovna-index2'
        ]);


    }

    public function index3 ():Sadrzaj {

        $odabir_test = (new BazaPodataka())
            ->transakcija(
                (new BazaPodataka())->tabela('Test')
                    ->odaberi(['test', 'test1'])
                    ->gdje('test', '>', 0)
                    ->gdje('test1', '=', 't1')
                    ->poredaj('test1', 'ASC')
                    ->limit(50, 2),
                (new BazaPodataka())->tabela('Test')
                    ->izbrisi()
                    ->gdje('test', '>', 0)
                    ->gdje('test1', '<>', 't2'),
                (new BazaPodataka())->tabela('Test')
                    ->umetni([
                        'test' => 10,
                        'test1' => 'xxx'
                    ])
            )->napravi();
        var_dump($odabir_test->rezultat());

        return sadrzaj()->datoteka('test.html')->format(Sadrzaj_Vrsta::HTML)->podatci([
            'prvi_podatak' => 'naslovna-index3',
            'drugi_podatak' => 'naslovna-index3',
            'treći_podatak' => 'naslovna-index3'
        ]);

    }

}