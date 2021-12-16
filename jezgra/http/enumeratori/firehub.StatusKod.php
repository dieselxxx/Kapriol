<?php declare(strict_types = 1);

/**
 * Datoteka za enumerator za dostupne HTTP status kodove
 * @since 0.2.5.pre-alpha.M2
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\HTTP
 */

namespace FireHub\Jezgra\HTTP\Enumeratori;

/**
 * ### Enumerator za dostupne HTTP status kodove
 * @since 0.2.5.pre-alpha.M2
 *
 * @package Sustav\HTTP
 */
enum StatusKod:int {

    case HTTP_CONTINUE = 100;
    case HTTP_SWITCHING_PROTOCOLS = 101;
    case HTTP_PROCESSING = 102; // WEBDAV; RFC 2518
    case HTTP_EARLY_HINTS = 103; // RFC 8297
    case HTTP_OK = 200;
    case HTTP_CREATED = 201;
    case HTTP_ACCEPTED = 202;
    case HTTP_NON_AUTHORITATIVE_INFORMATION = 203; // HTTP/1.1
    case HTTP_NO_CONTENT = 204;
    case HTTP_RESET_CONTENT = 205;
    case HTTP_PARTIAL_CONTENT = 206; // RFC 7233
    case HTTP_MULTI_STATUS = 207; // WEBDAV; RFC 4918
    case HTTP_ALREADY_REPORTED = 208; // WEBDAV; RFC 5842
    case HTTP_IM_USED  = 226; // RFC 3229
    case HTTP_MULTIPLE_CHOICES = 300;
    case HTTP_MOVED_PERMANENTLY = 301;
    case HTTP_FOUND = 302;
    case HTTP_SEE_OTHER = 303; // SINCE HTTP/1.1
    case HTTP_NOT_MODIFIED = 304; // RFC 7232
    case HTTP_USE_PROXY = 305; // SINCE HTTP/1.1
    case HTTP_SWITCH_PROXY = 306;
    case HTTP_TEMPORARY_REDIRECT = 307; // SINCE HTTP/1.1
    case HTTP_PERMANENT_REDIRECT = 308; // RFC 7538
    case HTTP_BAD_REQUEST = 400;
    case HTTP_UNAUTHORIZED = 401; // RFC 7235
    case HTTP_PAYMENT_REQUIRED = 402;
    case HTTP_FORBIDDEN = 403;
    case HTTP_NOT_FOUND = 404;
    case HTTP_METHOD_NOT_ALLOWED = 405;
    case HTTP_NOT_ACCEPTABLE = 406;
    case HTTP_PROXY_AUTHENTICATION_REQUIRED = 407; // RFC 7235
    case HTTP_REQUEST_TIMEOUT = 408;
    case HTTP_CONFLICT = 409;
    case HTTP_GONE = 410;
    case HTTP_LENGTH_REQUIRED = 411;
    case HTTP_PRECONDITION_FAILED = 412; // RFC 7232
    case HTTP_PAYLOAD_TOO_LARGE = 413; // RFC 7231
    case HTTP_URI_TOO_LONG = 414; // RFC 7231
    case HTTP_UNSUPPORTED_MEDIA_TYPE = 415; // RFC 7231
    case HTTP_RANGE_NOT_SATISFIABLE = 416; // RFC 7233
    case HTTP_EXPECTATION_FAILED = 417;
    case HTTP_I_M_A_TEAPOT = 418; // RFC 2324 RFC 7168
    case HTTP_MISDIRECTED_REQUEST = 421; // RFC 7540
    case HTTP_UNPROCESSABLE_ENTITY = 422; // WEBDAV; RFC 4918
    case HTTP_LOCKED = 423; // WEBDAV; RFC 4918
    case HTTP_FAILED_DEPENDENCY = 424; // WEBDAV; RFC 4918
    case HTTP_TOO_EARLY = 425; // RFC 8470
    case HTTP_UPGRADE_REQUIRED = 426;
    case HTTP_PRECONDITION_REQUIRED = 428; // RFC 6585
    case HTTP_TOO_MANY_REQUESTS = 429; // RFC 6585
    case HTTP_LOGIN_TIME_OUT = 440; // IIS
    case HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE = 431; // RFC 6585
    case HTTP_RETRY_WITH = 449; // IIS
    case HTTP_UNAVAILABLE_FOR_LEGAL_REASONS = 451; // RFC 7725
    case HTTP_INTERNAL_SERVER_ERROR = 500;
    case HTTP_NOT_IMPLEMENTED = 501;
    case HTTP_BAD_GATEWAY = 502;
    case HTTP_SERVICE_UNAVAILABLE = 503;
    case HTTP_GATEWAY_TIMEOUT = 504;
    case HTTP_HTTP_VERSION_NOT_SUPPORTED = 505;
    case HTTP_VARIANT_ALSO_NEGOTIATES = 506; // RFC 2295
    case HTTP_INSUFFICIENT_STORAGE = 507; // WEBDAV; RFC 4918
    case HTTP_LOOP_DETECTED = 508; // WEBDAV; RFC 5842
    case HTTP_NOT_EXTENDED = 510;
    case HTTP_NETWORK_AUTHENTICATION_REQUIRED = 511; // RFC 6585

    /**
     * ### Obrada naziva HTTP status koda
     *
     * Obrada naziva HTTP status koda iz sirovog naziva enuma
     * u format potreban za ispravan rad internet pretraživača.
     * @since 0.2.5.pre-alpha.M2
     *
     * @return string Obrađeni naziv status koda.
     */
    public function statusNaziv ():string {

        return ucwords( // prva slova svih riječi u velika slova
            str_replace( // promjena karatera {_} u prazni karakter
                '_', ' ',
                strtolower( // pretvaranje svih karatera u mala slova
                    substr($this->name, 5) // naziv status koda iz enumeratora
                )
            )
        );

    }

}