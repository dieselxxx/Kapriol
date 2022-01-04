<?php declare(strict_types = 1);

namespace FireHub\Aplikacija\FireHubTest\Kontroler;

use FireHub\Jezgra\Kontroler\Kontroler;
use FireHub\Jezgra\Atributi\Posrednici;
use FireHub\Jezgra\Posrednici\Test3_Posrednik;

#[Posrednici([Test3_Posrednik::class])]
final class Naslovna_Kontroler extends Kontroler {

    #[Posrednici([Test3_Posrednik::class])]
    public function index (\FireHub\Jezgra\Komponente\Dot\Dot $dot = null, $par1 = 'test', int $par2 = 5):string {

        return 'ja_sam_naslovna_index';

    }

    public function index2 () {


    }

    public function index3 () {

    }

}