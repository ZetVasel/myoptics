/**
 * Created by Dvacom on 24.05.2017.
 */

function setCount(action , obj) {
    var total = +obj.parent().find('.total').text();
    // console.log(action);
    switch (action){
        case 'minus': if(total - 1 > 0) { obj.parent().find('.total').text(total - 1);} break;
        case 'plus':  obj.parent().find('.total').text(total + 1); break;
    }
}


// $('.minus').click(function () {
//     setCount('minus', $(this));
// });
// $('.plus').click(function () {
//     setCount('plus', $(this));
// });

$(document).ready(function () {
    $('.featureSelect').styler();
    $('.bigSelect').styler();
    $('.textLabel').each(function () {
        if($(this).find('.text').height() > 18){
            $(this).css({"padding-top":"8px"});
        }
    });

    if($(window).width() > 768){

        $('.num').each(function () {
            var content = $(this).parent().parent().parent('.content');
            $(this).height(content.height());
            var total =  content.find('.total');
            total.css({"margin-top" : (content.height() - total.height()) / 2 + "px"});
        });

    }


});



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
            });
        };
        return this.each(createTabs);
    };
})(jQuery);

$(document).ready(function () {
    $(".cartBg .tabs").lightTabs();
});
