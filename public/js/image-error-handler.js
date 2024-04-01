document.addEventListener("DOMContentLoaded", function() {
    var images = document.querySelectorAll("img");

    images.forEach(function(image) {
        image.onerror = function() {
            handleImageError(this);
        };
    });

    function handleImageError(image) {
        var error = "Error al cargar la imagen: " + image.src;
        console.error(error);
        setTimeout(function() {
            image.src = image.src;
        }, 5);
    }
});