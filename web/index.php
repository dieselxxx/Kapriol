<?php declare(strict_types = 1);

/**
 * Osnovna datoteka za pokretanje sustava
 * @since 0.2.2.pre-alpha.M2
 *
 * @package Sustav\Jezgra
 */
use FireHub\Jezgra\Sustav;

/**
 * Pokreni sustav
 */
require __DIR__.'/../jezgra/firehub.Sustav.php';
$sustav = new Sustav();
$sustav->pokreni();