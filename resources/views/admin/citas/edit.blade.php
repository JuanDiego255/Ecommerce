<div class="modal fade" id="edit-barbero-modal{{ $barbero->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editBarberoLabel{{ $barbero->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="editBarberoLabel{{ $barbero->id }}">Editar barbero</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form class="form-horizontal" action="{{ url('barberos/update/' . $barbero->id) }}" method="post"
                    enctype="multipart/form-data" autocomplete="off">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    @include('admin.barberos.form', ['Modo' => 'editar', 'barbero' => $barbero])

                </form>
            </div>

        </div>
    </div>
</div>
