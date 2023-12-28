
/* jQuery(window).scroll(function () {
    var windowScrollPosTop = jQuery(window).scrollTop();

    if (windowScrollPosTop >= 40) {

        $('#mainNavigation').css({
            "transition": "all .3s ease"
        });
        $('#btnMenu').css({
            "transition": "all .3s ease"
        });
        $('#menuHolder').css({
            "transition": "all .3s ease"
        });
        $('#btnIngresar').css({
            "transition": "all .3s ease"
        });
        $('#btnIngresarLogo').css({
            "transition": "all .3s ease"
        });
        $('#menuDrawer').css({
            "transition": "all .3s ease"
        });
        $('.nav-menu-item').css({
            "color": "#fff"
        });
        $('.velvet-title').css({
            "color": "#fff"
        });
        $('#mainNavigation').removeClass('bg-menu-velvet');
        $('#menuHolder').removeClass('bg-menu-velvet');
        $('#menuDrawer').removeClass('bg-menu-d');        
        $('#btnMenu').removeClass('whiteLink');
        $('#btnIngresar').removeClass('whiteLink');
        $('#btnIngresarLogo').removeClass('whiteLink');
        
        jQuery("#mainNavigation").addClass('bg-transition');
        jQuery("#menuHolder").addClass('bg-transition-t');
        jQuery("#menuDrawer").addClass('bg-transition');
        jQuery("#btnMenu").addClass('bg-transition-t text-white');
        jQuery("#btnIngresar").addClass('bg-transition-t text-white');   
        jQuery("#btnIngresarLogo").addClass('bg-transition-t text-white');       

    }
    else {
        $('#mainNavigation').removeClass('bg-transition');
        $('#menuHolder').removeClass('bg-transition-t');
        $('#menuDrawer').removeClass('bg-transition');
        $('#btnMenu').removeClass('bg-transition-t text-white');
        $('#btnIngresar').removeClass('bg-transition-t text-white');
        $('#btnIngresarLogo').removeClass('bg-transition-t text-white');

        jQuery("#mainNavigation").addClass('bg-menu-velvet');
        jQuery("#menuHolder").addClass('bg-menu-velvet');
        jQuery("#menuDrawer").addClass('bg-menu-d');
        jQuery("#btnMenu").addClass('whiteLink');
        jQuery("#btnIngresar").addClass('whiteLink');
        jQuery("#btnIngresarLogo").addClass('whiteLink');

        $('.navbar-light .navbar-nav .nav-link').css({
            "color": "#344767"
        });
        $('.navbar-light .navbar-nav .seleccionado').css({
            "color": "#344767"
        });
        $('.velvet-title').css({
            "color": "#344767"
        });
        $('#mainNavigation').css({
            "transition": "all .3s ease"
        });
        $('#menuHolder').css({
            "transition": "all .3s ease"
        });
        $('#menuDrawer').css({
            "transition": "all .3s ease"
        });
        $('#btnMenu').css({
            "transition": "all .3s ease"
        });
        $('#btnIngresar').css({
            "transition": "all .3s ease"
        });
        $('#btnIngresarLogo').css({
            "transition": "all .3s ease"
        });
        $('.nav-menu-item').css({
            "color": "#000"
        });
    }
});
 */

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

var menuHolder = document.getElementById('menuHolder')
var siteBrand = document.getElementById('siteBrand')
function menuToggle() {
    if (menuHolder.className === "drawMenu sticky-top") menuHolder.className = "bg-menu-velvet sticky-top"
    else menuHolder.className = "drawMenu sticky-top"
}
if (window.innerWidth < 426) siteBrand.innerHTML = "VB"
window.onresize = function () {
    if (window.innerWidth < 420) siteBrand.innerHTML = "VB"
    else siteBrand.innerHTML = "VELVET BOUTIQUE"
}





