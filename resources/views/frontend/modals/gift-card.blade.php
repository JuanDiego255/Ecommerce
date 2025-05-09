<div class="modal fade" id="gift-card-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Tarjeta de regalo</h5>
                <button type="button" class="btn-close text-dark"
                    @if ($tenantinfo->kind_of_features == 1) data-dismiss="modal"
                @else
                data-bs-dismiss="modal" @endif
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="{{ url('gift/store') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <h1 class="gift-title">{{ $tenantinfo->title }}</h1>
                    <div class="row">
                        <div class="input-group-gift col-md-12">
                            <label for="para">Para:</label>
                            <input required type="text" id="for" name="for">
                        </div>
                        <div class="input-group-gift col-md-12">
                            <label for="de">De:</label>
                            <input required type="text" id="by" name="by">
                        </div>
                        <div class="input-group-gift col-md-12">
                            <label for="de">E-mail (Destinatario):</label>
                            <input required type="email" id="email" name="email">
                        </div>
                        <div class="input-group-gift col-md-12">
                            <label for="monto">Monto:</label>
                            <input required type="text" id="mount" name="mount">
                        </div>
                        <div class="input-group-gift col-md-12">
                            <label for="image">Comprobante:</label>
                            <input required class="form-control" type="file" name="image">
                        </div>
                    </div>
                    <center>
                        <button class="gift-button text-center" type="submit">Comprar</button>
                    </center>
                </form>
            </div>

        </div>
    </div>
</div>
