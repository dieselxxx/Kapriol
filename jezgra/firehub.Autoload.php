<?php declare(strict_types = 1);

/**
 * Autoload datoteka
 *
 * Ova datoteka sadrži definicije i niz anonimnih funkcija za automatsko učitavanje svih pozvanih objekata.
 * Datoteka bi trebala vratiti true ukoliko se učita klasa ili false ukoliko se dogodi greška.
 * @since 0.2.1.pre-alpha.M2
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra;

use Exception;
use FireHub\Jezgra\Enumeratori\Prefiks;
use FireHub\Jezgra\Enumeratori\Sufiks;

require __DIR__.'/../jezgra/enumeratori/firehub.Prefiks.php';
require __DIR__.'/../jezgra/enumeratori/firehub.Sufiks.php';

/**
 * ### Datoteka koja pripada FireHub aplikaciji
 *
 * FireHub datoteke sadrže prefiks "firehub." u svome nazivu, te ukoliko imaju sufiks,
 * on mora biti iz niza VRSTE_DATOTEKA.
 * @since 0.2.1.pre-alpha.M2
 *
 * @param string[] $putanja_niz <p>
 * FQN objekta razbijen u niz.
 * </p>
 * @param string $objekt <p>
 * Naziv objekta.
 * </p>
 *
 * @throws Exception Ukoliko ekstenzija nije ispravna.
 *
 * @return string Putanja i naziv datoteke.
 */
$datotekaFireHub = static function (array $putanja_niz, string $objekt):string {

    array_shift($putanja_niz); // ukloni prvu komponentu
    $putanja = implode(DIRECTORY_SEPARATOR, $putanja_niz);

    // vrsta datoteke
    $datoteka_komponente = explode('_', $objekt);
    $ime = $datoteka_komponente[0] ?? '';
    count($datoteka_komponente) > 1 ? $vrsta = $datoteka_komponente[1] : $vrsta = false;

    if ($vrsta && (!Sufiks::tryFrom($vrsta) || isset(Prefiks::cases()[0]) === false)) { // provjeri ispravnost ekstenzije datoteke

        throw new Exception(_('Ne mogu pokrenuti sustav, obratite se administratoru.'));

    }

    $naziv_objekta = $vrsta
        ? Prefiks::cases()[0]->value . '.' . $ime . '.' . $vrsta . '.php'
        : Prefiks::cases()[0]->value . '.' . $ime . '.' . $vrsta . 'php';

    return DIRECTORY_SEPARATOR . $putanja . DIRECTORY_SEPARATOR . $naziv_objekta;

};

/**
 * ### Datoteka koja pripada stranoj biblioteki
 *
 * Datoteke se nalaze u mapi "Biblioteke", te nemaju nikakvo dodatno
 * filtriranje datoteka prilikom autoload-a.
 * @since 0.2.1.pre-alpha.M2
 *
 * @param string[] $putanja_niz <p>
 * FQN objekta razbijen u niz.
 * </p>
 * @param string $objekt <p>
 * Naziv objekta.
 * </p>
 *
 * @return string Putanja i naziv datoteke.
 */
$datotekaBiblioteka = static function (array $putanja_niz, string $objekt):string {

    $putanja = 'Biblioteke' . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $putanja_niz);

    return realpath($_SERVER['DOCUMENT_ROOT']) . DIRECTORY_SEPARATOR . $putanja . DIRECTORY_SEPARATOR . $objekt . '.php';

};

/**
 * ### Potraži datoteku iz pozvanog objekta
 * @since 0.2.1.pre-alpha.M2
 *
 * @param string $auto_objekt <p>
 * Objekt koja se poziva.
 * </p>
 *
 * @throws Exception Ukoliko ne postoji datoteka.
 *
 * @return string|null Putanja i naziv datoteke.
 */
$datoteka = static function (string $auto_objekt) use ($datotekaFireHub, $datotekaBiblioteka):?string {

    // komponente argumenta
    $putanja_niz = explode(DIRECTORY_SEPARATOR, $auto_objekt);
    $namespace = reset($putanja_niz); // prva komponenta
    $objekt = end($putanja_niz); // zadnja komponenta

    // putanja datoteke (ukloni zadnju komponentu)
    array_pop($putanja_niz);

    // registriraj datoteku
    if ($namespace === 'FireHub') { // firehub datoteka

        $datoteka = $datotekaFireHub($putanja_niz, $objekt);

        // ako ne postoji datoteka
        if (!is_file(realpath($_SERVER['DOCUMENT_ROOT']) . DIRECTORY_SEPARATOR . $datoteka)) {

            throw new Exception(sprintf(_('Dogodila se greška prilikom izvođenja aplikacije zbog datoteke: %s!'), $datoteka));

        }

        return $datoteka;

    }

    if ($namespace === 'Bibliteka') { // datoteka iz biblioteke

        $datoteka = $datotekaBiblioteka($putanja_niz, $objekt);

        // ako ne postoji datoteka
        if (!is_file(realpath($_SERVER['DOCUMENT_ROOT']) . DIRECTORY_SEPARATOR . $datoteka)) {

            throw new Exception(sprintf(_('Dogodila se greška prilikom izvođenja aplikacije zbog datoteke: %s!'), $datoteka));

        }

        return $datoteka;

    }

    // ostale datoteke koje ne trebaju autoload
    return null;

};

/**
 * ### Autoload funkcija za registraciju objekata
 * @since 0.2.1.pre-alpha.M2
 *
 * @param string $auto_objekt <p>
 * Objekt koja se poziva.
 * </p>
 *
 * @return int|false Include datoteke.
 */
$autoload = static function (string $auto_objekt) use ($datoteka):int|false {

    $objekt = $datoteka($auto_objekt);

    return is_string(realpath($_SERVER['DOCUMENT_ROOT']) . DIRECTORY_SEPARATOR . $objekt) // da li je string
        ? include realpath($_SERVER['DOCUMENT_ROOT']) . DIRECTORY_SEPARATOR . $objekt // include objektovu datoteku
        : false;

};

/**
 * ### Autoload pozvanih objekta
 * @since 0.2.1.pre-alpha.M2
 *
 * @param callable $callback <p>
 * Autoload funkcija.
 * </p>
 * @param bool $throw <p>
 * Throws Execption, ignore nakon PHP 8 verzije.
 * </p>
 * @param bool $prepend <p>
 * Prepend(true) ili Append(false) autoload.
 * </p>
 *
 * @return bool True ako je objekt registriran, false ako nije.
 */
return spl_autoload_register (callback: $autoload, throw: true, prepend: false);