<div class="modal fade" id="add-cat-modal" tabindex="-1" role="dialog" aria-labelledby="addCatLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="addCatLabel">Nueva categoría</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form class="form-horizontal" action="{{ route('event-categories.store') }}" method="post"
                    autocomplete="off">
                    @csrf
                    @include('admin.event_categories._form', [
                        'Modo' => 'crear',
                        'category' => null,
                        'events' => $events,
                    ])
                </form>
            </div>

        </div>
    </div>
</div>
