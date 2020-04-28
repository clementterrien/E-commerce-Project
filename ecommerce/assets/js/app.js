/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css'
import '../css/product-card.css'
import '../css/sidebar.css'
import '../css/navbar.css'
import '../css/home.css'
import '../css/my-account.css'
// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
import $ from 'jquery';

$(window).on('load', function() {
    let sidebarItem = $('.myaccount-sidebar-item');

    sidebarItem.mouseenter(function(){
        $(this).addClass('myaccount-sidebar-item-hover');
    })
    sidebarItem.mouseleave(function(){
        $(this).removeClass('myaccount-sidebar-item-hover');
    })
});