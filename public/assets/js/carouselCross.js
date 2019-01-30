var carousel;

$(document).ready(function () {

    carousel = $("#scrolling ul");

    carousel.itemslide({
        swipe_out: true //NOTE: REMOVE THIS OPTION IF YOU WANT TO DISABLE THE SWIPING SLIDES OUT FEATURE.
    }); //initialize itemslide


    $(window).resize(function () {
        carousel.reload();
    }); //Recalculate width and center positions and sizes when window is resized

});