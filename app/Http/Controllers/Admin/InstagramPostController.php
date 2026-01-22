<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Instagram\Jobs\PublishInstagramPostJob;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InstagramAccount;
use App\Models\InstagramPost;
use App\Models\InstagramPostMedia;
use App\Models\ProductImage;
use App\Models\TenantInfo;
use Illuminate\Support\Facades\DB;

class InstagramPostController extends Controller
{
    public function index(Request $request)
    {
        $account = InstagramAccount::where('is_active', true)->latest()->first();

        $status = $request->get('status');
        $type   = $request->get('type');

        $posts = InstagramPost::with(['media', 'account'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($type, fn($q) => $q->where('type', $type))
            ->orderByDesc('id')
            ->paginate(20);

        // Opcional: lista de productos para el modal (si tu tabla clothing es grande, luego lo cambiamos a AJAX)
        $clothings = DB::table('clothing')
            ->select('id', 'name', 'code')
            ->orderByDesc('id')
            ->limit(200)
            ->get();

        return view('admin.instagram.posts.index', compact('account', 'posts', 'clothings', 'status', 'type'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'instagram_account_id' => 'required|exists:instagram_accounts,id',
            'type' => 'required|in:feed,story',
            'caption' => 'nullable|string',
            'clothing_id' => 'nullable|integer',
            'scheduled_at' => 'nullable|date',
            'auto_fill_images' => 'nullable|boolean',
            'media_paths' => 'nullable|array', // si el usuario elige manualmente
            'media_paths.*' => 'nullable|string',
        ]);

        $account = InstagramAccount::findOrFail($request->instagram_account_id);

        // Regla: Stories solo si account_type == business (lo dejamos preparado; fase 2)
        if ($request->type === 'story' && strtolower((string)$account->account_type) !== 'business') {
            return back()->with('error', 'Stories solo está disponible para cuentas Instagram Business.');
        }

        DB::beginTransaction();
        try {
            $tenantinfo = TenantInfo::first();
            $baseDomain = 'safeworsolutions.com'; // tu dominio base
            $tenantDomain = $tenantinfo->tenant . '.' . $baseDomain; // mitaibabyboutique.tudominio.com
            // o si $tenantinfo->tenant YA trae el subdominio completo, úsalo tal cual.

            $post = InstagramPost::create([
                'instagram_account_id' => $account->id,
                'clothing_id' => $request->clothing_id,
                'tenant_domain' => $tenantDomain,
                'type' => $request->type,
                'caption' => $request->caption,
                'status' => $request->scheduled_at ? 'scheduled' : 'draft',
                'scheduled_at' => $request->scheduled_at,
            ]);


            // 1) Si viene "auto_fill_images" y clothing_id, tomamos imágenes de product_images
            $mediaPaths = [];

            if ($request->boolean('auto_fill_images') && $request->clothing_id) {
                $mediaPaths = ProductImage::where('clothing_id', $request->clothing_id)
                    ->orderBy('id')
                    ->pluck('image')
                    ->filter()
                    ->values()
                    ->all();
            }

            // 2) Si no, usamos las rutas enviadas manualmente
            if (empty($mediaPaths) && is_array($request->media_paths)) {
                $mediaPaths = collect($request->media_paths)
                    ->filter(fn($p) => !empty($p))
                    ->values()
                    ->all();
            }

            // Validación: para feed se requiere al menos 1 imagen
            if ($post->type === 'feed' && count($mediaPaths) < 1) {
                DB::rollBack();
                return back()->with('error', 'Debes seleccionar al menos 1 imagen para el post.');
            }

            // Guardar media (máximo recomendado: 10 para carrusel)
            $mediaPaths = array_slice($mediaPaths, 0, 10);

            foreach ($mediaPaths as $i => $path) {
                InstagramPostMedia::create([
                    'instagram_post_id' => $post->id,
                    'sort_order' => $i,
                    'media_type' => 'image',
                    'media_path' => $path, // ej: uploads/xxx.jpg
                ]);
            }

            DB::commit();
            return back()->with('ok', 'Publicación creada correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Error creando publicación: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $post = InstagramPost::with('media')->findOrFail($id);

        $request->validate([
            'caption' => 'nullable|string',
            'scheduled_at' => 'nullable|date',
            'status' => 'nullable|in:draft,scheduled,cancelled',
        ]);

        // Solo permitimos editar si no está publicada
        if (in_array($post->status, ['publishing', 'published'])) {
            return back()->with('error', 'No se puede editar una publicación en proceso o ya publicada.');
        }

        $newStatus = $request->status ?? $post->status;

        // Si ponen scheduled_at, forzamos status scheduled (a menos que cancelen)
        if (!empty($request->scheduled_at) && $newStatus !== 'cancelled') {
            $newStatus = 'scheduled';
        }

        $post->update([
            'caption' => $request->caption,
            'scheduled_at' => $request->scheduled_at,
            'status' => $newStatus,
        ]);

        return back()->with('ok', 'Publicación actualizada.');
    }

    public function destroy($id)
    {
        $post = InstagramPost::findOrFail($id);

        if (in_array($post->status, ['publishing', 'published'])) {
            return back()->with('error', 'No se puede eliminar una publicación en proceso o ya publicada.');
        }

        $post->delete();
        return back()->with('ok', 'Publicación eliminada.');
    }

    public function publishNow($id)
    {
        $post = InstagramPost::with(['media', 'account'])->findOrFail($id);

        if ($post->status === 'published') {
            return back()->with('error', 'Esta publicación ya fue publicada.');
        }

        if ($post->media()->count() < 1) {
            return back()->with('error', 'La publicación no tiene imágenes.');
        }

        $post->update([
            'status' => 'scheduled',
            'scheduled_at' => now(),
        ]);

        // En el Paso 3 conectaremos el Job PublishInstagramPostJob aquí.
        dispatch(new PublishInstagramPostJob($post->id));

        return back()->with('ok', 'Publicación enviada a cola para publicar.');
    }

    public function reschedule(Request $request, $id)
    {
        $post = InstagramPost::findOrFail($id);

        $request->validate([
            'scheduled_at' => 'required|date',
        ]);

        if (in_array($post->status, ['publishing', 'published'])) {
            return back()->with('error', 'No se puede reprogramar una publicación en proceso o ya publicada.');
        }

        $post->update([
            'status' => 'scheduled',
            'scheduled_at' => $request->scheduled_at,
        ]);

        return back()->with('ok', 'Publicación reprogramada.');
    }
}
