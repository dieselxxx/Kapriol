<?php declare(strict_types = 1);

namespace FireHub\Aplikacija\FireHubTest\Kontroler;

use FireHub\Jezgra\Kontroler\Kontroler;

final class Naslovna_Kontroler extends Kontroler {

    public function index (\FireHub\Jezgra\Komponente\Dot\Dot $dot = null, $par1 = 'test', int $par2 = 5):string {

        return 'ja_sam_naslovna_index';

    }

    public function index2 () {


    }

    public function index3 () {

    }

}