$(window).on('load', function() {
    let okay = $('.product-card');

    $('.product-card').mouseenter(function(){
        console.log('okayyyy');
        $(this).addClass('product-hover');
    })
    $('.product-card').mouseleave(function(){
        $(this).removeClass('product-hover');
    })
});