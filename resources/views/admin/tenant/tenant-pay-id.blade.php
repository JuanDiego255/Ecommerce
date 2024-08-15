@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="container">

        <h2 class="text-center font-title"><strong>Pagos del inquilino {{$name}}</strong>
        </h2>
        <button type="button" data-bs-toggle="modal" data-bs-target="#add-pay-modal" class="btn btn-velvet">Nuevo pago</button>
        <hr class="hr-servicios">
        @include('admin.tenant.add')
        <center>
            <div class="card mt-3 mb-4">
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
                                    <option selected value="25">25 Registros</option>
                                    <option value="50">50 Registros</option>
                                </select>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card w-100 mb-4">
                <div class="table-responsive">
                    <table id="tenants-pay" class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Fecha de pago</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Total</th>                               
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Acciones</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tenants as $tenant)
                                <tr>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">{{ $tenant->payment_date }}</p>
                                    </td>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">{{ $tenant->payment }}</p>
                                    </td>
                                    
                                    <td class="align-middle">                                        
                                        <center>
                                            <form method="post" action="{{ url('/delete/pay/' . $tenant->id) }}"
                                                style="display:inline">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <button type="submit" data-bs-toggle="modal"
                                                    onclick="return confirm('Deseas borrar este pago?')"
                                                    data-bs-target="#edit-exercise-modal{{ $tenant->id }}"
                                                    class="btn btn-admin-delete" style="text-decoration: none;">Borrar</button>
                                            </form>
                                        </center>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <a href="{{ url('tenants/payments') }}" class="btn btn-velvet"
            style="text-decoration: none;">Volver</a>
        </center>
    </div>
@endsection
@section('script')
    <script>
        var dataTable = $('#tenants-pay').DataTable({
            searching: true,
            lengthChange: false,
            pageLength: 25,
            "language": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 a 0 de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "<<",
                    "sLast": "Último",
                    "sNext": ">>",
                    "sPrevious": "<<"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
        });

        function submitForm(alias) {
            var form = document.querySelector('form[name="' + alias + '"]');
            form.submit();
        }

        $('#recordsPerPage').on('change', function() {
            var recordsPerPage = parseInt($(this).val(), 25);
            dataTable.page.len(recordsPerPage).draw();
        });

        // Captura el evento input en el campo de búsqueda
        $('#searchfor').on('input', function() {
            var searchTerm = $(this).val();
            dataTable.search(searchTerm).draw();
        });
    </script>
@endsection
