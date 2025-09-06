<div class="modal fade" id="add-value-attr-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="exampleModalLabel">{{ __('Nuevo Valor') }}</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="{{ url('value/store/' . $id) }}" method="post"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}                   
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="input-group input-group-static mb-4">
                                <label>{{ __('Valor') }}</label>
                                <input required type="text" class="form-control form-control-lg" name="value">
                            </div>
                        </div>                                         
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-accion"> {{ __('Agregar valor') }}</button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</div>
