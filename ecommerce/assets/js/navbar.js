import '../css/navbar.css';

window.onload = function () {

    var myaccount = $('#myaccount-container');
        myaccount.mouseenter(function () {
            myaccount.addClass('hover');
            $('#account-box').show();
        });
        myaccount.mouseleave(function () {
            myaccount.removeClass('hover');
            $('#account-box').hide();
        });

    var boxButtons = $(".box-buttons-button");
            boxButtons.mouseenter(function () {
            $(this).addClass('hover');
        });
        boxButtons.mouseleave(function () {
            $(this).removeClass('hover');
        });

    var newsletter  = $('#newsletter-container');
        newsletter.mouseover(function () {
            newsletter.addClass('hover');
            $('#newsletter-box').show();
        });
        newsletter.mouseout(function () {
            newsletter.removeClass('hover');
            $('#newsletter-box').hide();
        })

    var favorite = $('#favorite-container');
        favorite.mouseover(function () {
            favorite.addClass('hover');
        });
        favorite.mouseout(function () {
            favorite.removeClass('hover');
        })

    var cart = $('#cart-container');
        cart.mouseover(function () {
            cart.addClass('hover');
            $('#cart-box').show();
        });
        cart.mouseout(function () {
            cart.removeClass('hover');
            $('#cart-box').hide();
        })

    var wineButtons = $('.nav-element-wine-button');
    wineButtons.mouseenter(function () {
        $(this).addClass('hover');
    });
    wineButtons.mouseleave(function () {
        $(this).removeClass('hover');
    });

    var reducedNavButtons = $('.reduced-nav-btn');
    reducedNavButtons.mouseenter(function () {
        $(this).addClass('hover');
    });
    reducedNavButtons.mouseleave(function () {
        $(this).removeClass('hover');
    });

    
  
}