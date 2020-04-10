import '../css/navbar.css';

window.onload = function () {
    var itemContainer = $('.item-container');

    var myaccount = $('#myaccount-container');
        myaccount.mouseenter(function () {
            myaccount.addClass('hover');
            $('#account-box').show();
        });
        myaccount.mouseleave(function () {
            myaccount.removeClass('hover');
            $('#account-box').hide();
        })

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

    var adress = $('.adress-button');
    adress.mouseover(function () {
        adress.addClass('hover-button');
    });
    adress.mouseout(function () {
        adress.removeClass('hover-button');
        })

    var order = $('.order-button');
    order.mouseover(function () {
        order.addClass('hover-button');
    });
    order.mouseout(function () {
        order.removeClass('hover-button');
    });

    var personalInfos = $('.personal-information-button');
    personalInfos.mouseover(function () {
        personalInfos.addClass('hover-button');
    });
    personalInfos.mouseout(function () {
        personalInfos.removeClass('hover-button');
    });

    var reduction = $('.reduction-button');
    reduction.mouseover(function () {
        reduction.addClass('hover-button');
    });
    reduction.mouseout(function () {
        reduction.removeClass('hover-button');
    });

        var promo = $('.element-promo');
    promo.mouseover(function () {
        promo.addClass('vins-button-hover');
    });
    promo.mouseout(function () {
        promo.removeClass('vins-button-hover');
    });

        var white = $('.element-vin-blanc');
    white.mouseover(function () {
        white.addClass('vins-button-hover');
    });
    white.mouseout(function () {
        white.removeClass('vins-button-hover');
    });

        var red = $('.element-vin-rouge');
    red.mouseover(function () {
        red.addClass('vins-button-hover');
    });
    red.mouseout(function () {
        red.removeClass('vins-button-hover');
    });

        var champaign = $('.element-champagne');
    champaign.mouseover(function () {
        champaign.addClass('vins-button-hover');
    });
    champaign.mouseout(function () {
        champaign.removeClass('vins-button-hover');
    });
    
    $('.myaccount-container').click(function(){
    })
  
}