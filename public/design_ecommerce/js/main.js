(function ($) {
    "use strict";

    /*[ Load page ]
    ===========================================================*/
    $(".animsition").animsition({
        inClass: 'fade-in',
        outClass: 'fade-out',
        inDuration: 1500,
        outDuration: 800,
        linkElement: '.animsition-link',
        loading: true,
        loadingParentElement: 'html',
        loadingClass: 'animsition-loading-1',
        loadingInner: '<div class="loader05"></div>',
        timeout: false,
        timeoutCountdown: 5000,
        onLoadEvent: true,
        browser: ['animation-duration', '-webkit-animation-duration'],
        overlay: false,
        overlayClass: 'animsition-overlay-slide',
        overlayParentElement: 'html',
        transition: function (url) {
            window.location.href = url;
        }
    });

    /*[ Back to top ]
    ===========================================================*/
    var windowH = $(window).height() / 2;

    $(window).on('scroll', function () {
        if ($(this).scrollTop() > windowH) {
            $("#myBtn").css('display', 'flex');
        } else {
            $("#myBtn").css('display', 'none');
        }
    });

    $('#myBtn').on("click", function () {
        $('html, body').animate({
            scrollTop: 0
        }, 300);
    });


    /*==================================================================
    [ Fixed Header ]*/
    var headerDesktop = $('.container-menu-desktop');
    var wrapMenu = $('.wrap-menu-desktop');

    if ($('.top-bar').length > 0) {
        var posWrapHeader = $('.top-bar').height();
    } else {
        var posWrapHeader = 0;
    }


    if ($(window).scrollTop() > posWrapHeader) {
        $(headerDesktop).addClass('fix-menu-desktop');
        $(wrapMenu).css('top', 0);
    } else {
        $(headerDesktop).removeClass('fix-menu-desktop');
        $(wrapMenu).css('top', posWrapHeader - $(this).scrollTop());
    }

    $(window).on('scroll', function () {
        var scroll = $(window).scrollTop();
        var logo = $("#logo-img");
        var logoOriginal = logo.data("logo-original");
        var logoScroll = logo.data("logo-scroll");

        if (scroll > posWrapHeader) {
            $(headerDesktop).addClass('fix-menu-desktop');
            $(wrapMenu).css('top', 0);
            logo.attr("src", logoScroll); // Cambia al logo de scroll
        } else {
            $(headerDesktop).removeClass('fix-menu-desktop');
            $(wrapMenu).css('top', posWrapHeader - scroll);
            logo.attr("src", logoOriginal); // Aquí debes volver al original
        }
    });



    /*==================================================================
    [ Menu mobile ]*/
    $('.btn-show-menu-mobile').on('click', function () {
        $(this).toggleClass('is-active');
        $('.menu-mobile').slideToggle();
    });

    var arrowMainMenu = $('.arrow-main-menu-m');

    for (var i = 0; i < arrowMainMenu.length; i++) {
        $(arrowMainMenu[i]).on('click', function () {
            $(this).parent().find('.sub-menu-m').slideToggle();
            $(this).toggleClass('turn-arrow-main-menu-m');
        })
    }

    $(window).resize(function () {
        if ($(window).width() >= 992) {
            if ($('.menu-mobile').css('display') == 'block') {
                $('.menu-mobile').css('display', 'none');
                $('.btn-show-menu-mobile').toggleClass('is-active');
            }

            $('.sub-menu-m').each(function () {
                if ($(this).css('display') == 'block') {
                    console.log('hello');
                    $(this).css('display', 'none');
                    $(arrowMainMenu).removeClass('turn-arrow-main-menu-m');
                }
            });

        }
    });


    /*==================================================================
    [ Show / hide modal search ]*/
    $('.js-show-modal-search').on('click', function () {
        $('.modal-search-header').addClass('show-modal-search');
        $(this).css('opacity', '0');
    });

    $('.js-hide-modal-search').on('click', function () {
        $('.modal-search-header').removeClass('show-modal-search');
        $('.js-show-modal-search').css('opacity', '1');
    });

    $('.container-search-header').on('click', function (e) {
        e.stopPropagation();
    });


    /*==================================================================
    [ Isotope ]*/
    var $topeContainer = $('.isotope-grid');
    var $filter = $('.filter-tope-group');

    // filter items on button click
    $filter.each(function () {
        $filter.on('click', 'button', function () {
            var filterValue = $(this).attr('data-filter');
            $topeContainer.isotope({
                filter: filterValue
            });
        });

    });

    // init Isotope
    $(window).on('load', function () {
        var $grid = $topeContainer.each(function () {
            $(this).isotope({
                itemSelector: '.isotope-item',
                layoutMode: 'fitRows',
                percentPosition: true,
                animationEngine: 'best-available',
                masonry: {
                    columnWidth: '.isotope-item'
                }
            });
        });
        $(document).on('click', '#btnPrev, #btnNext', function () {
            let pageUrl = $(this).data('next') || $(this).data('prev'); // Detecta si es "next" o "prev"
            if (!pageUrl) return;

            let urlParams = new URLSearchParams(new URL(pageUrl).search);
            let page = urlParams.get('page'); // Extrae el número de página
            let id = $(this).data('id');
            console.log($grid);

            $.ajax({
                method: "GET",
                url: "/paginate/" + Number(page) + "/" + id,
                success: function (response) {
                    var items = response.clothings.data;
                    var category_id = response.category_id;
                    var html = '';
                    // Construcción del HTML dinámicamente
                    items.forEach(function (item) {
                        let precio = item.price;
                        if (item.custom_size == 1) {
                            precio = item.first_price;
                        }
                        if (item.mayor_price > 0 && item.is_mayor) {
                            precio = item.mayor_price;
                        }
                        let descuento = (precio * item.discount) / 100;
                        let precioConDescuento = precio - descuento;

                        html += `
                            <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item ${item.category.toLowerCase().replace(/\s/g, '')}">
                                <div class="block2 product_data" data-attributes-filter='${JSON.stringify(Object.fromEntries(item.atributos.map(attr => [attr.attr_id, attr.ids.split("/")])))}'>
                                    <input type="hidden" class="code" name="code" value="${item.code}">
                                    <input type="hidden" class="clothing-name" name="clothing-name" value="${item.name}">
                                    <div class="block2-pic hov-img0">
                                        <img src="${item.image ? `/file/${item.image}` : '/images/producto-sin-imagen.PNG'}" 
                                            alt="IMG-PRODUCT">
                                        <a href="#" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1"
                                            data-id="${item.id}" 
                                            data-name="${item.name}" 
                                            data-discount="${item.discount}" 
                                            data-description="${item.description}"
                                            data-price="${precioConDescuento}"
                                            data-original-price="${item.price}"
                                            data-attributes='${JSON.stringify(item.atributos)}'
                                            data-category="${item.category}"
                                            data-images='${JSON.stringify(item.all_images.map(img => `/file/${img}`))}'
                                            data-image="${item.image ? `/file/${item.image}` : '/images/producto-sin-imagen.PNG'}">
                                            Detallar
                                        </a>
                                    </div>
                                    <div class="block2-txt flex-w flex-t p-t-14">
                                        <div class="block2-txt-child1 flex-col-l ">
                                            <a href="/detail-clothing/${item.id}/${category_id}" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                                ${item.name}
                                            </a>
                                            <div class="price">
                                                ₡${precioConDescuento}
                                                ${item.discount ? `<s class="text-danger">₡${item.price}</s>` : ''}
                                            </div>
                                        </div>
                                        <div class="block2-txt-child2 flex-r p-t-3">
                                            ${item.is_fav ? 
                                                `<a href="#" class="dis-block pos-relative add_favorite" data-clothing-id="${item.id}">
                                                                                                                                                                                                                    <i class="fa fa-heart text-danger"></i>
                                                                                                                                                                                                                </a>` : ''
                                            }
                                        </div>
                                    </div>
                                </div>
                            </div>`;

                    });

                    // Actualizar el contenido del contenedor
                    $('#product-container').empty().append(html);
                    //$('#circleNumber').text(response.page);

                    // Actualizar paginación
                    response.next_page_url ? $('#btnNext').data('next', response
                        .next_page_url) : $(
                        '#btnNext').removeData('next');
                    response.prev_page_url ? $('#btnPrev').data('prev', response
                        .prev_page_url) : $(
                        '#btnPrev').removeData('prev');
                    // Actualizar botones numéricos (resaltar página actual)
                    $('.page-lex-c-m').removeClass(
                        'font-weight-bold'); // Quita el bold de todos
                    $('.page-lex-c-m').addClass(
                        'text-muted'); // Quita el bold de todos
                    $(`.page-number-${response.currentPage}`).addClass(
                        'font-weight-bold'); // Agrega bold al actual
                    $(`.page-number-${response.currentPage}`).removeClass(
                        'text-muted');
                    // Reinicializar Isotope      
                    reinitGrid();
                    filterProducts();
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }
            });
        });
        $(document).on('click', '.page-lex-c-m', function () {
            let page = $(this).data('page');
            let id = $(this).data('id');

            if (!page || !id) return;

            $.ajax({
                method: "GET",
                url: "/paginate/" + Number(page) + "/" + id,
                success: function (response) {
                    var items = response.clothings.data;
                    var category_id = response.category_id;
                    var html = '';
                    // Construcción del HTML dinámicamente
                    items.forEach(function (item) {
                        let precio = item.price;
                        if (item.custom_size == 1) {
                            precio = item.first_price;
                        }
                        if (item.mayor_price > 0 && item.is_mayor) {
                            precio = item.mayor_price;
                        }
                        let descuento = (precio * item.discount) / 100;
                        let precioConDescuento = precio - descuento;

                        html += `
                            <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item ${item.category.toLowerCase().replace(/\s/g, '')}">
                                <div class="block2 product_data" data-attributes-filter='${JSON.stringify(Object.fromEntries(item.atributos.map(attr => [attr.attr_id, attr.ids.split("/")])))}'>
                                    <input type="hidden" class="code" name="code" value="${item.code}">
                                    <input type="hidden" class="clothing-name" name="clothing-name" value="${item.name}">
                                    <div class="block2-pic hov-img0">
                                        <img src="${item.image ? `/file/${item.image}` : '/images/producto-sin-imagen.PNG'}" 
                                            alt="IMG-PRODUCT">
                                        <a href="#" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1"
                                            data-id="${item.id}" 
                                            data-name="${item.name}" 
                                            data-discount="${item.discount}" 
                                            data-description="${item.description}"
                                            data-price="${precioConDescuento}"
                                            data-original-price="${item.price}"
                                            data-attributes='${JSON.stringify(item.atributos)}'
                                            data-category="${item.category}"
                                            data-images='${JSON.stringify(item.all_images.map(img => `/file/${img}`))}'
                                            data-image="${item.image ? `/file/${item.image}` : '/images/producto-sin-imagen.PNG'}">
                                            Detallar
                                        </a>
                                    </div>
                                    <div class="block2-txt flex-w flex-t p-t-14">
                                        <div class="block2-txt-child1 flex-col-l ">
                                            <a href="/detail-clothing/${item.id}/${category_id}" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                                ${item.name}
                                            </a>
                                            <div class="price">
                                                ₡${precioConDescuento}
                                                ${item.discount ? `<s class="text-danger">₡${item.price}</s>` : ''}
                                            </div>
                                        </div>
                                        <div class="block2-txt-child2 flex-r p-t-3">
                                            ${item.is_fav ? 
                                                `<a href="#" class="dis-block pos-relative add_favorite" data-clothing-id="${item.id}">
                                                                                                                                                                                                                    <i class="fa fa-heart text-danger"></i>
                                                                                                                                                                                                                </a>` : ''
                                            }
                                        </div>
                                    </div>
                                </div>
                            </div>`;

                    });

                    // Actualizar el contenido del contenedor
                    $('#product-container').empty().append(html);
                    //$('#circleNumber').text(response.page);

                    // Actualizar paginación
                    response.next_page_url ? $('#btnNext').data('next', response
                        .next_page_url) : $(
                        '#btnNext').removeData('next');
                    response.prev_page_url ? $('#btnPrev').data('prev', response
                        .prev_page_url) : $(
                        '#btnPrev').removeData('prev');
                    // Actualizar botones numéricos (resaltar página actual)
                    $('.page-lex-c-m').removeClass(
                        'font-weight-bold'); // Quita el bold de todos
                    $('.page-lex-c-m').addClass(
                        'text-muted'); // Quita el bold de todos
                    $(`.page-number-${response.currentPage}`).addClass(
                        'font-weight-bold'); // Agrega bold al actual
                    $(`.page-number-${response.currentPage}`).removeClass(
                        'text-muted');
                    // Reinicializar Isotope
                    reinitGrid();
                    filterProducts();
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });

                }
            });

        });
        $(document).on('click', '.js-show-modal1', function (e) {
            e.preventDefault();
            $('.js-modal1').addClass('show-modal1');
        });
        const activeFilters = {};
        document.querySelectorAll('.filter-link').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();

                const attrId = this.dataset.attrId;
                const valueId = this.dataset.valueId;

                this.classList.toggle('filter-link-active');

                // Manejo del estado activo
                if (!activeFilters[attrId]) {
                    activeFilters[attrId] = [];
                }

                if (this.classList.contains('filter-link-active')) {
                    if (!activeFilters[attrId].includes(valueId)) {
                        activeFilters[attrId].push(valueId);
                    }
                } else {
                    activeFilters[attrId] = activeFilters[attrId].filter(id => id !== valueId);
                    if (activeFilters[attrId].length === 0) {
                        delete activeFilters[attrId];
                    }
                }
                filterProducts();
            });
        });

        function filterProducts() {
            const items = document.querySelectorAll('.product_data');
            console.log(items);
            items.forEach(item => {
                console.log("Raw dataset value:", item.dataset.attributesFilter);

                if (!item.dataset.attributesFilter) {
                    console.warn("Elemento sin data-attributes-filter:", item);
                    return; // saltar este producto para evitar error
                }

                const attributes = JSON.parse(item.dataset.attributesFilter);
                console.log(attributes);

                let show = true;

                for (const [attrId, values] of Object.entries(activeFilters)) {
                    if (!attributes[attrId]) {
                        show = false;
                        break;
                    }

                    // ¿Al menos un valor coincide?
                    const match = values.some(v => attributes[attrId].includes(v));
                    if (!match) {
                        show = false;
                        break;
                    }
                }

                item.closest('.isotope-item').style.display = show ? '' : 'none';
            });
            reinitGrid();
        }

        function reinitGrid() {
            $grid.isotope('layout');
        }
    });

    var isotopeButton = $('.filter-tope-group button');

    $(isotopeButton).each(function () {
        $(this).on('click', function () {
            for (var i = 0; i < isotopeButton.length; i++) {
                $(isotopeButton[i]).removeClass('how-active1');
            }

            $(this).addClass('how-active1');
        });
    });

    /*==================================================================
    [ Filter / Search product ]*/
    $('.js-show-filter').on('click', function () {
        $(this).toggleClass('show-filter');
        $('.panel-filter').slideToggle(400);

        if ($('.js-show-search').hasClass('show-search')) {
            $('.js-show-search').removeClass('show-search');
            $('.panel-search').slideUp(400);
        }
    });

    $('.js-show-search').on('click', function () {
        $(this).toggleClass('show-search');
        $('.panel-search').slideToggle(400);

        if ($('.js-show-filter').hasClass('show-filter')) {
            $('.js-show-filter').removeClass('show-filter');
            $('.panel-filter').slideUp(400);
        }
    });




    /*==================================================================
    [ Cart ]*/
    $('.js-show-cart').on('click', function () {
        $('.js-panel-cart').addClass('show-header-cart');
    });

    $('.js-hide-cart').on('click', function () {
        $('.js-panel-cart').removeClass('show-header-cart');
    });

    /*==================================================================
    [ Cart ]*/
    $('.js-show-sidebar').on('click', function () {
        $('.js-sidebar').addClass('show-sidebar');
    });

    $('.js-hide-sidebar').on('click', function () {
        $('.js-sidebar').removeClass('show-sidebar');
    });

    /*==================================================================
    [ +/- num product ]*/
    $('.btn-num-product-down').on('click', function () {
        var numProduct = Number($(this).next().val());
        if (numProduct > 1) {
            $(this).next().val(numProduct - 1);
        }
    });

    $('.btn-num-product-up').on('click', function () {
        var numProduct = Number($(this).prev().val());
        let maxStock = Number($(this).prev().attr("max"));
        if (numProduct < maxStock) {
            $(this).prev().val(numProduct + 1);
        }
    });

    /*==================================================================
    [ Rating ]*/
    $('.wrap-rating').each(function () {
        var item = $(this).find('.item-rating');
        var rated = -1;
        var input = $(this).find('input');
        $(input).val(0);

        $(item).on('mouseenter', function () {
            var index = item.index(this);
            var i = 0;
            for (i = 0; i <= index; i++) {
                $(item[i]).removeClass('zmdi-star-outline');
                $(item[i]).addClass('zmdi-star');
            }

            for (var j = i; j < item.length; j++) {
                $(item[j]).addClass('zmdi-star-outline');
                $(item[j]).removeClass('zmdi-star');
            }
        });

        $(item).on('click', function () {
            var index = item.index(this);
            rated = index;
            $(input).val(index + 1);
        });

        $(this).on('mouseleave', function () {
            var i = 0;
            for (i = 0; i <= rated; i++) {
                $(item[i]).removeClass('zmdi-star-outline');
                $(item[i]).addClass('zmdi-star');
            }

            for (var j = i; j < item.length; j++) {
                $(item[j]).addClass('zmdi-star-outline');
                $(item[j]).removeClass('zmdi-star');
            }
        });
    });

    /*==================================================================
    [ Show modal1 ]*/
    $('.js-show-modal1').on('click', function (e) {
        e.preventDefault();
        $('.js-modal1').addClass('show-modal1');
    });

    $('.js-hide-modal1').on('click', function () {
        $('.js-modal1').removeClass('show-modal1');
    });
})(jQuery);
