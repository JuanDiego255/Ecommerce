<div class="modal fade" id="add-barbero-modal" tabindex="-1" role="dialog" aria-labelledby="addBarberoLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="addBarberoLabel">Nuevo barbero</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form class="form-horizontal" action="{{ url('barberos/store') }}" method="post"
                    enctype="multipart/form-data" autocomplete="off">
                    {{ csrf_field() }}

                    @include('admin.barberos.form', ['Modo' => 'crear', 'barbero' => null])

                </form>
            </div>

        </div>
    </div>
</div>
