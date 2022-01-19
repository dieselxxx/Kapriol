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

});