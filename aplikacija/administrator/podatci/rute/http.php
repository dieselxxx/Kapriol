<?php declare(strict_types = 1);

/**
 * Rute za HTTP pozive aplikacije
 * @since 0.4.1.pre-alpha.M4
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

use FireHub\Jezgra\Komponente\Rute\Rute;

Rute::sve('prijava/index', [\FireHub\Aplikacija\Administrator\Kontroler\Prijava_Kontroler::class, 'index']);
Rute::sve('prijava/autorizacija', [\FireHub\Aplikacija\Administrator\Kontroler\Prijava_Kontroler::class, 'autorizacija']);
Rute::sve('odjava/index', [\FireHub\Aplikacija\Administrator\Kontroler\Odjava_Kontroler::class, 'index']);
Rute::sve('naslovna/index', [\FireHub\Aplikacija\Administrator\Kontroler\Naslovna_Kontroler::class, 'index']);
Rute::sve('artikli/index', [\FireHub\Aplikacija\Administrator\Kontroler\Artikli_Kontroler::class, 'index']);
Rute::sve('artikli/lista', [\FireHub\Aplikacija\Administrator\Kontroler\Artikli_Kontroler::class, 'lista']);
Rute::sve('artikli/uredi', [\FireHub\Aplikacija\Administrator\Kontroler\Artikli_Kontroler::class, 'uredi']);
Rute::sve('artikli/spremi', [\FireHub\Aplikacija\Administrator\Kontroler\Artikli_Kontroler::class, 'spremi']);
Rute::sve('artikli/dodajsliku', [\FireHub\Aplikacija\Administrator\Kontroler\Artikli_Kontroler::class, 'dodajsliku']);
Rute::sve('artikli/izbrisisliku', [\FireHub\Aplikacija\Administrator\Kontroler\Artikli_Kontroler::class, 'izbrisisliku']);