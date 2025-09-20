<div class="modal fade" id="edit-evento-modal{{ $event->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editEventoLabel{{ $event->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="editEventoLabel{{ $event->id }}">Editar evento</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form class="form-horizontal" action="{{ route('events.update', $event) }}" method="post"
                    enctype="multipart/form-data" autocomplete="off">
                    @csrf @method('PUT')
                    @include('admin.events._form', ['Modo' => 'editar', 'event' => $event])
                </form>
            </div>

        </div>
    </div>
</div>
