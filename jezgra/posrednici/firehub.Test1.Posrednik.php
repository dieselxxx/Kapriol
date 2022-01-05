<?php declare(strict_types = 1);

namespace FireHub\Jezgra\Posrednici;

final class Test1_Posrednik implements Posrednik {

    /**
     * @inheritDoc
     */
    public function obradi ():bool {

        //var_dump('pos_test_1');

        return true;

    }

}