<div class="modal fade" id="edit-especialista-modal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius:14px;border:none;">
            <div class="modal-header" style="border-bottom:1px solid #f0f0f0;padding:1.25rem 1.5rem;">
                <div>
                    <h5 class="modal-title fw-semibold mb-0">Editar especialista</h5>
                    <small class="text-muted">{{ $item->nombre }}</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <form action="{{ url('especialistas/update/' . $item->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    @include('admin.especialistas.form', ['Modo' => 'editar'])
                </form>
            </div>
        </div>
    </div>
</div>
