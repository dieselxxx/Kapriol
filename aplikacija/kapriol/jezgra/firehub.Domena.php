<?php declare(strict_types = 1);

/**
 * Domena
 * @since 0.1.2.pre-alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2022 Kapriol Web Trgovina
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Aplikacija\Kontroler
 */

namespace FireHub\Aplikacija\Kapriol\Jezgra;

final class Domena {

    public static function Ba ():bool {

        if (Server::Domena() === 'localhost:223') {

            return true;

        }

    }

    public static function Hr ():bool {

        if (Server::Domena() === 'privremenadomena.lin77.host25.com') {

            return true;

        }

        return false;


    }

    public static function sqlTablica ():string {

        if (self::Hr()) {

            return 'Hr';

        }

        return 'Ba';

    }

    public static function sqlCijena ():string {

        if (self::Hr()) {

            return 'CijenaKn';

        }

        return 'Cijena';

    }

    public static function sqlCijenaAkcija ():string {

        if (self::Hr()) {

            return 'CijenaAkcijaKn';

        }

        return 'CijenaAkcija';

    }

    public static function valuta ():string {

        if (self::Hr()) {

            return 'kn';

        }

        return 'KM';

    }

    public static function telefon ():string {

        if (self::Hr()) {

            return '+385 21 486 385';

        }

        return '+387 36 349 223';

    }

    public static function adresa ():string {

        if (self::Hr()) {

            return 'Glavina Donja 336, 21260 Imotski';

        }

        return 'Dubrovačka bb (SC Piramida), 88000 Mostar';

    }

    public static function poslovnice ():string {

        if (self::Hr()) {

            return 'poslovnice_hr.html';

        }

        return 'poslovnice.html';

    }

    public static function podnozjeDostava ():string {

        if (self::Hr()) {

            return '
                <ul>
                    <li><span>Besplatna dostava za narudzbe preko 400 HRK.</span></li>
                    <li><span>Dostava za narudzbe manje od 400 HRK iznosi 25 HRK.</span></li>
                </ul>
            ';

        }

        return '
                <ul>
                    <li><span>Besplatna dostava za narudzbe preko 79 KM.</span></li>
                    <li><span>Dostava za narudzbe manje od 79 KM iznosi 5 KM.</span></li>
                </ul>
            ';

    }

    public static function OIBPDV ():string {

        if (self::Hr()) {

            return 'OIB';

        }

        return 'PDV';

    }

    public static function dostavaLimit ():int {

        if (self::Hr()) {

            return 400;

        }

        return 79;

    }

    public static function dostavaIznos ():int {

        if (self::Hr()) {

            return 25;

        }

        return 5;

    }

    public static function facebook ():string {

        if (self::Hr()) {

            return 'https://www.facebook.com/kapriol.hr';

        }

        return 'https://www.facebook.com/kapriol.ba';

    }

    public static function instagram ():string {

        if (self::Hr()) {

            return 'https://www.instagram.com/kapriol.hr/';

        }

        return 'https://www.instagram.com/kapriol.sa/';

    }

    public static function mobitel ():string {

        if (self::Hr()) {

            return '385996039376';

        }

        return '38763363270';

    }

    public static function opciUvjeti ():string {

        if (self::Hr()) {

            return '<li><span><a href="/opciuvjeti">Opći uvjeti</a></span></li>';

        }

        return '';

    }

}