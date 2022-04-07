ArtikalPlusMinus = function (element, $vrsta) {

    let staraVrijednost = $(element).parent().find('input[name="vrijednost"]').val();

    let pakiranje = $(element).parent().find('input[name="vrijednost"]').data("pakiranje");

    let maxpakiranje = $(element).parent().find('input[name="vrijednost"]').data("maxpakiranje");

    let novaVrijednost;

    if ($vrsta === 'plus') {

        if (staraVrijednost >= maxpakiranje) {

            novaVrijednost = maxpakiranje;

        } else if (staraVrijednost > 0) {

            novaVrijednost = parseFloat(staraVrijednost) + pakiranje;

        } else {

            novaVrijednost = pakiranje;

        }

    } else if ($vrsta === 'minus') {

        if (staraVrijednost > pakiranje) {

            novaVrijednost = parseFloat(staraVrijednost) - pakiranje;

        } else {

            novaVrijednost = pakiranje;

        }

    }

    $(element).parent().find('input[name="vrijednost"]').val(novaVrijednost);

};

$_Cookie = function ($odgovor) {

    if ($odgovor === 'da') {

        $.ajax({
            type: 'POST',
            url: '/kolacic/gdpr',
            complete: function (odgovor) {
                $("#gdpr").remove();
            }
        });

    } else {

        window.history.back();

    }

};

$(document).ready(function () {

    $('header > a.trazi').click(function() {
        $('header > form[data-oznaka="trazi_artikal"]').slideToggle("fast");
    });

    $('select[data-oznaka="redoslijed"]').on('change', function () {

        document.location.href = $(this).val();

    });
    $('select[data-oznaka="podkategorija"]').on('change', function () {

        document.location.href = $(this).val();

    });

    $('form[data-oznaka="trazi_artikal"]').submit(function (odgovor) {

        let vrijednost = $('form[data-oznaka="trazi_artikal"] input[name="trazi"]').val();

        vrijednost = vrijednost.replace('/', ' ');

        window.location.href = '/rezultat/sve/sve/sve velicine/' + vrijednost + '/naziv/asc';

        return false;

    });

    $("ul.slike a").on('click', function () {

        let slika = $(this).attr("href");
        let okvir = $(this).parent().parent().parent().find('div.slika > img');

        $(okvir).attr("src", slika);

        return false;

    });
    $('.slika > img').slika_zumiranje();


    var swiper = new Swiper(".rotator", {
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: {
            delay: 2500,
            disableOnInteraction: false
        },
        pagination: {
            el: ".swiper-pagination",
            type: "progressbar",
            clickable: true
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev"
        }
    });

    $('input[name="r1"]').on('change', function() {

        let isChecked = $('input[name="r1"]').is(':checked');

        if (isChecked) {
            $('main > div#sadrzaj.narudzba > form.podatci > section.tvrtka').show();
        } else {
            $('main > div#sadrzaj.narudzba > form.podatci > section.tvrtka').hide();
        }

    });

    tippy('#popup1', {
        content: 'Virman / opća uplatnica : plaćanje se vrši općom uplatnicom na temelju predračuna ili ponude koji će biti dostavljen nakon provjere dostupnosti naručenih artikala!',
    });
    tippy('#popup2', {
        content: 'Plaćanje pouzećem / gotovina : plaćanje se vrši gotovinski prilikom preuzimanja pošiljke!',
    });
    tippy('.sifraArtikla', {
        delay: [1000, 20],
        touch: ["hold", 500]
    });

});