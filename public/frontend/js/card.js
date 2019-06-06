/**
 * Created by Dvacom on 18.05.2017.
 */
(function($){
    jQuery.fn.lightTabs = function(options){
        var createTabs = function(){
            tabs = this;
            i = 0;
            showPage = function(i){
                $(tabs).children("div").children("div").hide();
                $(tabs).children("div").children("div").eq(i).show();
                $(tabs).children("ul").children("li").removeClass("active");
                $(tabs).children("ul").children("li").eq(i).addClass("active");
            };
            showPage(0);
            $(tabs).children("ul").children("li").each(function(index, element){
                $(element).attr("data-page", i);
                i++;
            });

            $(tabs).children("ul").children("li").click(function(){
                showPage(parseInt($(this).attr("data-page")));
                $(this).parent().find('li').data('checkout', 0);
                $(this).data('checkout', 1);
            });
        };
        return this.each(createTabs);
    };
})(jQuery);



function setCount(action , obj) {
    var total = +obj.parent().find('input').val();
    // console.log(action);
    switch (action){
        case 'minus': if(total - 1 > 0) { obj.parent().find('.total').text(total - 1); obj.parent().find('input').val(total - 1)}; break;
        case 'plus':  obj.parent().find('.total').text(total + 1); obj.parent().find('input').val(total + 1); break;
    }
}

$(document).ready(function () {
    $('.featureSelect').styler();
    $('body').on('click','.imageSlider .item', function(){
        var img_attr = $(this).find('img').attr("src");
        var result = img_attr.replace(/sm/i, "lg");
        $('.productImage > .image').find('img').attr("src", result);
    });
    $(".productTabs .tabs").lightTabs();
    $('.minus').click(function () {
       setCount('minus', $(this));
    });
    $('.plus').click(function () {
       setCount('plus', $(this));
    });
    $('#fullRewiew').click(function () {
        $(this).css({"height": "80px"});
        $(this).css({"padding-bottom": "45px"});
        $('#rating').fadeIn('slow');
        $('#avatar').css({"top" : "32px"});
        $('.bottomRewiw').fadeIn('slow');
    });
    $('.alikeGoodsBlock > .alikeGoodsSlider').slick({
        dots: false,
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 4,
        responsive: [
            {
                breakpoint: 1199,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    dots: false,
                    infinite: true
                }
            }
        ,
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
        $('#xsSliderImage').slick({
            dots: false,
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1
        });
});