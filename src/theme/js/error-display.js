$(document).ready(function () {

    navHeight = $('nav div.container-fluid').outerHeight();
    windowHeight = $(window).height();
    windowWidth = $(window).width();

    $('div.content').css('height', windowHeight - navHeight - 51);
    $('.modal-scroll').css('height', windowHeight*0.8);
    $('.modal-scroll pre.pre-none').css('height', windowHeight*0.8);

    if ($('div.check-size').is(':visible') == true) {

        $('div.error-list li').tooltip({ container: 'body' });
        $('.exception-content').hide();
        $('#ignaszak-exception-content-1').show();
        $('a#1').addClass('active');

        $('.ignaszak-exception-menu').click(function () {
            $('.exception-content').hide();
            $('.ignaszak-exception-menu').removeClass('active');
            $(this).addClass('active');
            var number = $(this).attr('id');
            $('#ignaszak-exception-content-'+number).show();
        });

    } else {

        $('.modal .modal-dialog').css('width', windowWidth - 10);

    }

});