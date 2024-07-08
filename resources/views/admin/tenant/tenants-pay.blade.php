@extends('layouts.admin')
@section('metatag')
{!! SEOMeta::generate() !!}
{!! OpenGraph::generate() !!}
@endsection
@section('content')
<div class="container">

    <h2 class="text-center font-title"><strong>Pagos y gastos realizados</strong>
    </h2>

    <hr class="hr-servicios">
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
                                <option selected value="10">10 Registros</option>
                                <option value="25">25 Registros</option>
                                <option value="50">50 Registros</option>
                            </select>

                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 g-4 align-content-center card-group mt-1">
            <div class="col-md-8">
                <div class="card w-100 mb-4">
                    <div class="table-responsive">
                        <table id="tenants-pay" class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                        Inquilino</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                        Fecha de cobro (Siguiente)</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                        Plan</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                        Pago total</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                        Acciones</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tenants as $tenant)
                                    <tr>
                                        <td class="align-middle text-xxs text-center">
                                            <p class=" font-weight-bold mb-0">{{ $tenant->id }}</p>
                                        </td>


                                        <td class="align-middle text-xxs text-center">
                                            @php
                                                date_default_timezone_set('America/Chihuahua');
                                                $datetoday = date("Y-m-d", time());
                                            @endphp

                                            @if($datetoday >= $tenant->payment_date )
                                                <p class=" font-weight-bold mb-0">
                                                    <span class="badge badge-pill ml-2 badge-date text-white animacion"
                                                        id="comparison-count">{{ $tenant->payment_date }}</span>
                                                </p>
                                            @else
                                                <p class=" font-weight-bold mb-0">
                                                    <span
                                                        class="badge badge-pill ml-2 badge-date-blue text-white"
                                                        id="comparison-count">{{ $tenant->payment_date }}</span>
                                                </p>
                                            @endif

                                        </td>
                                        <td class="align-middle text-xxs text-center">
                                            <p class=" font-weight-bold mb-0">Plan de: {{ $tenant->plan }}</p>
                                        </td>
                                        <td class="align-middle text-xxs text-center total">
                                            <p class=" font-weight-bold mb-0">
                                                {{ number_format($tenant->total_payment) }}</p>
                                        </td>

                                        <td class="align-middle">
                                            <center>
                                                <a href="{{ url('tenant/manage-pay/' . $tenant->id) }}"
                                                    class="btn btn-velvet" style="text-decoration: none;">Ver Pagos</a>
                                            </center>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 bg-transparent">
                <div class="card mb-4">
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li
                                class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                                Pagos realizados
                                <span><strong id="payments">₡1000</strong></span>
                            </li>
                            <li
                                class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                                Gastos
                                <span><strong id="total_bills">₡1000</strong></span>
                            </li>
                            <li
                                class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                                Fondo total
                                <span><strong id="totalPayment">₡1000</strong></span>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </center>
    <button type="button" data-bs-toggle="modal" data-bs-target="#add-bill-modal" class="btn btn-velvet">Nuevo
        gasto</button>
    <hr class="hr-servicios">
    @include('admin.tenant.add-bill')
    <center>
        <div class="card mt-3 mb-4">
            <div class="card-body">
                <div class="row w-100">
                    <div class="col-md-6">
                        <div class="input-group input-group-lg input-group-static my-3 w-100">
                            <label>Filtrar</label>
                            <input value="" placeholder="Escribe para filtrar...." type="text"
                                class="form-control form-control-lg" name="searchfor_bill" id="searchfor_bill">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group input-group-lg input-group-static my-3 w-100">
                            <label>Mostrar</label>
                            <select id="recordsPerPage_bill" name="recordsPerPage_bill"
                                class="form-control form-control-lg" autocomplete="recordsPerPage">
                                <option value="5">5 Registros</option>
                                <option selected value="10">10 Registros</option>
                                <option value="25">25 Registros</option>
                                <option value="50">50 Registros</option>
                            </select>

                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="card w-100 mb-4">
            <div class="table-responsive">
                <table id="bills" class="table align-items-center mb-0">
                    <thead>
                        <tr>

                            <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                Fecha de gasto</th>
                            <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                Total</th>
                            <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                Detalle</th>
                            <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                Acciones</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bills as $bill)
                            <tr>
                                <td class="align-middle text-xxs text-center">
                                    <p class=" font-weight-bold mb-0">{{ $bill->bill_date }}</p>
                                </td>
                                <td class="align-middle text-xxs text-center">
                                    <p class=" font-weight-bold mb-0">{{ $bill->bill }}</p>
                                </td>
                                <td class="align-middle text-xxs text-center">
                                    <p class=" font-weight-bold mb-0">{{ $bill->detail }}</p>
                                </td>

                                <td class="align-middle">
                                    <center>
                                        <form method="post"
                                            action="{{ url('/delete/bill/' . $bill->id) }}"
                                            style="display:inline">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type="submit" data-bs-toggle="modal"
                                                onclick="return confirm('Deseas borrar este gasto?')"
                                                class="btn btn-admin-delete"
                                                style="text-decoration: none;">Borrar</button>
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

    calcularTotal();

    $('#recordsPerPage').on('change', function () {
        var recordsPerPage = parseInt($(this).val(), 10);
        dataTable.page.len(recordsPerPage).draw();
        calcularTotal();
    });

    // Captura el evento input en el campo de búsqueda
    $('#searchfor').on('input', function () {
        var searchTerm = $(this).val();
        dataTable.search(searchTerm).draw();
        calcularTotal();
    });

    function calcularTotal() {

        let pays = 0;
        let bills = 0;
        let total_payment = 0;
        // Obtener todas las filas de la tabla
        $('#tenants-pay tbody tr').each(function () {
            var total = parseFloat($(this).find('td:eq(3)').text().replace(/[^0-9.-]+/g, ""));
            pays += total;
        });

        $('#bills tbody tr').each(function () {
            var bill = parseFloat($(this).find('td:eq(1)').text().replace(/[^0-9.-]+/g, ""));
            bills += bill;
        });

        pays = isNaN(pays) ? 0 : pays;
        bills = isNaN(bills) ? 0 : bills;
        total_payment = pays - bills;

        // Mostrar el total actualizado en el elemento correspondiente
        const payments = document.getElementById('payments');
        const total_bills = document.getElementById('total_bills');
        const totalPayment = document.getElementById('totalPayment');

        payments.textContent = `₡${pays.toLocaleString()}`;
        total_bills.textContent = `₡${bills.toLocaleString()}`;
        totalPayment.textContent = `₡${total_payment.toLocaleString()}`;
    }

    var dataTable_bills = $('#bills').DataTable({
        searching: true,
        lengthChange: false,

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
    $('#recordsPerPage_bill').on('change', function () {
        var recordsPerPage = parseInt($(this).val(), 10);
        dataTable_bills.page.len(recordsPerPage).draw();
        calcularTotal();
    });

    // Captura el evento input en el campo de búsqueda
    $('#searchfor_bill').on('input', function () {
        var searchTerm = $(this).val();
        dataTable_bills.search(searchTerm).draw();
        calcularTotal();
    });

</script>
@endsection
