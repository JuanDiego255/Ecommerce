<div class="modal fade" id="add-caja-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px;border:none;">
            <div class="modal-header" style="border-bottom:1px solid #f0f0f0;padding:1.25rem 1.5rem;">
                <h5 class="modal-title fw-semibold">Nueva caja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <form action="{{ url('cajas/store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @include('admin.cajas.form', ['Modo' => 'crear'])
                </form>
            </div>
        </div>
    </div>
</div>
