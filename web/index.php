<?php declare(strict_types = 1);

/**
 * Osnovna datoteka za pokretanje sustava
 * @since 0.2.2.pre-alpha.M2
 *
 * @package Sustav\Jezgra
 */

use FireHub\Jezgra\Sustav;
use FireHub\Jezgra\Enumeratori\Kernel;
use FireHub\Jezgra\Enumeratori\Kernel_Enumerator;

/**
 * Pokreni sustav
 */
require __DIR__.'/../jezgra/firehub.Sustav.php';
//require __DIR__.'/../jezgra/enumeratori/firehub.Kernel.php';
require __DIR__.'/../jezgra/firehub.Enumerator.php';
require __DIR__.'/../jezgra/enumeratori/firehub.Kernel.Enumerator.php';

$sustav = new Sustav();
$odgovor = $sustav->pokreni(Kernel_Enumerator::HTTP());
echo $odgovor->sadrzaj();