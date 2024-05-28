<div class="modal fade" id="import-product-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Seleccionar Excel</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="{{ url('import/products/'.$department_id) }}" method="post"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="col-md-12 mb-3">
                        <div class="input-group input-group-lg input-group-outline my-3">
                            <input required class="form-control" type="file" name="file">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-icon btn-3 btn-success">Importar</button>
                    </div> 
                </form>
            </div>

        </div>
    </div>
</div>
