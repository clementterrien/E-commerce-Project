import '../css/home.css';

$(window).on('load', function() {
    
    let homeFilterButton = $("#home-filterButton");
    let homeMenu = $("#homeMenu");
    homeFilterButton.click(function(){  
        if (homeMenu.is(':visible') === true) {
        homeMenu.hide();
        } else {
        homeMenu.show();
        }
    }
    )
});