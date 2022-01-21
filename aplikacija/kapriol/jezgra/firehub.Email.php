<?php declare(strict_types = 1);

/**
 * Email
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

use Biblioteke\PHPMailer\PHPMailer;
use Biblioteke\PHPMailer\SMTP;

final class Email {

    private $email;
    public $naslov = 'WebShop Email';
    public $predlozak;
    public $adrese;

    /**
     * Konstruktor.
     */
    public function __construct (string $predlozak = 'index.html') {

        // predložak email-a
        $this->predlozak = $predlozak;

        $this->email = new PHPMailer();

        // dohvati predlozak
        $this->_DohvatiPredlozak();

    }

    /**
     * Naslov email-a.
     */
    public function Naslov (string $naslov):string {

        $this->naslov = $naslov;

        return $this->naslov;

    }

    /**
     * Adresa email-a.
     */
    public function Adresa (array $adrese):array {

        $this->adrese = $adrese;

        return $this->adrese;

    }

    /**
     * Dohvati predloška za email.
     */
    private function _DohvatiPredlozak ():string {

        $this->predlozak = file_get_contents(APLIKACIJA_ROOT . 'sadrzaj' . RAZDJELNIK_MAPE . 'email' . RAZDJELNIK_MAPE . $this->predlozak);

        return $this->predlozak;

    }

    /**
     * Zamjena dodatnih komponenti na predlošku.
     */
    public function PredlozakKomponente (array $komponente):void {

        foreach ($komponente as $i => $stavka) {

            $this->predlozak = str_replace("'{{".$i."}}'", (string)$stavka, $this->predlozak);

        }

    }

    /**
     * Pošalji email.
     */
    public function Posalji ($debug = SMTP::DEBUG_OFF):void {

        $this->email->SMTPDebug = $debug;
        $this->email->IsSMTP();
        $this->email->SMTPKeepAlive = true;
        $this->email->Host = 'smtp.gmail.com';
        $this->email->Port = 587;
        $this->email->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->email->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];
        $this->email->SMTPAuth = true;
        $this->email->Username = 'grafotisak.website@gmail.com';
        $this->email->Password = 'Grude443#99';
        $this->email->SetFrom('grafotisak.website@gmail.com', 'Kapriol Web Trgovina');
        $this->email->CharSet = 'UTF-8';
        $this->email->Encoding = 'base64';
        $this->email->IsHTML(true);

        $this->email->Subject = $this->naslov;

        $this->email->AddEmbeddedImage(konfiguracija('sustav.putanje.web') . 'Kapriol' . RAZDJELNIK_MAPE . 'resursi' . RAZDJELNIK_MAPE . 'grafika' . RAZDJELNIK_MAPE . 'logo' . RAZDJELNIK_MAPE . '120x270.png', "logo");
        $this->email->msgHTML($this->predlozak);
        $this->email->AltBody = 'Za pregled ove poruke potrebno je imati HTML kompatibilni email preglednik!';

        foreach ($this->adrese as $adresa) {

            $this->email->AddAddress($adresa['adresa'], $adresa['ime']);

        }

        $this->email->Send();

    }

}