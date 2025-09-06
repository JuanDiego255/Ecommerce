<div class="modal modal-lg fade" id="add-advert-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Nuevo Anuncio</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="{{ url('advert/store') }}" method="post"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="row">
                        <div class="col-md-12 mb-3">

                            <div class="input-group input-group-static">
                                <label>{{ __('Sección') }}</label>
                                <select id="section" name="section"
                                    class="form-control form-control-lg @error('section') is-invalid @enderror"
                                    autocomplete="section" autofocus>
                                    <option selected value="inicio">{{ __('Inicio') }}
                                    </option>
                                    <option value="checkout">{{ __('Checkout') }}
                                    </option>
                                </select>
                                @error('section')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="input-group input-group-static">
                                <textarea placeholder="Contenido del anuncio..." required type="text" class="form-control form-control-lg @error('content') is-invalid @enderror" name="content"
                                    id="content"></textarea>                         
                            </div>
                        </div>
                    
                        <center>
                            <input class="btn btn-accion" type="submit"
                                value="Crear">
                        </center>
                    
                    </div>

                </form>
            </div>
            
        </div>
    </div>
</div>
