import '../css/home.css';
require('bootstrap');
require('bootstrap-star-rating');

$(window).on('load', function() {
    let homeFilterButton = $("#home-filterButton");
    let homeMenu = $("#homeMenu");
    homeFilterButton.click(function(){  
        if (homeMenu.is(':visible') === true) {
        homeMenu.hide();
        } else {
        homeMenu.show();
        }
    })
    
    let toggle = $("#color-collapse-button");
    let collapse = $('#color-collapse');
    var button = $( '#color-collapse-button' );
    console.log(button);

    $('.homeMenu-button').click(function() {
       $(this).children(".arrow").toggleClass('rotated');
       $(this).next().toggleClass('show-collapse');
    });

});