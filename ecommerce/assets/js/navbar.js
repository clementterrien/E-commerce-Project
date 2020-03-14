import '../css/navbar.css';

window.onload = function () {
    var newsletter  = $('.newsletter-item');
        newsletter.mouseover(function () {
            newsletter.addClass('hover');
        });
        newsletter.mouseout(function () {
            newsletter.removeClass('hover');
        })

    var myaccount = $('.myaccount-item');
        myaccount.mouseover(function () {
            myaccount.addClass('hover');
        });
        myaccount.mouseout(function () {
            myaccount.removeClass('hover');
        })

    var favorite = $('.favorite-item');
        favorite.mouseover(function () {
            favorite.addClass('hover');
        });
        favorite.mouseout(function () {
            favorite.removeClass('hover');
        })

    var cart = $('.cart-item');
        cart.mouseover(function () {
            cart.addClass('hover');
        });
        cart.mouseout(function () {
            cart.removeClass('hover');
        })

    myaccount.click(function(){
        console.log('hello');
        myaccount.append('<div class="hello">hello!!!!!!!!</div>');
    })
    }