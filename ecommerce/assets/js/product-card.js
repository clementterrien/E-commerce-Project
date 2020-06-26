$(window).on('load', function() {
    let okay = $('.product-card');

    $('.product-card').mouseenter(function(){
        $(this).addClass('product-hover');
    })
    $('.product-card').mouseleave(function(){
        $(this).removeClass('product-hover');
    })
    $('button#add-to-cart-btn').mouseenter(function(){
        console.log("oklog")
        $(this).addClass('add-to-cart-hover');
    })
    $('button#add-to-cart-btn').mouseleave(function(){
        $(this).removeClass('add-to-cart-hover');
    })
});