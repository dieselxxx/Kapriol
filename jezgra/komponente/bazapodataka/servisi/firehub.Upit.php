<?php declare(strict_types = 1);

/**
 * Datoteka za upit prema bazi podataka
 * @since 0.6.0.alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\BazaPodataka\Servisi;

/**
 * ### Upit prema bazi podataka
 * @since 0.6.0.alpha.M1
 *
 * @package Sustav\Jezgra
 */
final class Upit {

    /**
     * ### Sirovi upit prema bazi podataka
     * @var ?string
     */
    public ?string $sirovi = null;

    /**
     * ### Vrsta upita prema bazi podataka (odaberi, umetni, azuriraj)
     * @var ?string
     */
    public ?string $vrsta = null;

    /**
     * ### Lista kolumni za odabir
     * @var ?array
     */
    public ?array $kolumne = null;

    /**
     * ### Lista podataka za umetanje
     * @var ?array
     */
    public ?array $podatci = null;

    /**
     * ### Spoji tabelu na postojeći upit ('tabela' => $tabela, 'kolumna_lijevo' => $kolumna_lijevo, 'kolumna_desno' => $kolumna_desno)
     * @var ?array
     */
    public ?array $spoji = null;

    /**
     * ### Filtri vrijednost po nazivu kolumne ('naziv' => $naziv, 'operator' => $operator, 'vrijednost' => $vrijednost)
     * @var ?array
     */
    public ?array $gdje = null;

    /**
     * ### Kolumna po kojoj redamo zapise
     * @var ?string
     */
    public ?string $poredaj = null;

    /**
     * ### Redolijed po kojoj redamo zapise (ASC ili DESC)
     * @var ?string
     */
    public ?string $poredaj_redoslijed = null;

    /**
     * ### Pomak od kojeg se limitiraju zapisi
     * @var ?int
     */
    public ?int $limit_pomak = null;

    /**
     * ### Broj redaka koje odabiremo
     * @var ?int
     */
    public ?int $limit_broj_redaka = null;

}