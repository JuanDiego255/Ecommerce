<div class="modal fade" id="add-servicio-modal" tabindex="-1" role="dialog" aria-labelledby="addServicioLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="addServicioLabel">Nuevo servicio</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form class="form-horizontal" action="{{ url('servicios/store') }}" method="post"
                    enctype="multipart/form-data" autocomplete="off">
                    {{ csrf_field() }}

                    @include('admin.servicios.form', ['Modo' => 'crear', 'servicio' => null])

                </form>
            </div>

        </div>
    </div>
</div>
