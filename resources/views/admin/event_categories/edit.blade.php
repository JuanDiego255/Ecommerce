<div class="modal fade" id="edit-cat-modal{{ $category->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editCatLabel{{ $category->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="editCatLabel{{ $category->id }}">Editar categoría</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form class="form-horizontal" action="{{ route('event-categories.update', $category->id) }}" method="post"
                    autocomplete="off">
                    @csrf @method('PUT')
                    @include('admin.event_categories._form', [
                        'Modo' => 'editar',
                        'category' => $category,
                        'events' => $events,
                    ])
                </form>
            </div>

        </div>
    </div>
</div>
