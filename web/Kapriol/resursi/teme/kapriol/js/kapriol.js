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

        let url = $(this).attr("href");
        let vrsta = $(this).data("vrsta");
        let okvir = $(this).parent().parent().parent().find('div.slika');

        if (vrsta === 'slika') {
            $(okvir).html('<img src="'+url+'" alt="" loading="lazy">');
        } else if (vrsta === 'video') {
            $(okvir).html('\
                <video controls="controls" playsinline preload="none">\
                    <source src="'+url+'" type="video/mp4">\
                    Vaš pretraživač ne podržava ovaj video zapis.\
                </video>\
            ');
        } else if (vrsta === 'pdf') {
            $(okvir).html('\
                <object data="'+url+'?#zoom=100&scrollbar=1&toolbar=1&navpanes=1" type="application/pdf">\
                    <p>{{Vaš pretaživač nema PDF plugin ili datoteka ne postoji.}}}<br>{{Možete preuzeti datoteku}} <a href="'+url+'">{{ovdje}}</a>.</p>\
                </object>\
            ');
        } else if (vrsta === 'excel') {
            $(okvir).html('\
                <object data="'+url+'" type="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">\
                    <p>{{Vaš pretaživač nema Excel plugin ili datoteka ne postoji.}}<br>{{Možete preuzeti datoteku}} <a href="'+url+'">{{ovdje}}</a>.</p>\
                </object>\
            ');
        } else if (vrsta === 'word') {
            $(okvir).html('\
                <object data="'+url+'" type="application/vnd.openxmlformats-officedocument.wordprocessingml.document">\
                    <p>{{Vaš pretaživač nema Word plugin ili datoteka ne postoji.}}<br>{{Možete preuzeti datoteku}} <a href="'+url+'">{{ovdje}}</a>.</p>\
                </object>\
            ');
        }

        $('.slika > img').slika_zumiranje();

        return false;

    });
    $('.slika > img').slika_zumiranje();

});