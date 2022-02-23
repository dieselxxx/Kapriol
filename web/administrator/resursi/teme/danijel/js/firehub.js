$(document).ready(function () {

    /**
     * Navigacija status.
     */
    let $lokalnaPohrana = new LokalnaPohrana();
    let NavigacijaStatus = $lokalnaPohrana.Procitaj("NavigacijaStatus");

    if (NavigacijaStatus != null) {

        if (NavigacijaStatus === 'zatvoren') {

            $('body').addClass('zatvoren');

        }
    }
    $('header .navigacija_gumb').on('click', function (event) {

        if ($('body').hasClass('zatvoren')) {

            $lokalnaPohrana.Umetni('NavigacijaStatus', 'otvoren');
            $('body').removeClass('zatvoren');

        } else {

            $lokalnaPohrana.Umetni('NavigacijaStatus', 'zatvoren');
            $('body').addClass('zatvoren');

        }

    });

    /**
     * Zaglavlje profil.
     */
    $("header nav").on("click", function() {

        event.stopPropagation();

        $(this).find('ul li ul.podmeni').toggle(
            function() {
                $(this).animate({}, 500)
            }
        )

    });
    $(document).on("click", function() {

        $('header nav ul li ul.podmeni').hide(
            function() {
                $(this).animate({}, 500)
            }
        );

    });
    $('header nav ul li ul.podmeni li.treci_level').hover(

        function () {
            $(this).find('ul').show(
                function() {
                    $(this).animate({}, 500)
                }
            );
        }, function(){
            $(this).find('ul').hide(
                function() {
                    $(this).animate({}, 500)
                }
            );
        }

    );

    /**
     * Navigacija.
     */
    let url = window.location.pathname.split('/');
    if (!url[1]) {var link = '';}
    else if (!url[2]) {var link = url[1];}
    else {var link = url[1] + '/' + url[2]}
    var link = $('a[href$="' + link + '"]');
    link.closest("li").addClass("aktivan").parent("ul").parent("li").addClass("aktivan otvoren").parent("ul").parent("li").addClass("aktivan otvoren");

    $("aside nav li.podmeni > a").on("click", function() {

        $(this).removeAttr("href");
        let roditelj = $(this).parent("li");

        if (roditelj.hasClass("otvoren")) {

            roditelj.removeClass("otvoren");
            roditelj.find("li").removeClass("otvoren");
            roditelj.find("ul").slideUp(200);

        } else {

            roditelj.addClass("otvoren");
            roditelj.children("ul").slideDown(200);
            roditelj.siblings("li").children("ul").slideUp(200);
            roditelj.siblings("li").removeClass("otvoren");
            roditelj.siblings("li").find("li").removeClass("otvoren");
            roditelj.siblings("li").find("ul").slideUp(200);

        }

    });

    /**
     * Prijava.
     */
    $('form[data-oznaka="prijava"]').submit(function (odgovor) {

        odgovor.preventDefault();

        let podatci = $('form[data-oznaka="prijava"]').serializeArray();

        // dialog prozor
        let dialog = new Dialog();

        $.ajax({
            type: 'POST',
            url: '/administrator/prijava/autorizacija',
            dataType: 'json',
            data: podatci,
            beforeSend: function () {
                Dialog.dialogOtvori();
                dialog.sadrzaj(Loader_Krug);
            },
            success: function (odgovor) {
                Dialog.dialogOcisti();
                dialog.naslov('Poruka');
                dialog.sadrzaj(odgovor.Poruka);
                dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');

                if (odgovor.Validacija === 'da') {
                    //location.reload();
                    window.location.href = "/administrator/";
                }
            },
            error: function () {
                Dialog.dialogOcisti();
                dialog.naslov('Greška');
                dialog.sadrzaj('Dogodila se greška prilikom učitavanja podataka, molimo kontaktirajte administratora');
                dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
            },
            complete: function (odgovor) {
                //
            }
        });

        return false;

    });

});

/**
 * Spremi slike.
 */
$(function() {

    $("body").on('submit', 'form.slika', function() {

        let oznaka = $(this).data("oznaka");

        $_SpremiSlike('form[data-oznaka="' + oznaka + '"]');

        return false;

    }).on('change','form.slika input[type="file"]', function() {

        let oznaka = $(this).closest('form').data("oznaka");

        $('form[data-oznaka="' + oznaka + '"]').submit();

        return false;

    });

});
$_SpremiSlike = function ($url) {

    // dialog prozor
    let dialog = new Dialog();

    $($url).ajaxSubmit({
        beforeSend: function() {
            Dialog.dialogOtvori(false);
            dialog.naslov('Dodajem sliku');
            dialog.sadrzaj('' +
                '<div class="progres" style="display: block;">\
                    <div class="bar" style="width: 0%;"></div>\
                    <div class="postotak">0%</div>\
                </div>'
            );
        },
        uploadProgress: function(event, position, total, postotakZavrseno) {
            $('#dialog .sadrzaj .bar').width(postotakZavrseno + '%');
            $('#dialog .sadrzaj .postotak').html(postotakZavrseno + '%');
        },
        success: function(odgovor) {
            Dialog.dialogOcisti();
            dialog.naslov('Dodajem sliku');
            dialog.sadrzaj(odgovor.Poruka);
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">U redu</button>');
        },
        error: function () {
            Dialog.dialogOcisti();
            dialog.naslov('Greška');
            dialog.sadrzaj('Dogodila se greška prilikom učitavanja podataka, molimo kontaktirajte administratora');
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
        },
        complete: function(odgovor) {
            location.reload();
        }
    });

    return false;

};

/**
 * Odjavi se.
 */
$_Odjava = function () {

    // dialog prozor
    let dialog = new Dialog();

    $.ajax({
        type: 'POST',
        url: '/administrator/odjava',
        dataType: 'json',
        beforeSend: function () {
            Dialog.dialogOtvori(false);
            dialog.sadrzaj(Loader_Krug);
        },
        success: function (odgovor) {
            Dialog.dialogOcisti();
            dialog.naslov('Poruka');
            dialog.sadrzaj(odgovor.Poruka);
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
        },
        error: function () {
            Dialog.dialogOcisti();
            dialog.naslov('Greška');
            dialog.naslov('Dogodila se greška prilikom učitavanja podataka, molimo kontaktirajte administratora');
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
        },
        complete: function (odgovor) {
            location.reload();
        }
    });

};

/**
 * Dohvati artikle.
 *
 * @param {object} element
 * @param {int} $broj_stranice
 * @param {string} $poredaj
 * @param {string} $redoslijed
 */
$_Artikli = function (element = '', $broj_stranice = 1, $poredaj = 'Naziv', $redoslijed = 'asc') {

    let podatci = $('form[data-oznaka="artikli_lista"]').serializeArray();

    $.ajax({
        type: 'POST',
        url: '/administrator/artikli/lista/' + $broj_stranice + '/' + $poredaj + '/' + $redoslijed,
        dataType: 'json',
        data: podatci,
        success: function (odgovor) {
            $.ajax({
                type: 'POST',
                url: '/administrator/artikli/lista/' + $broj_stranice + '/' + $poredaj + '/' + $redoslijed,
                dataType: 'json',
                data: podatci,
                beforeSend: function () {
                    $('form[data-oznaka="artikli_lista"] > section table tbody').empty().html('<tr><td colspan="3">' + Loader_Krug + '</td></tr>');
                },
                success: function (odgovor) {
                    $('form[data-oznaka="artikli_lista"] > section table tbody').empty();
                    let Artikli = odgovor.Artikli;
                    $.each(Artikli, function (a, Artikal) {
                        if (Artikal.Aktivan === "1") {Artikal.Aktivan = '\
                    <label data-boja="boja" class="kontrolni_okvir">\
                        <input type="checkbox" disabled checked><span class="kontrolni_okvir"><span class="ukljuceno"></span></span>\
                    </label>\
                ';} else {Artikal.Aktivan = '\
                    <label data-boja="boja" class="kontrolni_okvir">\
                        <input type="checkbox" disabled><span class="kontrolni_okvir"><span class="ukljuceno"></span></span>\
                    </label>\
                ';}
                        $('form[data-oznaka="artikli_lista"] > section table tbody').append('\
                    <tr onclick="$_Artikl(\''+ Artikal.ID +'\')">\
                        <td class="uredi">'+ Artikal.ID +'</td>\
                        <td class="uredi">'+ Artikal.Naziv +'</td>\
                        <td class="uredi">'+ Artikal.Aktivan +'</td>\
                    </tr>\
                ');
                    });
                    // zaglavlje
                    let Zaglavlje = odgovor.Zaglavlje;
                    $('form[data-oznaka="artikli_lista"] > section div.sadrzaj > table thead').empty().append(Zaglavlje);
                    // navigacija
                    let Navigacija = odgovor.Navigacija;
                    $('form[data-oznaka="artikli_lista"] > section div.kontrole').empty().append('<ul class="navigacija">' + Navigacija.pocetak + '' + Navigacija.stranice + '' + Navigacija.kraj + '</ul>');

                },
                error: function () {
                },
                complete: function (odgovor) {
                    //
                }
            });
        },
        error: function () {
        }
    });

    return false;

};

/**
 * Uredi artikl.
 *
 * @param {int} $id
 */
$_Artikl = function ($id) {

    // dialog prozor
    let dialog = new Dialog();

    $.ajax({
        type: 'GET',
        url: '/administrator/artikli/uredi/' + $id,
        dataType: 'html',
        context: this,
        beforeSend: function () {
            Dialog.dialogOtvori(true);
            dialog.sadrzaj(Loader_Krug);
        },
        success: function (odgovor) {
            Dialog.dialogOcisti();
            dialog.naslov('Artikl: ' + $id);
            dialog.sadrzaj(odgovor);
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
            dialog.kontrole('<button type="button" class="ikona" onclick="$_ArtiklSpremi(this, \'forma\');"><svg><use xlink:href="/kapriol/resursi/grafika/simboli/simbol.ikone.svg#spremi"></use></svg><span>Spremi</span></button>');
        },
        error: function () {
            Dialog.dialogOcisti();
            dialog.naslov('Greška');
            dialog.naslov('Dogodila se greška prilikom učitavanja podataka, molimo kontaktirajte administratora');
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
        },
        complete: function (odgovor) {
            $(function () {
                $('.tagovi').tagovi_input({
                    width: 'auto'
                });
                $(".input-select").chosen({
                    search_contains: true,
                    width: '100%'
                });
            });
        }
    });

    return false;

};

/**
 * Spremi artikl.
 */
$_ArtiklSpremi = function (element) {

    // dialog prozor
    let dialog = new Dialog();

    let artikl_forma = $('form[data-oznaka="artikl"]');

    let $id = artikl_forma.data("sifra");

    let $podatci = artikl_forma.serializeArray();

    $.ajax({
        type: 'POST',
        url: '/administrator/artikli/spremi/' + $id,
        dataType: 'json',
        data: $podatci,
        beforeSend: function () {
            $(element).closest('form').find('table tr.poruka td').empty();
        },
        success: function (odgovor) {
            if (odgovor.Validacija === "da") {

                Dialog.dialogOcisti();
                dialog.naslov('Uspješno spremljeno');
                dialog.sadrzaj('Postavke artikla su spremljene!');
                dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');

            } else {
                $(element).closest('form').find('table tr.poruka td').append(odgovor.Poruka);
            }
        },
        error: function () {
            Dialog.dialogOcisti();
            dialog.naslov('Greška');
            dialog.naslov('Dogodila se greška prilikom učitavanja podataka, molimo kontaktirajte administratora');
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
        },
        complete: function (odgovor) {
            $_Artikli();
        }
    });

    return false;

};

/**
 * Dohvati obavijesti.
 *
 * @param {object} element
 * @param {int} $broj_stranice
 * @param {string} $poredaj
 * @param {string} $redoslijed
 */
$_Obavijesti = function (element = '', $broj_stranice = 1, $poredaj = 'Obavijest', $redoslijed = 'asc') {

    let podatci = $('form[data-oznaka="obavijesti_lista"]').serializeArray();

    $.ajax({
        type: 'POST',
        url: '/administrator/obavijesti/lista/' + $broj_stranice + '/' + $poredaj + '/' + $redoslijed,
        dataType: 'json',
        data: podatci,
        success: function (odgovor) {
            $.ajax({
                type: 'POST',
                url: '/administrator/obavijesti/lista/' + $broj_stranice + '/' + $poredaj + '/' + $redoslijed,
                dataType: 'json',
                data: podatci,
                beforeSend: function () {
                    $('form[data-oznaka="obavijesti_lista"] > section table tbody').empty().html('<tr><td colspan="3">' + Loader_Krug + '</td></tr>');
                },
                success: function (odgovor) {
                    $('form[data-oznaka="obavijesti_lista"] > section table tbody').empty();
                    let Obavijesti = odgovor.Obavijesti;
                    $.each(Obavijesti, function (a, Obavijest) {
                        if (Obavijest.Aktivan === "1") {Obavijest.Aktivan = '\
                    <label data-boja="boja" class="kontrolni_okvir">\
                        <input type="checkbox" disabled checked><span class="kontrolni_okvir"><span class="ukljuceno"></span></span>\
                    </label>\
                ';} else {Obavijest.Aktivan = '\
                    <label data-boja="boja" class="kontrolni_okvir">\
                        <input type="checkbox" disabled><span class="kontrolni_okvir"><span class="ukljuceno"></span></span>\
                    </label>\
                ';}
                        $('form[data-oznaka="obavijesti_lista"] > section table tbody').append('\
                    <tr>\
                        <td>'+ Obavijest.ID +'</td>\
                        <td>'+ Obavijest.Obavijest +'</td>\
                        <td></td>\
                    </tr>\
                ');
                    });
                    // zaglavlje
                    let Zaglavlje = odgovor.Zaglavlje;
                    $('form[data-oznaka="obavijesti_lista"] > section div.sadrzaj > table thead').empty().append(Zaglavlje);
                    // navigacija
                    let Navigacija = odgovor.Navigacija;
                    $('form[data-oznaka="obavijesti_lista"] > section div.kontrole').empty().append('<ul class="navigacija">' + Navigacija.pocetak + '' + Navigacija.stranice + '' + Navigacija.kraj + '</ul>');

                },
                error: function () {
                },
                complete: function (odgovor) {
                    //
                }
            });
        },
        error: function () {
        }
    });

    return false;

};

/**
 * Spremi sliku artikla.
 */
$(function() {

    $("body").on('submit', 'form[data-oznaka="artikl"]', function() {

        let oznaka = $(this).data("oznaka");

        $_ArtiklSpremiSliku('form[data-oznaka="' + oznaka + '"]');

        return false;

    }).on('change','form[data-oznaka="artikl"] input[type="file"]', function() {

        let oznaka = $(this).closest('form').data("oznaka");

        $('form[data-oznaka="' + oznaka + '"]').submit();

        return false;

    });

});
$_ArtiklSpremiSliku = function ($url) {

    // dialog prozor
    let dialog = new Dialog();

    $($url).ajaxSubmit({
        beforeSend: function() {
            Dialog.dialogOtvori(false);
            dialog.naslov('Dodajem sliku');
            dialog.sadrzaj('' +
                '<div class="progres" style="display: block;">\
                    <div class="bar" style="width: 0%;"></div>\
                    <div class="postotak">0%</div>\
                </div>'
            );
        },
        uploadProgress: function(event, position, total, postotakZavrseno) {
            $('#dialog .sadrzaj .bar').width(postotakZavrseno + '%');
            $('#dialog .sadrzaj .postotak').html(postotakZavrseno + '%');
        },
        success: function(odgovor) {
            Dialog.dialogOcisti();
            dialog.naslov('Dodajem sliku');
            dialog.sadrzaj(odgovor.Poruka);
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">U redu</button>');
        },
        error: function () {
            Dialog.dialogOcisti();
            dialog.naslov('Greška');
            dialog.naslov('Dogodila se greška prilikom učitavanja podataka, molimo kontaktirajte administratora');
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
        },
        complete: function(odgovor) {
            //$_Artikl($id);
        }
    });

    return false;

};
$_ArtiklIzbrisiSliku = function ($slika) {

    let artikl_forma = $('form[data-oznaka="artikl"]');

    let $id = artikl_forma.data("sifra");

    let $podatci = artikl_forma.serializeArray();

    $.ajax({
        type: 'POST',
        url: '/administrator/artikli/izbrisisliku/' + $slika,
        dataType: 'json',
        data: $podatci,
        beforeSend: function () {
            //
        },
        success: function (odgovor) {
            if (odgovor.Validacija === "da") {

            } else {
                //$(element).closest('form').find('table tr.poruka td').append(odgovor.Poruka);
            }
        },
        error: function () {
        },
        complete: function (odgovor) {
            $_Artikl($id);
        }
    });

    return false;

};