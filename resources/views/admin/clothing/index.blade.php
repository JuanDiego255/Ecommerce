@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@php
    $exist_attr = false;
@endphp
@section('content')
    <center>
        <h2 class="text-center font-title">
            <strong>{{ $category_name }}</strong>
        </h2>
    </center>
    <div class="row w-50">
        <div class="col-md-6">
            <a href="{{ url('new-item/' . $category_id) }}"
                class="btn btn-velvet w-100">{{ __('Agregar nuevo producto') }}</a>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-body">
            <div class="row w-100">
                <div class="col-md-4">
                    <div class="input-group input-group-lg input-group-static my-3 w-100">
                        <label>Filtrar</label>
                        <input value="" placeholder="Escribe para filtrar...." type="text"
                            class="form-control form-control-lg" name="searchfor" id="searchfor">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group input-group-lg input-group-static my-3 w-100">
                        <label>Mostrar</label>
                        <select id="recordsPerPage" name="recordsPerPage" class="form-control form-control-lg"
                            autocomplete="recordsPerPage">
                            <option value="5">5 Registros</option>
                            <option value="10">10 Registros</option>
                            <option selected value="15">15 Registros</option>
                            <option value="50">50 Registros</option>
                        </select>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group input-group-lg input-group-static my-3 w-100">
                        <label>Estado</label>
                        <select id="status" name="status" class="form-control form-control-lg" autocomplete="status">
                            <option value="2" selected>Todos</option>
                            <option value="1">Activos</option>
                            <option value="0">Inactivos</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row row-cols-1 row-cols-md-2 g-4 align-content-center card-group mt-1">

        <div class="col-md-12">
            <div class="card p-2">
                <div class="table-responsive">

                    <table class="table align-items-center mb-0" id="clothing_table">
                        <thead>
                            <tr>
                                <th class="text-secondary font-weight-bolder opacity-7">
                                    {{ __('Activo') }}</th>
                                <th class=" text-secondary font-weight-bolder opacity-7">
                                    {{ __('Acciones') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Producto') }}
                                </th>
                                <th class=" text-secondary font-weight-bolder opacity-7">
                                    {{ __('Precio') }}</th>
                                <th class=" text-secondary font-weight-bolder opacity-7">
                                    {{ __('Atributos') }}</th>
                                <th class=" text-secondary font-weight-bolder opacity-7">
                                    {{ __('Stock') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <center>

        <div class="col-md-12 mt-3">
            <a href="{{ url('categories/' . $department_id) }}" class="btn btn-velvet w-25">Volver</a>
        </div>
    </center>
@endsection
@section('script')
    <script>
        function submitForm(alias) {
            var form = document.querySelector('form[name="' + alias + '"]');
            form.submit();
        }
        $(document).ready(function() {
            var tableClothings = $('#clothing_table').DataTable({
                searching: true,
                lengthChange: false,
                pageLength: 15,
                serverSide: true, // Carga los datos desde el servidor
                ajax: {
                    url: "/add-item/{{ $category_id }}", // Ruta en Laravel
                    type: "GET"
                },
                columns: [{
                        data: "status"
                    },
                    {
                        data: "acciones",
                        orderable: false,
                        searchable: false
                    }, // Acciones
                    {
                        data: "name"
                    }, // Servicio
                    {
                        data: "price"
                    }, // Servicio
                    {
                        data: "atributos"
                    }, // Servicio
                    {
                        data: "stock"
                    }

                ],
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        titleAttr: 'Exportar a Excel',
                        className: 'btn btn-table',
                        messageTop: 'Mi reporte personalizado de Excel',
                        title: 'Reporte Excel'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        titleAttr: 'Exportar a PDF',
                        className: 'btn btn-table',
                        messageTop: 'Mi reporte personalizado de PDF',
                        title: 'Reporte PDF'
                    }
                ],
                language: {
                    sProcessing: "Procesando...",
                    sZeroRecords: "No se encontraron resultados",
                    sEmptyTable: "Ningún dato disponible en esta tabla",
                    sInfo: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    sInfoEmpty: "Mostrando 0 a 0 de 0 registros",
                    sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
                    sSearch: "Buscar:",
                    oPaginate: {
                        sFirst: "<<",
                        sLast: "Último",
                        sNext: ">>",
                        sPrevious: "<<"
                    }
                }
            });

            function getTotal(itemId, callback) {
                $.ajax({
                    method: "GET",
                    url: "/get-total-categories/" + itemId,
                    success: function(total) {
                        callback(total); // Llama al callback con el total
                    }
                });
            }
            $('#recordsPerPage').on('change', function() {
                var recordsPerPage = parseInt($(this).val());
                tableClothings.page.len(recordsPerPage).draw();
            });
            $('#searchfor').on('input', function() {
                var searchTerm = $(this).val();
                tableClothings.search(searchTerm).draw();
            });
            $(document).on('click', '.btnDeleteItem', function(e) {
                e.preventDefault();

                var itemId = $(this).data('item-id');

                // Llama a getTotal y maneja el resultado en el callback
                getTotal(itemId, function(total) {
                    let message = (total > 1) ?
                        'Este producto se encuentra ligado a más de una categoría, ¿desea borrarlo?' :
                        '¿Deseas borrar este artículo?';

                    Swal.fire({
                        title: 'Confirmación',
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Borrar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                method: "POST",
                                url: "/delete-clothing/" + itemId,
                                data: {
                                    _token: $('meta[name="csrf-token"]').attr(
                                        'content'), // Usar meta tag para CSRF token
                                    _method: 'DELETE',
                                },
                                success: function(response) {
                                    tableClothings.ajax.reload(null, false);
                                },
                                error: function(xhr, status, error) {
                                    console.error(xhr.responseText);
                                }
                            });
                        }
                    });
                });
            });
            $(document).on('change', '.changeStatus', function() {
                let itemId = $(this).val();
                let status = $(this).prop('checked') ? 1 : 0;

                $.ajax({
                    url: "/status/" + itemId,
                    method: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        status: status
                    },
                    success: function(response) {
                        Swal.fire({
                            title: "Cambio de estado",
                            text: response.message,
                            icon: "success",
                            timer: 1500,
                            showConfirmButton: false
                        });
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire({
                            title: "Error",
                            text: "No se pudo actualizar el estado",
                            icon: "error"
                        });
                    }
                });
            });
            $(document).on('change', '#status', function() {
                var status = $(this).val();
                tableClothings.ajax.url('/add-item/{{ $category_id }}?status=' + status).load();
            });

        });
    </script>
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
