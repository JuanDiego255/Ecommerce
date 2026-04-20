@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ url('add-item/' . $category->id) }}">{{ $category->name }}</a></li>
    <li class="breadcrumb-item active">Carga masiva</li>
@endsection
@section('content')

    @if(session('status'))
        <div class="alert alert-{{ session('icon') === 'success' ? 'success' : 'warning' }} alert-dismissible fade show">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-header d-flex align-items-center justify-content-between mb-3">
        <div>
            <h4 class="page-header-title mb-0">Carga masiva de productos</h4>
            <div class="page-header-sub">{{ $category->name }}</div>
        </div>
        <a href="{{ url('add-item/' . $category->id) }}" class="ph-btn ph-btn-back"
           title="Volver" data-bs-toggle="tooltip" data-bs-placement="left">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>

    <form action="{{ url('bulk-upload/' . $category->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Upload area --}}
        <div class="surface p-4 mb-3">
            <div class="surface-title mb-3">Archivo CSV</div>
            <div class="row g-3 align-items-end">
                <div class="col-md-8">
                    <label class="filter-label">Seleccionar archivo (.csv) *</label>
                    <input type="file" name="csv_file" class="filter-input" accept=".csv,.txt" required
                           id="csv-file-input">
                    <span style="font-size:.72rem;color:var(--gray3);">
                        Máximo 10 MB. El sistema crea los departamentos y categorías que no existan.
                        Los productos con código duplicado se omiten automáticamente.
                    </span>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="s-btn-primary w-100">
                        <i class="fas fa-upload me-1"></i> Importar productos
                    </button>
                </div>
            </div>
        </div>

        {{-- Format reference --}}
        <div class="surface p-4 mb-3">
            <div class="surface-title mb-3">Formato del CSV</div>
            <div style="font-size:.78rem;color:var(--gray4);margin-bottom:1rem;">
                La primera fila debe ser el encabezado (puede dejarse en blanco o usar los nombres indicados).
                Las columnas deben estar en este orden exacto, separadas por coma:
            </div>
            <div style="overflow-x:auto">
                <table style="width:100%;border-collapse:collapse;font-size:.78rem">
                    <thead>
                        <tr style="background:var(--gray0)">
                            <th class="surface-title" style="padding:.5rem .75rem;border:1px solid var(--gray1)">#</th>
                            <th class="surface-title" style="padding:.5rem .75rem;border:1px solid var(--gray1)">Columna</th>
                            <th class="surface-title" style="padding:.5rem .75rem;border:1px solid var(--gray1)">Descripción</th>
                            <th class="surface-title" style="padding:.5rem .75rem;border:1px solid var(--gray1)">Obligatorio</th>
                            <th class="surface-title" style="padding:.5rem .75rem;border:1px solid var(--gray1)">Ejemplo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach([
                            ['0',  'categoria',    'Nombre de categoría. Se crea si no existe.',         'No',  'Camisetas'],
                            ['1',  'codigo',       'SKU / código único del producto.',                  'Sí',  'CAM-001'],
                            ['2',  'nombre',       'Nombre del producto.',                              'Sí',  'Camiseta blanca'],
                            ['3',  'precio',       'Precio de venta (número).',                        'Sí',  '9900'],
                            ['4',  'precio_mayor', 'Precio al por mayor. Vacío = mismo que precio.',   'No',  '8000'],
                            ['5',  'stock',        'Cantidad inicial en inventario.',                  'No',  '50'],
                            ['6',  'descripcion',  'Descripción del producto.',                        'No',  'Tela 100% algodón'],
                            ['7',  'keywords',     'Palabras clave SEO separadas por coma.',           'No',  'camiseta,ropa'],
                            ['8',  'trending',     '1 = destacado. 0 = normal.',                      'No',  '0'],
                            ['9',  'descuento',    'Porcentaje de descuento (número).',                'No',  '10'],
                            ['10', 'departamento', 'Nombre de departamento. Se crea si no existe.',    'No',  'Ropa'],
                            ['11', 'imagen_1',     'URL pública de la imagen 1.',                     'No',  'https://…/img.jpg'],
                            ['12', 'imagen_2',     'URL pública de la imagen 2.',                     'No',  ''],
                            ['13', 'imagen_3',     'URL pública de la imagen 3.',                     'No',  ''],
                            ['14', 'imagen_4',     'URL pública de la imagen 4.',                     'No',  ''],
                        ] as [$col, $name, $desc, $req, $ex])
                        <tr style="border-bottom:1px solid var(--gray0)">
                            <td style="padding:.4rem .75rem;border:1px solid var(--gray1);color:var(--gray3)">{{ $col }}</td>
                            <td style="padding:.4rem .75rem;border:1px solid var(--gray1);font-family:monospace;font-weight:600">{{ $name }}</td>
                            <td style="padding:.4rem .75rem;border:1px solid var(--gray1);color:var(--gray4)">{{ $desc }}</td>
                            <td style="padding:.4rem .75rem;border:1px solid var(--gray1);text-align:center">
                                @if($req === 'Sí')
                                    <span style="color:var(--red);font-weight:700">Sí</span>
                                @else
                                    <span style="color:var(--gray3)">No</span>
                                @endif
                            </td>
                            <td style="padding:.4rem .75rem;border:1px solid var(--gray1);font-family:monospace;font-size:.72rem;color:var(--gray4)">{{ $ex }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3 p-3" style="background:#fefce8;border:1px solid #fde047;border-radius:8px;font-size:.78rem;color:#713f12">
                <strong>Imágenes:</strong> proporcionar URLs públicas y accesibles.
                Las imágenes se descargan individualmente — si una falla, el producto se guarda igualmente sin esa imagen.
                Las URLs de Google Drive o Dropbox pueden no funcionar; preferí URLs directas a imágenes (.jpg / .png).
            </div>
        </div>

        {{-- Download sample CSV --}}
        <div class="surface p-4 mb-3">
            <div class="surface-title mb-2">Archivo de ejemplo</div>
            <p style="font-size:.8rem;color:var(--gray4);margin-bottom:.75rem">
                Descargá esta plantilla, completá tus productos y subila directamente.
            </p>
            <a id="download-sample" href="#" class="s-btn-sec" style="display:inline-flex;align-items:center;gap:6px">
                <i class="fas fa-download"></i> Descargar plantilla CSV
            </a>
        </div>

    </form>

@endsection
@section('script')
<script>
document.getElementById('download-sample').addEventListener('click', function (e) {
    e.preventDefault();
    var header = 'categoria,codigo,nombre,precio,precio_mayor,stock,descripcion,keywords,trending,descuento,departamento,imagen_1,imagen_2,imagen_3,imagen_4\n';
    var example = 'Camisetas,CAM-001,Camiseta blanca,9900,8000,50,Tela 100% algodón,"camiseta,ropa",0,0,Ropa,https://ejemplo.com/img1.jpg,,,\n';
    var blob = new Blob([header + example], { type: 'text/csv;charset=utf-8;' });
    var url  = URL.createObjectURL(blob);
    var a    = document.createElement('a');
    a.href = url; a.download = 'plantilla_productos.csv';
    a.click();
    URL.revokeObjectURL(url);
});
</script>
@endsection
