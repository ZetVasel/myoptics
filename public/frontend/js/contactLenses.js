/**
 * Created by Dvacom on 16.05.2017.
 */

$(document).ready(function () {


    // console.log($('#price-range').data('min'));

    var minPrice = +$('#price-range').data('min');
    var maxPrice = +$('#price-range').data('max');


    $("#polzunok").slider({


        min: Number(minPrice),

        max: Number(maxPrice),
        values: [+$("#minCost").val(), +$("#maxCost").val()],
        range: true,
        stop: function(event, ui) {
            $("input#minCost").val($("#polzunok").slider("values", 0));
            $(".leftPrice > span").text($("#polzunok").slider("values", 0));
            $("input#maxCost").val($("#polzunok").slider("values", 1));
            $(".rightPrice > span").text($("#polzunok").slider("values", 1));
            $('#filter').submit();
        },
        slide: function(event, ui){
            $("input#minCost").val($("#polzunok").slider("values", 0));
            $(".leftPrice > span").text($("#polzunok").slider("values", 0));
            $("input#maxCost").val($("#polzunok").slider("values", 1));
            $(".rightPrice > span").text($("#polzunok").slider("values", 1));
        }
    });




    //show all

    var news = 3; // - количество отображаемых фильтров
    hidenews = "Скрыть";
    shownews = "Показать еще";


    $('.featureBlock').each(function () {

        $(this).find('.seeMoreFeature').html( shownews );
        $(this).find('.seeMoreFeature').data('show', 1);
        $(this).find(".item:not(:lt("+news+"))").hide();

    });
    $(".seeMoreFeature").click(function (e){

        e.preventDefault();
        if( +$(this).data('show') == 1)
        {
            $(this).parent(".fcontent").parent(".featureBlock").find(".item:hidden").show('slow');
            $(this).html( hidenews );
            $(this).data('show', 0);
        }
        else
        {
            $(this).parent(".fcontent").parent(".featureBlock").find(".item:not(:lt("+news+"))").hide('slow');
            $(this).html( shownews );
            $(this).data('show', 1);
        }
    });




});


$('.delFeature').click(function () {
    $("input[id='"+ $(this).data('id') +"']").removeAttr('checked').closest('form').submit();
    return false;
});


$('#clear_all_filters').click(function(e){
    $("input").prop('checked', false);
    $('#filter').submit();
    e.preventDefault();
});

$('.ftitle').click(function () {
   $(this).toggleClass('close');
   $(this).next().slideToggle('slow');
});









$('.lastSeenSlider > .product > .item').hover(
    function () {
        $('#seeMore').css({"z-index":-1});
    },
    function () {
        $('#seeMore').css({"z-index":0});
    }
);



$('#featureButton').click(function () {
   $('.overlay').fadeToggle('slow');
   $('.leftBar').fadeToggle('slow');
});

$(document).ready(function () {
    $('#sort').styler();

    $('.lastSeenBlock > .lastSeenSlider').slick({
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


});
