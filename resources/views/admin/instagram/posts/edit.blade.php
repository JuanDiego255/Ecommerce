<div class="modal fade" id="modalEditPost{{ $post->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form method="POST" action="{{ route('instagram.posts.update', $post->id) }}">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Editar publicación #{{ $post->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-2">
                        <label>Estado</label>
                        <select name="status" class="form-control">
                            <option value="draft" {{ $post->status == 'draft' ? 'selected' : '' }}>draft</option>
                            <option value="scheduled" {{ $post->status == 'scheduled' ? 'selected' : '' }}>scheduled</option>
                            <option value="cancelled" {{ $post->status == 'cancelled' ? 'selected' : '' }}>cancelled</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label>Programado (opcional)</label>
                        <input type="datetime-local" name="scheduled_at" class="form-control"
                            value="{{ $post->scheduled_at ? $post->scheduled_at->format('Y-m-d\TH:i') : '' }}">
                    </div>

                    <div class="mb-2">
                        <label>Caption</label>
                        <textarea name="caption" rows="5" class="form-control">{{ $post->caption }}</textarea>
                    </div>

                    <div class="mb-2">
                        <label>Imágenes</label>
                        <div class="d-flex gap-2 flex-wrap">
                            @foreach ($post->media as $m)
                                <img src="{{ $m->media_url }}"
                                    style="height:70px;width:70px;object-fit:cover;border-radius:6px;">
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cerrar</button>
                    <button class="btn btn-primary" type="submit">Guardar cambios</button>
                </div>

            </form>

        </div>
    </div>
</div>
