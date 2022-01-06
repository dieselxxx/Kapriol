<?php declare(strict_types = 1);

/**
 * Datoteka za MemCache predmemoriju
 * @since 0.5.0.pre-alpha.M5
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

namespace FireHub\Jezgra\Komponente\Predmemorija\Servisi;

use FireHub\Jezgra\Komponente\Predmemorija\Predmemorija;
use FireHub\Jezgra\Komponente\Predmemorija\Predmemorija_Interface;
use FireHub\Jezgra\Komponente\Log\Enumeratori\Level;
use FireHub\Jezgra\Komponente\Predmemorija\Greske\Predmemorija_Greska;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;
use Memcache as Memcache_Server;

/**
 * ### Servis Memcache predmemorije
 * @since 0.5.0.pre-alpha.M5
 *
 * @package Sustav\Jezgra
 */
final class MemCache implements Predmemorija_Interface {

    /**
     * ### Memcache klasa
     * @var Memcache_Server
     */
    private Memcache_Server $memcache;

    /**
     * ### Konstruktor
     * @since 0.5.0.pre-alpha.M5
     *
     * @param Predmemorija $posluzitelj <p>
     * Poslužitelj servisa.
     * </p>
     *
     * @throws Predmemorija_Greska Ukoliko se ne može spojiti na memcache servis il ne mogu postaviti kompresiju memcache servisa.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     */
    public function __construct (private Predmemorija $posluzitelj) {

        // napravi memcache objekt
        $this->memcache = new Memcache_Server();

        // konekcija na memcache server
        $this->konekcija();

        // postavi kompresiju
        if (!$this->kompresija()) {

            zapisnik(Level::KRITICNO, _('Ne mogu postaviti kompresiju memcache servisa!'));
            throw new Predmemorija_Greska(_('Ne mogu postaviti kompresiju memcache servisa!'));

        }

    }

    /**
     * ### Konekcija na memcache server
     * @since 0.5.0.pre-alpha.M5
     *
     * @throws Predmemorija_Greska Ukoliko se ne može spojiti na memcache servis.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     *
     * @return void
     */
    private function konekcija ():void {

        if (empty($this->posluzitelj->dodatni_serveri[0])) {

            // konekcija na memcache server
            if (!$this->konekcija_single()) {

                zapisnik(Level::KRITICNO, _('Ne mogu se spojiti na memcache servis!'));
                throw new Predmemorija_Greska(_('Ne mogu se spojiti na memcache servis!'));

            }

        } else {

            $konekcije = array_merge(
                [
                    [
                        'host' => $this->posluzitelj->host,
                        'port' => $this->posluzitelj->port,
                        'trajno' => $this->posluzitelj->trajno,
                        'tezina' => $this->posluzitelj->tezina,
                        'odziv' => $this->posluzitelj->odziv,
                        'interval_ponovni_pokusaj' => $this->posluzitelj->interval_ponovni_pokusaj
                    ]
                ],
                $this->posluzitelj->dodatni_serveri
            );

            // dodaj sve servere u konekcije
            array_walk(
                $konekcije,
                function ($konekcija):bool {

                    return $this->konekcija_multi($konekcija);

                }
            );

        }

    }

    /**
     * ### Konekcija na memcache server
     * @since 0.5.0.pre-alpha.M5
     *
     * @return bool Da li je uspješno spojeno na server.
     */
    private function konekcija_single ():bool {

        // trajna konekcija na memcache server
        if ($this->posluzitelj->trajno) {

            return $this->memcache->pconnect(
                host: $this->posluzitelj->host,
                port: $this->posluzitelj->port,
                timeout: $this->posluzitelj->odziv
            );

        }

        return $this->memcache->connect(
            host: $this->posluzitelj->host,
            port: $this->posluzitelj->port,
            timeout: $this->posluzitelj->odziv
        );

    }

    /**
     * ### Konekcija na više memcache servera
     * @since 0.5.0.pre-alpha.M5
     *
     * @param array<string, mixed> $konekcija <p>
     * Konekcija na memcache server.
     * </p>
     *
     * @return bool True, jer se konekcija ne ostvaruje u trenutku pozivanje servera, već pri zapisivanju rezultata.
     */
    private function konekcija_multi (array $konekcija):bool {

        return $this->memcache->addServer(
            host: $konekcija['host'],
            tcp_port: (int)$konekcija['port'],
            persistent: $konekcija['trajno'],
            weight: $konekcija['tezina'],
            timeout: $konekcija['odziv'],
            retry_interval: $konekcija['interval_ponovni_pokusaj']
        );

    }

    /**
     * @inheritDoc
     */
    public function dodaj (string $kljuc, mixed $vrijednost, bool $kompresija = false, int $vrijeme = 0):bool {

        if (!$this->memcache->add($this->posluzitelj->prefiks.$kljuc, $vrijednost, $this->kompresijaStavke($kompresija), $vrijeme)) {

            return false;

        }

        return true;

    }

    /**
     * @inheritDoc
     */
    public function zapisi (string $kljuc, mixed $vrijednost, bool $kompresija = false, int $vrijeme = 0):bool {

        return $this->memcache->set($this->posluzitelj->prefiks.$kljuc, $vrijednost, $this->kompresijaStavke($kompresija), $vrijeme);

    }

    /**
     * @inheritDoc
     */
    public function zamijeni (string $kljuc, mixed $vrijednost, bool $kompresija = false, int $vrijeme = 0):bool {

        return $this->memcache->replace($this->posluzitelj->prefiks.$kljuc, $vrijednost, $this->kompresijaStavke($kompresija), $vrijeme);

    }

    /**
     * @inheritDoc
     */
    public function dohvati (array|string $kljuc):string|array|false {

        return $this->memcache->get($this->posluzitelj->prefiks.$kljuc);

    }

    /**
     * @inheritDoc
     */
    public function izbrisi (string $kljuc):bool {

        return $this->memcache->delete($this->posluzitelj->prefiks.$kljuc);

    }

    /**
     * @inheritDoc
     */
    public function ocisti ():bool {

        return $this->memcache->flush();

    }

    /**
     * {@inheritDoc}
     *
     * @throws Predmemorija_Greska Ukoliko se ne može spojiti na server preko socketa.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     */
    public function sve ():array {

        $string = $this->komanda('stats items');

        $linije = explode("\r\n", $string);

        foreach ($linije as $linija) {

            if (preg_match('/STAT items:([\d]+):number ([\d]+)/', $linija, $pronadjeno) && isset($pronadjeno[1])) {

                $string = $this->komanda('stats cachedump ' . $pronadjeno[1] . ' ' . $pronadjeno[2]);

                preg_match_all('/ITEM (.*?) /', $string, $pronadjeno);

                return $pronadjeno[1];

            }

        }

        return [];

    }

    /**
     * {@inheritDoc}
     *
     * @throws Predmemorija_Greska Ukoliko se ne može spojiti na server preko socketa.
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Log-a.
     */
    public function komanda (string $komadna):string {

        $socket = @fsockopen($this->posluzitelj->host, $this->posluzitelj->port);

        if (!$socket) {

            zapisnik(Level::KRITICNO, sprintf(_('Ne se spojiti na server: %s, preko socketa!'), $this->posluzitelj->host));
            throw new Predmemorija_Greska((_('Ne mogu pokrenuti sustav, obratite se administratoru.')));

        }

        fwrite($socket, $komadna."\r\n");

        $tekst = '';
        while ((!feof($socket))) {

            $tekst .= fgets($socket, 256);

            if (str_contains($tekst, "END\r\n")) { // status

                break;

            }

            if (str_contains($tekst, "DELETED\r\n") || str_contains($tekst, "NOT_FOUND\r\n")){ // brisanje

                break;

            }

            if (str_contains($tekst, "OK\r\n")){ // izbriši_sve

                break;

            }

        }

        fclose($socket);

        return ($tekst);

    }

    /**
     * ### Postavi kompresiju stavke
     * @since 0.5.0.pre-alpha.M5
     *
     * @param bool $kompresija <p>
     * Da li se stavka kompresira.
     * </p>
     *
     * @return int Vrijednost kompresije stavke.
     */
    private function kompresijaStavke (bool $kompresija):int {

        return $kompresija ? MEMCACHE_COMPRESSED : 0;

    }

    /**
     * ### Postavi kompresiju
     * @since 0.5.0.pre-alpha.M5
     *
     * @return bool Da li je postavljena konfiguracija predmemorije.
     */
    private function kompresija ():bool {

        return $this->memcache->setCompressThreshold(
            $this->posluzitelj->prag_duljine_kompresije,
            $this->posluzitelj->kompresija
        );

    }

    /**
     * ### Zatvori memcache konekciju
     * @since 0.5.0.pre-alpha.M5
     *
     * @return void
     */
    public function __destruct () {

        $this->memcache->close();

    }

}