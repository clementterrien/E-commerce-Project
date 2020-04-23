$(window).on('load', function() {
if (window.matchMedia("(min-width: 600px)").matches) {
    console.log('hello');
     var row = $('#uuu');
     row.removeClass('col-md-4');
} else {
  /* L'affichage est inférieur à 600px de large */
}
});