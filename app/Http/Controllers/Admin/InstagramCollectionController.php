<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstagramAccount;
use App\Models\InstagramCaptionTemplate;
use App\Models\InstagramCollection;
use App\Models\InstagramCollectionGroup;
use App\Models\InstagramCollectionItem;
use App\Models\InstagramPost;
use App\Models\InstagramPostMedia;
use App\Domain\Instagram\Jobs\PublishInstagramPostJob;
use App\Domain\Instagram\Services\SpintaxService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class InstagramCollectionController extends Controller
{
    public function index()
    {
        $collections = InstagramCollection::orderByDesc('id')->paginate(20);
        return view('admin.instagram.collections.index', compact('collections'));
    }

    public function create()
    {
        $templates = InstagramCaptionTemplate::active()->orderBy('name')->get();
        return view('admin.instagram.collections.add', compact('templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'default_caption' => 'nullable|string',
            'caption_template_id' => 'nullable|integer|exists:instagram_caption_templates,id',
        ]);

        $tenantDomain = request()->getHost();

        $collection = InstagramCollection::create([
            'name' => $request->name,
            'notes' => $request->notes,
            'default_caption' => $request->default_caption,
            'caption_template_id' => $request->caption_template_id ?: null,
            'status' => 'draft',
            'tenant_domain' => $tenantDomain,
        ]);

        return redirect("/instagram/collections/{$collection->id}/edit")
            ->with('ok', 'Colección creada. Ahora sube las fotos.');
    }

    public function edit($id)
    {
        $collection = InstagramCollection::with([
            'items',
            'groups.items',
            'groups.post',
            'captionTemplate',
        ])->findOrFail($id);

        $templates = InstagramCaptionTemplate::active()->orderBy('name')->get();

        return view('admin.instagram.collections.edit', compact('collection', 'templates'));
    }

    public function update(Request $request, $id)
    {
        $collection = InstagramCollection::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'default_caption' => 'nullable|string',
            'caption_template_id' => 'nullable|integer|exists:instagram_caption_templates,id',
        ]);

        $collection->update([
            'name' => $request->name,
            'notes' => $request->notes,
            'default_caption' => $request->default_caption,
            'caption_template_id' => $request->caption_template_id ?: null,
        ]);

        return back()->with('ok', 'Colección actualizada.');
    }

    public function destroy($id)
    {
        $collection = InstagramCollection::with('items')->findOrFail($id);

        foreach ($collection->items as $item) {
            Storage::disk('public')->delete($item->image_path);
        }

        $collection->delete();

        return back()->with('ok', 'Colección eliminada.');
    }

    public function uploadItems(Request $request, InstagramCollection $collection)
    {
        $request->validate([
            'images' => 'required',
            'images.*' => 'image|max:8192',
        ]);

        $maxOrder = (int) $collection->items()->max('sort_order');

        foreach ($request->file('images') as $file) {
            $path = $file->store('uploads/ig-collections', 'public');
            $maxOrder++;

            InstagramCollectionItem::create([
                'instagram_collection_id' => $collection->id,
                'group_id' => null,
                'sort_order' => $maxOrder,
                'image_path' => $path,
                'original_name' => $file->getClientOriginalName(),
            ]);
        }

        return back()->with('ok', 'Imágenes subidas.');
    }

    public function deleteItem(InstagramCollection $collection, InstagramCollectionItem $item)
    {
        if ($item->instagram_collection_id !== $collection->id) {
            abort(404);
        }

        Storage::disk('public')->delete($item->image_path);
        $item->delete();

        return back()->with('ok', 'Imagen eliminada.');
    }

    /**
     * Crea un carrusel (grupo) dentro de una colección
     */
    public function createGroup(InstagramCollection $collection)
    {
        $order = (int) $collection->groups()->max('sort_order') + 1;

        $group = InstagramCollectionGroup::create([
            'instagram_collection_id' => $collection->id,
            'name' => 'Carrusel ' . $order,
            'sort_order' => $order,
        ]);

        return response()->json(['ok' => true, 'group' => $group]);
    }

    /**
     * Elimina carrusel: SOLO si no está bloqueado (no generó post).
     * Sus imágenes vuelven a "Sin asignar"
     */
    public function deleteGroup(InstagramCollection $collection, InstagramCollectionGroup $group)
    {
        if ($group->instagram_collection_id !== $collection->id) {
            abort(404);
        }

        if (!empty($group->instagram_post_id)) {
            return back()->with('error', 'Este carrusel está bloqueado porque ya generó un post.');
        }

        InstagramCollectionItem::where('group_id', $group->id)
            ->update(['group_id' => null]);

        $group->delete();

        return back()->with('ok', 'Carrusel eliminado. Sus imágenes volvieron a "Sin asignar".');
    }

    /**
     * Mover/Reordenar imágenes entre columnas (Kanban PRO)
     * - valida pertenencia a colección
     * - bloqueo: carrusel con post generado no permite cambios
     * - límite: 10 imágenes por carrusel (to_order)
     * - aplica orden destino y origen
     */
    public function moveItem(Request $request, InstagramCollection $collection)
    {
        Log::info('moveItem payload', $request->all());

        $request->validate([
            'item_id' => 'required|integer',
            'to_group_id' => 'nullable|integer',
            'from_group_id' => 'nullable|integer',
            'to_order' => 'required|array',
            'to_order.*' => 'integer',
            'from_order' => 'nullable|array',
            'from_order.*' => 'integer',
        ]);

        $fromOrder = $request->input('from_order', []);
        $toOrder   = $request->input('to_order', []);

        $itemId = (int) $request->item_id;

        $item = InstagramCollectionItem::where('id', $itemId)
            ->where('instagram_collection_id', $collection->id)
            ->firstOrFail();

        $toGroupId = $request->to_group_id ? (int) $request->to_group_id : null;
        $fromGroupId = $request->from_group_id ? (int) $request->from_group_id : null;

        // Validar que grupos pertenezcan a la colección y no estén locked
        if (!is_null($toGroupId)) {
            $toGroup = InstagramCollectionGroup::where('id', $toGroupId)
                ->where('instagram_collection_id', $collection->id)
                ->firstOrFail();

            if (!empty($toGroup->instagram_post_id)) {
                return response()->json(['ok' => false, 'message' => 'Este carrusel está bloqueado porque ya generó un post.'], 422);
            }
        }

        if (!is_null($fromGroupId)) {
            $fromGroup = InstagramCollectionGroup::where('id', $fromGroupId)
                ->where('instagram_collection_id', $collection->id)
                ->firstOrFail();

            if (!empty($fromGroup->instagram_post_id)) {
                return response()->json(['ok' => false, 'message' => 'Este carrusel está bloqueado porque ya generó un post.'], 422);
            }
        }

        // Límite 10 imágenes por carrusel destino
        if (!is_null($toGroupId) && count($request->to_order) > 10) {
            return response()->json(['ok' => false, 'message' => 'Un carrusel no puede tener más de 10 imágenes.'], 422);
        }

        // Validar IDs en arrays (pertenecen a la colección)
        $allIds = array_unique(array_merge($toOrder, $fromOrder));
        $validCount = InstagramCollectionItem::where('instagram_collection_id', $collection->id)
            ->whereIn('id', $allIds)
            ->count();

        if ($validCount !== count($allIds)) {
            return response()->json(['ok' => false, 'message' => 'El orden contiene imágenes inválidas para esta colección.'], 422);
        }

        // Mover item al destino
        $item->group_id = $toGroupId;
        $item->save();

        // Orden destino: aplica group_id destino + sort_order
        foreach ($toOrder as $index => $id) {
            InstagramCollectionItem::where('id', (int) $id)
                ->where('instagram_collection_id', $collection->id)
                ->update([
                    'group_id' => $toGroupId,
                    'sort_order' => $index + 1
                ]);
        }

        // Orden origen: asegura que origen quede ordenado con su group_id origen
        foreach ($fromOrder as $index => $id) {
            InstagramCollectionItem::where('id', (int) $id)
                ->where('instagram_collection_id', $collection->id)
                ->update([
                    'group_id' => $fromGroupId,
                    'sort_order' => $index + 1
                ]);
        }

        return response()->json(['ok' => true]);
    }

    /**
     * Generar post INDIVIDUAL por carrusel (solo 1 por group)
     */
    public function generatePostForGroup(Request $request, InstagramCollection $collection, InstagramCollectionGroup $group)
    {
        if ($group->instagram_collection_id !== $collection->id) {
            abort(404);
        }

        // Solo 1 post por carrusel
        if (!empty($group->instagram_post_id)) {
            return back()->with('error', "Este carrusel ya generó un post (ID: {$group->instagram_post_id}).");
        }

        $request->validate([
            'publish_mode' => 'required|in:now,scheduled',
            'scheduled_at' => 'nullable|string', // datetime-local
            'caption' => 'nullable|string',
            'use_template' => 'nullable|boolean',
        ]);

        $account = InstagramAccount::where('is_active', true)->latest()->first();
        if (!$account) {
            return back()->with('error', 'No hay cuenta Instagram conectada.');
        }

        $group->load('items');

        if ($group->items->count() < 1) {
            return back()->with('error', 'Este carrusel no tiene imágenes.');
        }

        if ($group->items->count() > 10) {
            return back()->with('error', 'Un carrusel no puede tener más de 10 imágenes.');
        }

        $scheduledAt = null;
        $status = 'draft';

        if ($request->publish_mode === 'scheduled') {
            if (!$request->filled('scheduled_at')) {
                return back()->with('error', 'Debes indicar fecha y hora para programar.');
            }

            $scheduledAt = Carbon::createFromFormat(
                'Y-m-d\TH:i',
                $request->scheduled_at,
                config('app.timezone')
            );

            $status = 'scheduled';
        } else {
            $status = 'publishing';
        }

        // Determinar el caption a usar
        $caption = trim((string) $request->input('caption', ''));

        // Si se solicita usar plantilla y hay una asignada, procesar spintax
        if ($request->boolean('use_template') && $collection->caption_template_id) {
            $collection->load('captionTemplate');
            if ($collection->captionTemplate) {
                $spintaxService = app(SpintaxService::class);
                $caption = $spintaxService->process($collection->captionTemplate->template_text);
            }
        } elseif ($caption === '') {
            // Fallback al caption por defecto de la colección
            $caption = $collection->default_caption ?? '';
        }

        $post = InstagramPost::create([
            'instagram_account_id' => $account->id,
            'clothing_id' => null,
            'tenant_domain' => $collection->tenant_domain,
            'type' => 'feed',
            'caption' => $caption,
            'status' => $status,
            'scheduled_at' => $scheduledAt,
        ]);

        // ✅ Bloqueo definitivo (solo 1)
        $group->update(['instagram_post_id' => $post->id]);

        foreach ($group->items()->orderBy('sort_order')->orderBy('id')->get() as $item) {
            InstagramPostMedia::create([
                'instagram_post_id' => $post->id,
                'media_path' => $item->image_path,
                'sort_order' => $item->sort_order,
            ]);
        }

        if ($request->publish_mode === 'now') {
            //$collection->update(['status' => 'publishing']);
            // Ejecuta inmediatamente (no requiere queue:work)
            Bus::dispatchSync(new PublishInstagramPostJob($post->id));
            return back()->with('ok', "Carrusel '{$group->name}' publicado (o en proceso).");
        }

        $collection->update(['status' => 'scheduled']);
        return back()->with('ok', "Carrusel '{$group->name}' programado.");
    }

    /**
     * LEGACY (batch): si aún lo usas, aquí está corregido.
     * Si no lo necesitas, puedes eliminar el route para evitar confusión.
     */
    public function generatePosts(Request $request, InstagramCollection $collection)
    {
        $request->validate([
            'per_post' => 'nullable|integer|min:1|max:10',
            'publish_mode' => 'required|in:now,scheduled',
            'first_time' => 'nullable|string',
            'gap_minutes' => 'nullable|integer|min:10|max:1440',
        ]);

        $account = InstagramAccount::where('is_active', true)->latest()->first();
        if (!$account) return back()->with('error', 'No hay cuenta Instagram conectada.');

        $items = $collection->items()->orderBy('sort_order')->orderBy('id')->get();
        if ($items->count() < 1) return back()->with('error', 'La colección no tiene imágenes.');

        $perPost = (int) ($request->per_post ?: 5);
        $gap = (int) ($request->gap_minutes ?: 60);

        $scheduledAt = null;
        if ($request->publish_mode === 'scheduled') {
            if (!$request->filled('first_time')) return back()->with('error', 'Debes indicar la primera hora para programar.');
            $scheduledAt = Carbon::createFromFormat('Y-m-d\TH:i', $request->first_time, config('app.timezone'))->utc();
        }

        $chunks = $items->chunk($perPost);
        $created = [];

        foreach ($chunks as $i => $chunk) {
            $postScheduledAt = null;
            $status = 'draft';

            if ($request->publish_mode === 'scheduled') {
                $postScheduledAt = (clone $scheduledAt)->addMinutes($gap * $i);
                $status = 'scheduled';
            }

            $post = InstagramPost::create([
                'instagram_account_id' => $account->id,
                'clothing_id' => null,
                'tenant_domain' => $collection->tenant_domain,
                'type' => 'feed',
                'caption' => $collection->default_caption ?? '',
                'status' => $status,
                'scheduled_at' => $postScheduledAt,
            ]);

            foreach ($chunk as $item) {
                InstagramPostMedia::create([
                    'instagram_post_id' => $post->id,
                    'media_path' => $item->image_path,
                    'sort_order' => $item->sort_order,
                ]);
            }

            $created[] = $post->id;

            if ($request->publish_mode === 'now') {
                dispatch(new PublishInstagramPostJob($post->id));
            }
        }

        $collection->update([
            'status' => $request->publish_mode === 'now' ? 'publishing' : 'scheduled'
        ]);

        return back()->with('ok', 'Posts generados: ' . count($created));
    }
    public function updateGroup(Request $request, InstagramCollection $collection, InstagramCollectionGroup $group)
    {
        if ($group->instagram_collection_id !== $collection->id) {
            abort(404);
        }

        // si ya generó post, bloquear cambios
        if (!empty($group->instagram_post_id)) {
            return response()->json(['ok' => false, 'message' => 'Este carrusel está bloqueado.'], 422);
        }

        $request->validate([
            'name' => 'required|string|max:60',
        ]);

        $group->update([
            'name' => $request->name,
        ]);

        return response()->json(['ok' => true, 'name' => $group->name]);
    }
}
