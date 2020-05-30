import '../css/home.css';
import noUiSlider from 'nouislider';
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

    $('.homeMenu-button').click(function() {
       $(this).children(".arrow").toggleClass('rotated');
       $(this).next().toggleClass('show-collapse');
    });

    var slider = document.getElementById('price-slider');

    if(slider){
    const min = document.getElementById('min');
    const max = document.getElementById('max');
    const minValue = Math.floor(parseInt(slider.dataset.min, 10) / 10) * 10;
    const maxValue = Math.ceil(parseInt(slider.dataset.max, 10) / 10) * 10;


    const range = noUiSlider.create(slider, {
        start: [min.value || minValue , max.value || maxValue],
        connect: true,
        range: {
            'min': minValue,
            'max': maxValue
        }
    })

    range.on('slide', function (values, handle) {
        if(handle === 0) {
            min.value = Math.round(values[0])
        }
        if(handle === 1) {
            max.value = Math.round(values[1])
        }
    })
    }

})