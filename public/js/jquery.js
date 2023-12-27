
jQuery(window).scroll(function () {
    var windowScrollPosTop = jQuery(window).scrollTop();
    
    if (windowScrollPosTop >= 40) {
        
        
        
        $('#menu').css({
            "transition": "all .4s ease"
        });
        
        $('.navbar-light .navbar-nav .nav-link').css({
            "color": "#fff"
        });
        $('.navbar-light .navbar-nav .seleccionado').css({
            "color": "#fff"
        });
        $('.velvet-title').css({
            "color": "#fff"
        });
        $('#menu').removeClass('navbar-light');
        jQuery("#menu").addClass('bg-transition');
    }
    else {
        $('#menu').removeClass('bg-transition');
        jQuery("#menu").addClass('navbar-light');
       
        $('.navbar-light .navbar-nav .nav-link').css({
            "color": "#344767"
        });
        $('.navbar-light .navbar-nav .seleccionado').css({
            "color": "#344767"
        });
        $('.velvet-title').css({
            "color": "#344767"
        });
        $('#menu').css({
            "transition": "all .4s ease"
        });

        $('.navbar-light .navbar-nav .nav-link').removeClass('text-item');
    }
});


var animado = document.querySelectorAll(".animado");

function mostrarScroll() {
    let scrollTop = document.documentElement.scrollTop;
    
    for (var i = 0; i < animado.length; i++) {
        let altura = animado[i].offsetTop;
        if (altura - 600 < scrollTop) {
            animado[i].style.opacity = 1;
            animado[i].classList.add("mostrarArriba");
        }
    }
}
window.addEventListener('scroll', mostrarScroll);
//********************************Mostrar Foto********************************* */
var foto = document.querySelectorAll(".foto");
function mostrarFoto() {
    for (var i = 0; i < foto.length; i++) {
        let altura = 0;
        if (altura == 0) {
            foto[i].style.opacity = 1;
            foto[i].classList.add("mostrarAbajo");
        }
    }
}
window.addEventListener('load', mostrarFoto);
//*****************************************************Slider */
var slider = document.querySelectorAll(".slider");

function mostrarSlider() {
    for (var i = 0; i < slider.length; i++) {
        let altura = 0;
        if (altura == 0) {
            slider[i].style.opacity = 1;
            slider[i].classList.add("mostrarAbajo");
        }
    }
}
window.addEventListener('load', mostrarSlider);






