document.addEventListener("DOMContentLoaded", function () {
    var images = document.querySelectorAll("img");

    images.forEach(function (image) {
        var isLoaded = image.complete && image.naturalHeight !== 0;
        if(!isLoaded){
            handleImageError(image);
        }
    });

    function handleImageError(image) {
        var error = "Imagen cargada correctamente " + image.src;
        console.error(error);
        setTimeout(function () {
            image.src = image.src;
        }, 1);
        var isLoaded = image.complete && image.naturalHeight !== 0;
        if(!isLoaded){
            handleImageError(image);
        }
    }
});