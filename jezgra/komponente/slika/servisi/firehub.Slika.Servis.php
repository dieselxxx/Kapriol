<?php declare(strict_types = 1);

/**
 * Datoteka za upravljanje slika
 * @since 0.6.1.alpha.M6
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\Slika\Servisi;

use FireHub\Jezgra\Komponente\Slika\Slika;
use FireHub\Jezgra\Komponente\Slika\Slika_Interface;
use FireHub\Jezgra\Komponente\Log\Enumeratori\Level;
use FireHub\Jezgra\Komponente\Slika\Greske\Slika_Greska;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;
use GdImage;

/**
 * ### Servis za za upravljanje slika
 * @since 0.6.1.alpha.M6
 *
 * @package Sustav\Jezgra
 */
final class Slika_Servis implements Slika_Interface {

    private GdImage $slika;

    /**
     * ### Kontruktor
     * @since 0.6.1.alpha.M6
     *
     * @param Slika $posluzitelj <p>
     * Poslužitelj servisa.
     * </p>
     *
     * @throws Slika_Greska Ukoliko se ne može učitati datoteka.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     */
    public function __construct (
        private Slika $posluzitelj
    ) {

        // provjeri da li je ispravna putanja do slike
        if (!is_file($this->posluzitelj->slika)) {

            zapisnik(Level::GRESKA, sprintf(_('Ne mogu učitati sliku %s'), $this->posluzitelj->slika));
            throw new Slika_Greska(_('Ne mogu pročitati sliku potrebnu za rad sustava, obratite se administratoru'));

        }

        // Napravi sliku
        $this->napraviSliku();

        // postavi dimenzije slike
        $this->postaviDimenzije();

    }

    /**
     * @inheritDoc
     */
    public function ispisi ():void {

        if ($this->posluzitelj->vrsta->value == 'jpeg') { // jpeg

            imagejpeg($this->slika, NULL, $this->posluzitelj->kvaliteta);
            imagedestroy($this->slika);

        } else if ($this->posluzitelj->vrsta->value == 'gif') { // gif

            imagegif($this->slika);
            imagedestroy($this->slika);

        } else if ($this->posluzitelj->vrsta->value == 'png') { // png

            imagepng($this->slika, NULL, $this->posluzitelj->kvaliteta);
            imagedestroy($this->slika);

        } else if ($this->posluzitelj->vrsta->value == 'webp') { // webp

            imagewebp($this->slika, NULL, $this->posluzitelj->kvaliteta);
            imagedestroy($this->slika);

        } else if ($this->posluzitelj->vrsta->value == 'avif') { // avif

            imageavif($this->slika, NULL, $this->posluzitelj->kvaliteta);
            imagedestroy($this->slika);

        }

    }

    /**
     * ### Napravi sliku
     * @since 0.6.1.alpha.M6
     *
     * @throws Slika_Greska Ukoliko se ne može učitati datoteka ili slika nije u podržanom obliku.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     */
    private function napraviSliku ():void {

        if (!$vrsta = exif_imagetype($this->posluzitelj->slika)) {

            zapisnik(Level::GRESKA, sprintf(_('Ne mogu napraviti sliku %s'), $this->posluzitelj->slika));
            throw new Slika_Greska(_('Ne mogu pročitati sliku potrebnu za rad sustava, obratite se administratoru'));

        }

        // vrste datoteke
        if ($vrsta === IMAGETYPE_GIF) { // gif

            $this->slika = imagecreatefromgif($this->posluzitelj->slika);

        } else if ($vrsta === IMAGETYPE_JPEG) { // jpeg

            $this->slika = imagecreatefromjpeg($this->posluzitelj->slika);

        } else if ($vrsta === IMAGETYPE_PNG) { // png

            $this->slika = imagecreatefrompng($this->posluzitelj->slika);

        } else if ($vrsta === IMAGETYPE_WEBP) { // webp

            $this->slika = imagecreatefromwebp($this->posluzitelj->slika);

        } else if ($vrsta === IMAGETYPE_AVIF) { // avif

            $this->slika = imagecreatefromavif($this->posluzitelj->slika);

        } else {

            zapisnik(Level::GRESKA, sprintf(_('Slika %s nije u podržanom obliku'), $this->posluzitelj->slika));
            throw new Slika_Greska(_('Ne mogu pročitati sliku potrebnu za rad sustava, obratite se administratoru'));

        }

    }

    /**
     * ### Postavi dimenzije slike
     * @since 0.6.1.alpha.M6
     *
     * @throws Slika_Greska Ukoliko se ne može pročitati visina ili širina slike.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     */
    private function postaviDimenzije ():GdImage {

        $nova_slika = imagecreatetruecolor($this->posluzitelj->sirina, $this->posluzitelj->visina);

        imagecolortransparent($nova_slika, imagecolorallocate($nova_slika, 0, 0, 0));
        imagealphablending($nova_slika, false);
        imagesavealpha($nova_slika, true);

        // nova slika
        imagecopyresampled($nova_slika, $this->slika, 0, 0, 0, 0, $this->posluzitelj->sirina, $this->posluzitelj->visina, $this->UcitajSirinu(), $this->UcitajVisinu());

        return $this->slika = $nova_slika;

    }

    /**
     * ### Učitaj visinu slike.
     * @since 0.6.1.alpha.M6
     *
     * @throws Slika_Greska Ukoliko se ne može pročitati visina slike.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     *
     * @return int Visina slike.
     */
    private function UcitajVisinu ():int {

        if (!$visina = imagesy($this->slika)) {

            zapisnik(Level::GRESKA, sprintf(_('Ne mogu učitati visinu slike %s'), $this->posluzitelj->slika));
            throw new Slika_Greska(_('Ne mogu pročitati sliku potrebnu za rad sustava, obratite se administratoru'));

        }

        return $visina;

    }

    /**
     * ### Učitaj širinu slike.
     * @since 0.6.1.alpha.M6
     *
     * @throws Slika_Greska Ukoliko se ne može pročitati širina slike.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     *
     * @return int Širna slike.
     */
    private function UcitajSirinu ():int {

        if (!$sirina = imagesx($this->slika)) {

            zapisnik(Level::GRESKA, sprintf(_('Ne mogu učitati širinu slike %s'), $this->posluzitelj->slika));
            throw new Slika_Greska(_('Ne mogu pročitati sliku potrebnu za rad sustava, obratite se administratoru'));

        }

        return $sirina;

    }

}
