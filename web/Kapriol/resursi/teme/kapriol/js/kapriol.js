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

$(document).ready(function () {

    $('select[data-oznaka="redoslijed"]').on('change', function () {

        document.location.href = $(this).val();

    });

    $('form[data-oznaka="trazi_artikal"]').submit(function (odgovor) {

        odgovor.preventDefault();

        let vrijednost = $('form[data-oznaka="trazi_artikal"] input[name="trazi"]').val();

        vrijednost = vrijednost.replace('/', ' ');

        window.location.href = '/rezultat/sve/sve velicine/' + vrijednost + '/naziv/asc';

        return false;

    });

    $("ul.slike a").on('click', function () {

        let slika = $(this).attr("href");
        let okvir = $(this).parent().parent().parent().find('div.slika > img');

        $(okvir).attr("src", slika);

        return false;

    });
    $('.slika > img').slika_zumiranje();


    $(function() {
        NaslovnaSlider('.obavijesti', true, 8000);
    });
    function NaslovnaSlider(element = '.obavijesti', auto = false, pause) {

        let $element = $(element);
        let obavijesti_okolo = $element.children('.obavijesti_okolo');
        let obavijesti = obavijesti_okolo.children('.obavijest');
        let pager = $element.children('.pager');

        let strelice = $element.children('.strelice');
        let proslaObavijest = strelice.children('.prije');
        let iducaobavijest = strelice.children('.dalje');

        let brojObavijesti = obavijesti.length;

        let trenutnaObavijest = obavijesti.first();
        let trenutnaObavijestIndex = 1;

        let autoPlay = null;

        obavijesti.not(':first').css('display', 'none');
        trenutnaObavijest.addClass('aktivno');

        function fadeDalje() {
            trenutnaObavijest.removeClass('aktivno').fadeOut(700);

            if(trenutnaObavijestIndex === brojObavijesti) {
                trenutnaObavijest = obavijesti.first();
                trenutnaObavijest.delay(500).addClass('aktivno').fadeIn(700);
                trenutnaObavijestIndex = 1;
            } else {
                trenutnaObavijestIndex++;
                trenutnaObavijest = trenutnaObavijest.next();
                trenutnaObavijest.delay(500).addClass('aktivno').fadeIn(700);
            }

            pager.text(trenutnaObavijestIndex+' / '+brojObavijesti);
        }

        function fadePrije() {
            trenutnaObavijest.removeClass('aktivno').fadeOut(700);

            if(trenutnaObavijestIndex === 1) {
                trenutnaObavijest = obavijesti.last();
                trenutnaObavijest.delay(500).addClass('aktivno').fadeIn();
                trenutnaObavijestIndex = brojObavijesti;
            } else {
                trenutnaObavijestIndex--;
                trenutnaObavijest = trenutnaObavijest.prev();
                trenutnaObavijest.delay(500).addClass('aktivno').fadeIn(700);
            }

            pager.text(trenutnaObavijestIndex+' / '+brojObavijesti);
        }

        function AutoPlay() {
            clearInterval(autoPlay);

            if(auto === true)
                autoPlay = setInterval(function() {fadeDalje()}, pause);
        }

        $(iducaobavijest).click(function(e) {
            e.preventDefault();
            fadeDalje();
            AutoPlay();
        });

        $(proslaObavijest).click(function(e) {
            e.preventDefault();
            fadePrije();
            AutoPlay();
        });

        AutoPlay();

    }

});