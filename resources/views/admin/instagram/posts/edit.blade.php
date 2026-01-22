<div class="modal fade" id="modalEditPost{{ $post->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form method="POST" action="{{ route('instagram.posts.update', $post->id) }}">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Editar publicación # ') }}{{ $post->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-2">
                        <div class="input-group input-group-lg input-group-outline is-filled my-3">
                            <label class="form-label">Estado</label>
                            <select name="status" class="form-control form-control-lg">
                                <option value="draft" {{ $post->status == 'draft' ? 'selected' : '' }}>Fija</option>
                                <option value="scheduled" {{ $post->status == 'scheduled' ? 'selected' : '' }}>
                                    Programado
                                </option>
                                <option value="cancelled" {{ $post->status == 'cancelled' ? 'selected' : '' }}>Cancelado
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-2">
                        <div class="input-group input-group-lg input-group-outline is-filled my-3">
                            <label class="form-label">Programar (Opcional)</label>
                            <input type="datetime-local" name="scheduled_at" class="form-control"
                                value="{{ $post->scheduled_at ? $post->scheduled_at->format('Y-m-d\TH:i') : '' }}">
                        </div>
                    </div>

                    <div class="mb-2">
                        <div class="input-group input-group-static">
                            <label>{{ __('Descripción (opcional)') }}</label>
                            <textarea name="caption" rows="1" class="form-control">{{ $post->caption }}</textarea>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label>{{ __('Imágenes') }}</label>
                        <div class="d-flex gap-2 flex-wrap">
                            @foreach ($post->media as $m)
                                <img src="{{ $m->media_url }}"
                                    style="height:70px;width:70px;object-fit:cover;border-radius:6px;">
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-accion" type="button" data-bs-dismiss="modal">Cerrar</button>
                    <button class="btn btn-accion" type="submit">Guardar cambios</button>
                </div>

            </form>

        </div>
    </div>
</div>
