(function ($) {
    "use strict";
    // TOP Menu Sticky
    $(window).on('scroll', function () {
        var scroll = $(window).scrollTop();
        var logo = $("#logo-img"); // Selecciona el logo
        var logoOriginal = logo.data("logo-original"); // Obtiene el logo original
        var logoScroll = logo.data("logo-scroll");
        if (scroll < 400) {
            $("#sticky-header").removeClass("sticky");
            $("#sticky-header .main-menu ul li a").removeClass("scrolled"); // Remueve la clase del color al volver arriba
            logo.attr("src", logoOriginal);
        } else {
            $("#sticky-header").addClass("sticky");
            $("#sticky-header .main-menu ul li a").addClass("scrolled"); // Agrega la clase al hacer scroll
            logo.attr("src", logoScroll);
        }
    });






    $(document).ready(function () {

        // mobile_menu
        var menu = $('ul#navigation');
        if (menu.length) {
            menu.slicknav({
                prependTo: ".mobile_menu",
                closedSymbol: '+',
                openedSymbol: '-'
            });
        };
        // blog-menu
        // $('ul#blog-menu').slicknav({
        //   prependTo: ".blog_menu"
        // });

        // review-active
        $('.slider_active').owlCarousel({
            loop: true,
            margin: 0,
            items: 1,
            autoplay: true,
            navText: ['<i class="ti-angle-left"></i>', '<i class="ti-angle-right"></i>'],
            nav: true,
            animateOut: "fadeOut", // Animación de desvanecimiento
            dots: false,
            autoplayHoverPause: true,
            autoplaySpeed: 800,
            responsive: {
                0: {
                    items: 1,
                    nav: false,
                },
                767: {
                    items: 1,
                    nav: false,
                },
                992: {
                    items: 1,
                    nav: false
                },
                1200: {
                    items: 1,
                    nav: false
                },
                1600: {
                    items: 1,
                    nav: true
                }
            }
        });

        // review-active
        $('.testmonial_active').owlCarousel({
            loop: true,
            margin: 0,
            items: 1,
            autoplay: true,
            navText: ['<i class="ti-angle-left"></i>', '<i class="ti-angle-right"></i>'],
            nav: true,
            dots: false,
            autoplayHoverPause: true,
            autoplaySpeed: 800,
            responsive: {
                0: {
                    items: 1,
                    dots: false,
                    nav: false,
                },
                767: {
                    items: 1,
                    dots: false,
                    nav: false,
                },
                992: {
                    items: 1,
                    nav: false
                },
                1200: {
                    items: 1,
                    nav: false
                },
                1500: {
                    items: 1
                }
            }
        });

        // review-active
        $('.financial_active').owlCarousel({
            loop: true,
            margin: 0,
            items: 1,
            autoplay: true,
            navText: ['<i class="ti-angle-left"></i>', '<i class="ti-angle-right"></i>'],
            nav: true,
            dots: false,
            autoplayHoverPause: true,
            autoplaySpeed: 800,

            responsive: {
                0: {
                    items: 1,
                    nav: false
                },
                767: {
                    items: 1,
                    nav: false
                },
                992: {
                    items: 1
                },
                1200: {
                    items: 1
                },
                1500: {
                    items: 1
                }
            }
        });

        // review-active
        $('.case_active').owlCarousel({
            loop: true,
            margin: 30,
            items: 1,
            autoplay: false,
            navText: ['<i class="ti-angle-left"></i>', '<i class="ti-angle-right"></i>'],
            nav: true,
            dots: true,
            autoplayHoverPause: true,
            autoplaySpeed: 800,
            // dotsData: true,
            center: false,
            responsive: {
                0: {
                    items: 1,
                    nav: false
                },
                767: {
                    items: 3,
                    nav: false
                },
                992: {
                    items: 3,
                    nav: false
                },
                1200: {
                    items: 3,
                    nav: false
                },
                1500: {
                    items: 3,
                    nav: true
                }
            }
        });

        $('.case_active_logos').owlCarousel({
            loop: true,
            margin: 30,
            items: 1,
            autoplay: true,
            navText: ['<i class="ti-angle-left"></i>', '<i class="ti-angle-right"></i>'],
            nav: true,
            dots: true,
            autoplayHoverPause: true,
            autoplaySpeed: 800,
            // dotsData: true,
            center: false,
            responsive: {
                0: {
                    items: 1,
                    nav: false
                },
                767: {
                    items: 2,
                    nav: false
                },
                992: {
                    items: 4,
                    nav: false
                },
                1200: {
                    items: 6,
                    nav: false
                },
                1500: {
                    items: 6,
                    nav: true
                }
            }
        });
        // for filter
        // init Isotope
        var $grid = $('.grid').isotope({
            itemSelector: '.grid-item',
            percentPosition: true,
            masonry: {
                // use outer width of grid-sizer for columnWidth
                columnWidth: 1
            }
        });

        // filter items on button click
        $('.portfolio-menu').on('click', 'button', function () {
            var filterValue = $(this).attr('data-filter');
            $grid.isotope({
                filter: filterValue
            });
        });

        //for menu active class
        $('.portfolio-menu button').on('click', function (event) {
            $(this).siblings('.active').removeClass('active');
            $(this).addClass('active');
            event.preventDefault();
        });

        // wow js
        new WOW().init();

        // counter 
        $('.counter').counterUp({
            delay: 10,
            time: 10000
        });

        /* magnificPopup img view */
        $('.popup-image').magnificPopup({
            type: 'image',
            gallery: {
                enabled: true
            }
        });

        /* magnificPopup img view */
        $('.img-pop-up').magnificPopup({
            type: 'image',
            gallery: {
                enabled: true
            }
        });

        /* magnificPopup video view */
        $('.popup-video').magnificPopup({
            type: 'iframe'
        });


        // scrollIt for smoth scroll
        $.scrollIt({
            upKey: 38, // key code to navigate to the next section
            downKey: 40, // key code to navigate to the previous section
            easing: 'linear', // the easing function for animation
            scrollTime: 600, // how long (in ms) the animation takes
            activeClass: 'active', // class given to the active nav element
            onPageChange: null, // function(pageIndex) that is called when page is changed
            topOffset: 0 // offste (in px) for fixed top navigation
        });

        // scrollup bottom to top
        $.scrollUp({
            scrollName: 'scrollUp', // Element ID
            topDistance: '4500', // Distance from top before showing element (px)
            topSpeed: 300, // Speed back to top (ms)
            animation: 'fade', // Fade, slide, none
            animationInSpeed: 200, // Animation in speed (ms)
            animationOutSpeed: 200, // Animation out speed (ms)
            scrollText: '<i class="fa fa-angle-double-up"></i>', // Text for element
            activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
        });


        // blog-page

        //brand-active
        $('.brand-active').owlCarousel({
            loop: true,
            margin: 30,
            items: 1,
            autoplay: true,
            nav: false,
            dots: false,
            autoplayHoverPause: true,
            autoplaySpeed: 800,
            responsive: {
                0: {
                    items: 1,
                    nav: false

                },
                767: {
                    items: 4
                },
                992: {
                    items: 7
                }
            }
        });

        // blog-dtails-page

        //project-active
        $('.project-active').owlCarousel({
            loop: true,
            margin: 30,
            items: 1,
            // autoplay:true,
            navText: ['<i class="Flaticon flaticon-left-arrow"></i>', '<i class="Flaticon flaticon-right-arrow"></i>'],
            nav: true,
            dots: false,
            // autoplayHoverPause: true,
            // autoplaySpeed: 800,
            responsive: {
                0: {
                    items: 1,
                    nav: false

                },
                767: {
                    items: 1,
                    nav: false
                },
                992: {
                    items: 2,
                    nav: false
                },
                1200: {
                    items: 1,
                },
                1501: {
                    items: 2,
                }
            }
        });

        if (document.getElementById('default-select')) {
            $('select').niceSelect();
        }

        //about-pro-active
        $('.details_active').owlCarousel({
            loop: true,
            margin: 0,
            items: 1,
            // autoplay:true,
            navText: ['<i class="ti-angle-left"></i>', '<i class="ti-angle-right"></i>'],
            nav: true,
            dots: false,
            // autoplayHoverPause: true,
            // autoplaySpeed: 800,
            responsive: {
                0: {
                    items: 1,
                    nav: false

                },
                767: {
                    items: 1,
                    nav: false
                },
                992: {
                    items: 1,
                    nav: false
                },
                1200: {
                    items: 1,
                }
            }
        });

    });

    // resitration_Form
    $(document).ready(function () {
        $('.popup-with-form').magnificPopup({
            type: 'inline',
            preloader: false,
            focus: '#name',

            // When elemened is focused, some mobile browsers in some cases zoom in
            // It looks not nice, so we disable it:
            callbacks: {
                beforeOpen: function () {
                    if ($(window).width() < 700) {
                        this.st.focus = false;
                    } else {
                        this.st.focus = '#name';
                    }
                }
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        var whatsappValue = document.getElementById("random_whats").value.trim();
        var phoneNumbers = whatsappValue.split(',').map(function (item) {
            return item.trim();
        });
        console.log(phoneNumbers);
        let remainingNumbers = [];

        // Cargar el estado desde localStorage
        function loadState() {
            const storedNumbers = localStorage.getItem('remainingNumbers');
            if (storedNumbers) {
                remainingNumbers = JSON.parse(storedNumbers);
            } else {
                remainingNumbers = [...phoneNumbers];
            }
        }

        // Guardar el estado en localStorage
        function saveState() {
            localStorage.setItem('remainingNumbers', JSON.stringify(remainingNumbers));
        }

        // Obtener un número aleatorio y actualizar el estado
        function getRandomNumber() {
            if (remainingNumbers.length === 0) {
                // Si ya se han usado todos los números, reiniciar la lista
                remainingNumbers = [...phoneNumbers];
            }

            // Seleccionar un índice aleatorio
            const randomIndex = Math.floor(Math.random() * remainingNumbers.length);
            const selectedNumber = remainingNumbers[randomIndex];

            // Eliminar el número seleccionado de la lista
            remainingNumbers.splice(randomIndex, 1);

            // Guardar el estado actualizado
            saveState();

            return selectedNumber;
        }

        // Seleccionar todos los botones de WhatsApp por clase
        const whatsappButtons = document.querySelectorAll('.whatsapp-button-click');

        // Verificar si hay al menos un botón en la página
        if (whatsappButtons.length > 0) {
            // Cargar el estado inicial de los números restantes
            loadState();

            // Función para obtener el número y abrir WhatsApp
            function openWhatsApp() {
                const number = getRandomNumber();
                console.log("Número seleccionado: ", number);
                window.open(`https://wa.me/${number}`, '_blank');
            }

            // Asignar el evento de clic a cada botón
            whatsappButtons.forEach(button => {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    openWhatsApp();
                });
            });
        } else {
            console.log("Botones de WhatsApp no encontrados");
        }

    });

    //------- Mailchimp js --------//  
    function mailChimp() {
        $('#mc_embed_signup').find('form').ajaxChimp();
    }
    mailChimp();



    // Search Toggle
    $("#search_input_box").hide();
    $("#search").on("click", function () {
        $("#search_input_box").slideToggle();
        $("#search_input").focus();
    });
    $("#close_search").on("click", function () {
        $('#search_input_box').slideUp(500);
    });
    // Search Toggle
    $("#search_input_box").hide();
    $("#search_1").on("click", function () {
        $("#search_input_box").slideToggle();
        $("#search_input").focus();
    });

})(jQuery);
