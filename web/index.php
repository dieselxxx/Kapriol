<?php declare(strict_types = 1);

/**
 * Osnovna datoteka za pokretanje sustava
 * @since 0.2.2.pre-alpha.M2
 *
 * @package Sustav\Jezgra
 */

use FireHub\Jezgra\Sustav;
use FireHub\Jezgra\Enumeratori\Kernel;

/**
 * Pokreni sustav
 */
require __DIR__.'/../jezgra/firehub.Sustav.php';
require __DIR__.'/../jezgra/enumeratori/firehub.Kernel.php';
$sustav = new Sustav();
$sustav->pokreni(Kernel::HTTP);

// @todo maknuti u produkciji, ili naraviti opciju u debugeru
echo round(memory_get_peak_usage()/1048576, 2) . ' mb';