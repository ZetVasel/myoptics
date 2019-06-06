/**
 * Created by Dvacom on 16.05.2017.
 */
//слайдер
$(document).ready(function () {



    //слайдер продуктов

    $('.products-slider > .row').slick({
        dots: false,
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 3,
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    dots: false,
                    infinite: true
                }
            }
        ]
    });

    $('.newsSlider').slick({
        dots: false,
        infinite: true,
        slidesToShow: 2,
        slidesToScroll: 2,
        responsive: [
            {
                breakpoint: 1199,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    dots: false,
                    infinite: true
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    dots: false,
                    infinite: true
                }
            }
        ]
    });

    $('.brandsSlider').slick({
        dots: false,
        infinite: true,
        slidesToShow: 6,
        slidesToScroll: 1,
        prevArrow: false,
        nextArrow: false,
        autoplay: true,
        autoplaySpeed: 2000,
        responsive: [
            {
                breakpoint: 1199,
                settings: {
                    slidesToShow: 6,
                    slidesToScroll: 1,
                    prevArrow: false,
                    nextArrow: false,
                    dots: false,
                    infinite: true
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    prevArrow: false,
                    nextArrow: false,
                    dots: false,
                    infinite: true
                }
            }
        ]
    });




    if($(window).width() <= '1199' ){
        $('#greenCat').appendTo('#toGreenCatSm');
        $('a.button').each(function () {
            $(this).appendTo($(this).parent('.up-block').parent('.item').find('.information'));
        });
    }

});