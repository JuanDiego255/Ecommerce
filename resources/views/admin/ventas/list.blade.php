@extends('layouts.admin')
@section('content')
    @include('admin.ventas.anular')
    @include('admin.ventas.change-arqueo')
    <h2 class="text-center font-title">
        <strong>Ventas realizadas</strong>
    </h2>

    <hr class="hr-servicios">
    <div class="col-md-12 mb-2">
        <a href="{{ url('ventas/especialistas/0') }}" class="btn btn-velvet w-25">{{ __('Nueva venta') }}</a>
    </div>
    <div class="card mt-3 mb-3">
        <div class="card-body">
            <div class="row w-100">
                <div class="col-md-6">
                    <div class="input-group input-group-lg input-group-static my-3 w-100">
                        <label>Filtrar</label>
                        <input value="" placeholder="Escribe para filtrar...." type="text"
                            class="form-control form-control-lg" name="searchfor" id="searchfor">
                    </div>
                </div>
                <div class="col-md-6">
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

            </div>
        </div>
    </div>
    <div class="card p-2">
        <div class="table-responsive">
            <table id="table_ventas" class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                            Acciones</th>
                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                            Especialista</th>
                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                            Servicio</th>
                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                            Monto Venta</th>
                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                            Monto Clínica</th>
                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                            Monto Esp</th>
                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                            Monto Prod</th>
                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                            Porcentaje</th>
                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                            Tipo</th>
                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                            Cambio Arqueo</th>
                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                            Nota anulación</th>
                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                            Fecha</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            var tableVentas = $('#table_ventas').DataTable({
                searching: true,
                lengthChange: false,
                pageLength: 15,
                serverSide: true, // Carga los datos desde el servidor
                ajax: {
                    url: "/ajax/ventas", // Ruta en Laravel
                    type: "GET"
                },
                columns: [{
                        data: 'acciones',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nombre'
                    },
                    {
                        data: 'servicios'
                    },
                    {
                        data: 'monto_venta'
                    },
                    {
                        data: 'monto_clinica'
                    },
                    {
                        data: 'monto_especialista'
                    },
                    {
                        data: 'monto_producto_venta'
                    },
                    {
                        data: 'porcentaje'
                    },
                    {
                        data: 'tipo'
                    },
                    {
                        data: 'justificacion_arqueo'
                    },
                    {
                        data: 'nota_anulacion'
                    },
                    {
                        data: 'created_at'
                    },
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
        });

        function abrirAnularModal(id) {
            const modal = new bootstrap.Modal(document.getElementById('anularModal'));
            const form = document.getElementById('anularForm');
            form.action = '/anular/venta/' + id;
            document.getElementById('nota_anulacion_input').value = ''; // opcional: limpiar el input
            modal.show();
        }

        function abrirCambioArqueoModal(ventaId, createdAt) {
            const modal = new bootstrap.Modal(document.getElementById('changeArqueoModal'));
            const form = document.getElementById('changeArqueoForm');
            form.action = '/cambiar/venta/' + ventaId;

            // Limpiar valores anteriores
            document.getElementById('justificacionArqueoInput').value = '';
            const select = document.getElementById('arqueoSelect');
            select.innerHTML = '<option>Cargando...</option>';
            // Obtener arqueos válidos vía AJAX
            fetch(`/api/arqueos-validos?fecha=${createdAt}`)
                .then(response => response.json())
                .then(data => {
                    select.innerHTML = '';
                    if (data.length === 0) {
                        select.innerHTML = '<option disabled selected>No hay arqueos disponibles</option>';
                    } else {
                        data.forEach((arqueo, index) => {
                            const option = document.createElement('option');
                            option.value = arqueo.id;
                            option.text = `Inicio: ${arqueo.fecha_ini} - Cierre: ${arqueo.fecha_fin}`;
                            if (index === 0) option.selected = true;
                            select.appendChild(option);
                        });
                    }
                });

            modal.show();
        }
    </script>
@endsection
