@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="container">
        @include('admin.tenant-info.social-modal')
        <hr class="hr-servicios">
        @foreach ($tenant_info as $item)
            <form class="form-horizontal" action="{{ url('tenant-components/save/') }}" method="post"
                enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-dark">Mostrar u ocultar componentes</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-check form-switch col-md-6">
                                <input value="1" class="form-check-input" type="checkbox" name="manage_size" id="manage_size"
                                    @if ($item->manage_size == 1) checked @endif>
                                <label class="form-check-label" for="manage_size">Manejar Tallas</label>
                            </div>
                            <div class="form-check form-switch col-md-6">
                                <input value="1" class="form-check-input" type="checkbox" name="manage_department" id="manage_department"
                                    @if ($item->manage_department == 1) checked @endif>
                                <label class="form-check-label" for="manage_department">Manejar Departamentos</label>
                            </div>
                            <div class="form-check form-switch col-md-6">
                                <input value="1" class="form-check-input" type="checkbox" name="show_stock" id="show_stock"
                                    @if ($item->show_stock == 1) checked @endif>
                                <label class="form-check-label" for="show_stock">Mostrar Stock</label>
                            </div>
                            <div class="form-check form-switch col-md-6">
                                <input value="1" class="form-check-input" type="checkbox" name="show_insta" id="show_insta"
                                    @if ($item->show_insta == 1) checked @endif>
                                <label class="form-check-label" for="show_insta">Mostrar Seccion Instagram</label>
                            </div>
                            <div class="form-check form-switch col-md-6">
                                <input value="1" class="form-check-input" type="checkbox" name="show_trending" id="show_trending"
                                    @if ($item->show_trending == 1) checked @endif>
                                <label class="form-check-label" for="show_trending">Mostrar Productos En Tendencia</label>
                            </div>
                            <div class="form-check form-switch col-md-6">
                                <input value="1" class="form-check-input" type="checkbox" name="show_cintillo" id="show_cintillo"
                                    @if ($item->show_cintillo == 1) checked @endif>
                                <label class="form-check-label" for="show_cintillo">Mostrar Cintillo (Cinta arriba del menu
                                    principal)</label>
                            </div>
                            <div class="form-check form-switch col-md-6">
                                <input value="1" class="form-check-input" type="checkbox" name="show_mision" id="show_mision"
                                    @if ($item->show_mision == 1) checked @endif>
                                <label class="form-check-label" for="show_mision">Mostrar Misión</label>
                            </div>

                        </div>
                        <input class="btn btn-velvet mt-4 w-25" type="submit" value="Guardar Cambios">
                    </div>
                </div>
            </form>
        @endforeach


    </div>
@endsection
