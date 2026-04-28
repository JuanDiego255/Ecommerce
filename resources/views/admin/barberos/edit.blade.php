<div class="modal fade" id="edit-barbero-modal{{ $barbero->id }}" tabindex="-1"
     aria-labelledby="editBarberoLabel{{ $barbero->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header" style="border-bottom:1px solid var(--border-color,#e5e7eb);padding:1rem 1.25rem;">
                <div class="d-flex align-items-center gap-2">
                    <span class="material-icons" style="color:var(--primary,#4f46e5);font-size:1.2rem;">edit</span>
                    <h5 class="modal-title mb-0 fw-semibold" id="editBarberoLabel{{ $barbero->id }}" style="font-size:1rem;">
                        Editar — {{ $barbero->nombre }}
                    </h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body" style="max-height:80vh;overflow-y:auto;padding:1.25rem;">
                <form action="{{ url('barberos/update/' . $barbero->id) }}" method="post"
                      enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    @method('PUT')

                    @include('admin.barberos.form', ['Modo' => 'editar', 'barbero' => $barbero])

                </form>
            </div>

        </div>
    </div>
</div>
