<div class="modal modal-lg fade" id="add-products-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Productos</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="icon-selector">
                            <div class="col-md-4">
                                <div class="input-group input-group-lg input-group-static my-3 w-100">
                                    <label>Filtrar</label>
                                    <input id="icon-search" value="" oninput="filterIcons()"
                                        placeholder="Escribe para filtrar...." type="text"
                                        class="form-control form-control-lg" name="searchfor">
                                </div>
                            </div>
                            <div id="icon-list">
                                <div class="table-responsive">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-secondary font-weight-bolder opacity-7">{{ __('Producto') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($clothings as $item)
                                                <tr onclick="selectIcon('{{ $item->code }}')" class="icon-item" data-code="{{ $item->code }}" data-name="{{ $item->name }}">
                                                   
                                                    <td class="w-100">
                                                        <div class="d-flex px-2 py-1">
                                                            <div>
                                                                <a target="blank" data-fancybox="gallery"
                                                                    href="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}">
                                                                    <img src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}"
                                                                        class="avatar avatar-md me-3">
                                                                </a>
                                                            </div>
                                                            <div class="d-flex flex-column justify-content-center">
                                                                <h4 class="mb-0 text-lg">{{ $item->name }} - {{ $item->code }}</h4>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
            </div>
        </div>
    </div>
</div>
