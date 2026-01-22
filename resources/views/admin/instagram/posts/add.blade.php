<div class="modal fade" id="modalAddPost" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form method="POST" action="{{ route('instagram.posts.store') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Nueva publicación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    @include('admin.instagram.posts.form', ['post' => null])
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cerrar</button>
                    <button class="btn btn-primary" type="submit" {{ $account ? '' : 'disabled' }}>
                        Guardar
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
