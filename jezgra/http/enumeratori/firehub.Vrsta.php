<?php declare(strict_types = 1);

/**
 * Datoteka za enumerator za dostupne HTTP vrste
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
 * ### Enumerator za dostupne HTTP vrste
 * @since 0.2.5.pre-alpha.M2
 *
 * @package Sustav\HTTP
 */
enum Vrsta:string {

    case AAC = 'audio/aac';
    case ABW = 'application/x-abiword';
    case ARC = 'application/x-freearc';
    case AVI = 'video/x-msvideo';
    case AZW = 'application/vnd.amazon.ebook';
    case BIN = 'application/octet-stream';
    case BMP = 'image/bmp';
    case BZIP = 'application/x-bzip';
    case BZIP2 = 'application/x-bzip2';
    case CDA = 'application/x-cdf';
    case CSH = 'application/x-csh';
    case CSS = 'text/css';
    case CSV = 'text/csv';
    case DOC = 'application/msword';
    case DOCX = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
    case EOT = 'application/vnd.ms-fontobject';
    case EPUB = 'application/epub+zip';
    case GZ = 'application/gzip';
    case GIF = 'image/gif';
    case HTML = 'text/html';
    case ICO = 'image/vnd.microsoft.icon';
    case ICS = 'text/calendar';
    case JAR = 'application/java-archive';
    case JPEG = 'image/jpeg';
    case JS = 'text/javascript';
    case JSON = 'application/json';
    case JSONLD = 'application/ld+json';
    case MIDI = 'audio/midi audio/x-midi';
    case MP3 = 'audio/mpeg';
    case MP4 = 'video/mp4';
    case MPEG = 'video/mpeg';
    case MPKG = 'application/vnd.apple.installer+xml';
    case ODP = 'application/vnd.oasis.opendocument.presentation';
    case ODS = 'application/vnd.oasis.opendocument.spreadsheet';
    case ODT = 'application/vnd.oasis.opendocument.text';
    case OGA = 'audio/ogg';
    case OGG = 'video/ogg';
    case OGX = 'application/ogg';
    case OPUS = 'audio/opus';
    case OTF = 'font/otf';
    case PNG = 'image/png';
    case PDF = 'application/pdf';
    case PHP = 'application/x-httpd-php';
    case PPT = 'application/vnd.ms-powerpoint';
    case PPTX = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
    case RAR = 'application/vnd.rar';
    case RTF = 'application/rtf';
    case SH = 'application/x-sh';
    case SVG = 'image/svg+xml';
    case SWF = 'application/x-shockwave-flash';
    case TAR = 'application/x-tar';
    case TIFF = 'image/tiff';
    case TS = 'video/mp2t';
    case TTF = 'font/ttf';
    case TXT = 'text/plain';
    case VSD = 'application/vnd.visio';
    case WAV = 'audio/wav';
    case WEBA = 'audio/webm';
    case WEBM = 'video/webm';
    case WEBP = 'image/webp';
    case WOFF = 'font/woff';
    case WOFF2 = 'font/woff2';
    case XHTML = 'application/xhtml+xml';
    case XLS = 'application/vnd.ms-excel';
    case XLSX = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    case XML = 'application/xml';
    case XUL = 'application/vnd.mozilla.xul+xml';
    case ZIP = 'application/zip';
    case GP3VIDEO = 'video/3gpp';
    case GP3AUDIP = 'audio/3gpp';
    case GP32VIDEO = 'video/3gpp2';
    case GP32AUDIP = 'audio/3gpp2';
    case SZIP = 'application/x-7z-compressed';

}