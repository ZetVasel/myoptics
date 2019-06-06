/**
 * Created by Dvacom on 16.05.2017.
 */
//поиск
$('.search').click(function () {
    $(this).toggleClass('active');
    $('#searchForm').fadeToggle('fast');
});
$('#closeSearch').click(function () {
    $('#searchForm').fadeOut('fast');
    $('.search').toggleClass('active');
});


$('#closeLoginForm').click(function () {
    $('#loginForm').fadeOut(200);
    $('.overlay').fadeOut('fast');
    $('.profile').toggleClass('active');
});

$('.overlay').click(function () {
    $('#loginForm').fadeOut('slow');
    $(this).fadeOut('fast');
    $('#cartChangeForm').fadeOut('fast');
    $('.profile').toggleClass('active');
    $('.leftBar').fadeToggle('active');
});

//меню
$('.burger').click(function () {
    $(this).toggleClass('active');
    $('ul.menu').slideToggle('slow');
});