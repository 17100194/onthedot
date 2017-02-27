/**
 * Created by Hashim on 27/02/2017.
 */

$(document).ready(function (){
    $('.notification-box').hover(function () {
        $(this).find('.drop').fadeIn(100);
    }, function () {
        $(this).find('.drop').fadeOut(100);
    });
});
