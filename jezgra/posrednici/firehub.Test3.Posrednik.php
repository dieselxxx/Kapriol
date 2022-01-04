<?php declare(strict_types = 1);

namespace FireHub\Jezgra\Posrednici;

use FireHub\Jezgra\Komponente\Dot\Dot;

final class Test3_Posrednik implements Posrednik {

    public function __construct (private Dot $dot) {

    }

    /**
     * @inheritDoc
     */
    public function obradi ():bool {

        return true;

    }

}