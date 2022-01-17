$(document).ready(function () {

    $('select[data-oznaka="redoslijed"]').on('change', function () {

        document.location.href = $(this).val();

    });

    $('form[data-oznaka="trazi_artikal"]').submit(function (odgovor) {

        odgovor.preventDefault();

        let vrijednost = $('form[data-oznaka="trazi_artikal"] input[name="trazi"]').val();

        vrijednost = vrijednost.replace('/', ' ');

        window.location.href = '/rezultat/sve/' + vrijednost + '/naziv/asc';

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