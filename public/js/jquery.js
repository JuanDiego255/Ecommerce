
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






