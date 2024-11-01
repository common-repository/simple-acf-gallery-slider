var $ = jQuery.noConflict();
$(document).ready(function () {
    var autoplay_status = sacfgs_attribute_object.autoplay == "true";
    var autoplay_speed = sacfgs_attribute_object.speed
        ? sacfgs_attribute_object.speed
        : 1000;
    var slider2 = $(".sacfgs-slider").slick({
        dots: true,
        infinite: false,
        pauseOnFocus: true,
        pauseOnHover: false,
        pauseOnDotsHover: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        fade: true,
        cssEase: "linear",
        arrows: true,
        autoplay: autoplay_status,
        autoplaySpeed: autoplay_speed,
    
    });
    console.log(autoplay_speed);
});
