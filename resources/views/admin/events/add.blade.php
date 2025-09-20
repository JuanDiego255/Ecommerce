<div class="modal fade" id="add-evento-modal" tabindex="-1" role="dialog" aria-labelledby="addEventoLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="addEventoLabel">Nuevo evento</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form class="form-horizontal" action="{{ route('events.store') }}" method="post"
                    enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    @include('admin.events._form', ['Modo' => 'crear', 'event' => null])
                </form>
            </div>

        </div>
    </div>
</div>
