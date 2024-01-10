
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
$('#toggleCategories').click(function () {
    $('#categoriesDropdown').slideToggle('fast');
});
$('#toggleLogout').click(function () {
    $('#logoutDropdown').slideToggle('fast');
});

$(document).ready(function () {
    var bigimage = $("#big");
    var thumbs = $("#thumbs");
    //var totalslides = 10;
    var syncedSecondary = true;

    bigimage
        .owlCarousel({
            items: 1,
            slideSpeed: 6000000000,
            nav: false,
            autoplay: false,
            dots: false,
            loop: true,
            responsiveRefreshRate: 200,
        })
        .on("changed.owl.carousel", syncPosition);

    thumbs
        .on("initialized.owl.carousel", function () {
            thumbs
                .find(".owl-item")
                .eq(0)
                .addClass("current");
        })
        .owlCarousel({
            items: 4,
            dots: false,           
            smartSpeed: 200,
            slideSpeed: 500,
            slideBy: 4,
            responsiveRefreshRate: 100
        })
        .on("changed.owl.carousel", syncPosition2);

    function syncPosition(el) {
        //if loop is set to false, then you have to uncomment the next line
        //var current = el.item.index;

        //to disable loop, comment this block
        var count = el.item.count - 1;
        var current = Math.round(el.item.index - el.item.count / 2 - 0.5);

        if (current < 0) {
            current = count;
        }
        if (current > count) {
            current = 0;
        }
        //to this
        thumbs
            .find(".owl-item")
            .removeClass("current")
            .eq(current)
            .addClass("current");
        var onscreen = thumbs.find(".owl-item.active").length - 1;
        var start = thumbs
            .find(".owl-item.active")
            .first()
            .index();
        var end = thumbs
            .find(".owl-item.active")
            .last()
            .index();

        if (current > end) {
            thumbs.data("owl.carousel").to(current, 100, true);
        }
        if (current < start) {
            thumbs.data("owl.carousel").to(current - onscreen, 100, true);
        }
    }

    function syncPosition2(el) {
        if (syncedSecondary) {
            var number = el.item.index;
            bigimage.data("owl.carousel").to(number, 100, true);
        }
    }

    thumbs.on("click", ".owl-item", function (e) {
        e.preventDefault();
        var number = $(this).index();
        bigimage.data("owl.carousel").to(number, 300, true);
    });
});





