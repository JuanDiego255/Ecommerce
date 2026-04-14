var dataTable = $('#table').DataTable({
    searching: true,
    lengthChange: false,
    pageLength: 15,
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
            // Opcionalmente, puedes agregar más configuración como la personalización del título:
            title: 'Reporte PDF'
        }
    ],
    dom: 'Bfrtip', // Para colocar los botones
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

// Apply initial filter based on the status dropdown (if it exists on this page).
// Previously used a broken header-text check that never matched ("Entregado" vs "Entrega").
if ($('#recordsPerStatus').length && $('#recordsPerStatus').val()) {
    dataTable.search($('#recordsPerStatus').val()).draw();
}

$('#recordsPerPage').on('change', function () {
    var recordsPerPage = parseInt($(this).val());
    dataTable.page.len(recordsPerPage).draw();
});

$('#recordsPerStatus').on('change', function () {
    var searchTerm = $(this).val();
    dataTable.search(searchTerm).draw();
});

$('#searchfor').on('input', function () {
    var searchTerm = $(this).val();
    dataTable.search(searchTerm).draw();
});
