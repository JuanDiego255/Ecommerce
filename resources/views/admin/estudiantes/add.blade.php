<div class="modal fade" id="add-estudiante-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius:14px;border:none;">
            <div class="modal-header" style="border-bottom:1px solid #f0f0f0;padding:1.25rem 1.5rem;">
                <h5 class="modal-title fw-semibold">Nuevo estudiante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <form action="{{ url('estudiantes/store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" value="{{ $tipo }}" name="tipo_est">
                    @include('admin.estudiantes.form', ['Modo' => 'crear'])
                </form>
            </div>
        </div>
    </div>
</div>
