<div class="modal modal-lg fade" id="add-icons-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Nuevo</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="text-dark">{{ __('Iconografía') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="icon-selector">
                            <div class="col-md-4">
                                <div class="input-group input-group-lg input-group-static my-3 w-100">
                                    <label>Filtrar</label>
                                    <input id="icon-search" value="" oninput="filterIcons()" placeholder="Escribe para filtrar...." type="text"
                                        class="form-control form-control-lg" name="searchfor" id="searchfor">
                                </div>
                            </div>
                            <div id="icon-list">
                                @foreach ($icons as $icon)
                                    <div class="icon-item" data-name="{{ $icon }}" onclick="selectIcon('{{ $icon }}')">
                                        <i class="fas fa-{{ $icon }}"></i>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>