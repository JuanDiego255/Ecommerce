<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\ClothingCategory;
use App\Models\Department;
use App\Models\InstagramAccount;
use App\Models\InstagramCaptionTemplate;
use App\Models\InstagramCollection;
use App\Models\InstagramCollectionGroup;
use App\Models\InstagramCollectionItem;
use App\Models\InstagramPost;
use App\Models\InstagramPostMedia;
use App\Models\PivotClothingCategory;
use App\Models\ProductImage;
use App\Models\TenantInfo;
use App\Domain\Instagram\Jobs\PublishInstagramPostJob;
use App\Domain\Instagram\Services\CaptionGeneratorService;
use App\Domain\Instagram\Services\ImageAnalyzerService;
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

        // Obtener el siguiente número de código disponible para esta colección
        $nextCode = $this->getNextImageCode($collection->id);

        foreach ($request->file('images') as $file) {
            $extension = $file->getClientOriginalExtension() ?: 'jpg';

            // Generar nombre corto: ig0001.jpg, ig0002.jpg, etc.
            $shortName = sprintf('ig%04d.%s', $nextCode, strtolower($extension));

            // Guardar con el nombre corto
            $path = $file->storeAs('uploads/ig-collections', $shortName, 'public');
            $maxOrder++;
            $nextCode++;

            InstagramCollectionItem::create([
                'instagram_collection_id' => $collection->id,
                'group_id' => null,
                'sort_order' => $maxOrder,
                'image_path' => $path,
                'original_name' => $shortName, // Usar el nombre corto como display name
            ]);
        }

        return back()->with('ok', 'Imágenes subidas.');
    }

    /**
     * Obtiene el siguiente código disponible para imágenes
     */
    protected function getNextImageCode(int $collectionId): int
    {
        // Buscar el código más alto usado en cualquier imagen de esta colección
        $lastItem = InstagramCollectionItem::where('instagram_collection_id', $collectionId)
            ->where('original_name', 'like', 'ig%')
            ->orderByDesc('id')
            ->first();

        if (!$lastItem) {
            return 1;
        }

        // Extraer el número del nombre (ig0001.jpg -> 1)
        if (preg_match('/ig(\d+)\./', $lastItem->original_name, $matches)) {
            return (int) $matches[1] + 1;
        }

        return 1;
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
        $isAjax = $request->expectsJson() || $request->ajax();

        if ($group->instagram_collection_id !== $collection->id) {
            if ($isAjax) {
                return response()->json(['ok' => false, 'message' => 'Grupo no válido'], 404);
            }
            abort(404);
        }

        // Solo 1 post por carrusel
        if (!empty($group->instagram_post_id)) {
            $msg = "Este carrusel ya generó un post (ID: {$group->instagram_post_id}).";
            if ($isAjax) {
                return response()->json(['ok' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        $request->validate([
            'publish_mode' => 'required|in:now,scheduled',
            'scheduled_at' => 'nullable|string', // datetime-local
            'caption' => 'nullable|string',
            'use_template' => 'nullable|boolean',
            'analyze_images' => 'nullable|boolean',
            'create_ecommerce' => 'nullable|boolean',
            'ecommerce_price' => 'nullable|numeric|min:0',
            'ecommerce_stock' => 'nullable|integer|min:0',
            'ecommerce_analysis_data' => 'nullable|string',
            'generated_caption' => 'nullable|string',
            'caption_type' => 'nullable|string|in:instagram,ecommerce',
        ]);

        $account = InstagramAccount::where('is_active', true)->latest()->first();
        if (!$account) {
            $msg = 'No hay cuenta Instagram conectada.';
            if ($isAjax) {
                return response()->json(['ok' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        $group->load('items');

        if ($group->items->count() < 1) {
            $msg = 'Este carrusel no tiene imágenes.';
            if ($isAjax) {
                return response()->json(['ok' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        if ($group->items->count() > 10) {
            $msg = 'Un carrusel no puede tener más de 10 imágenes.';
            if ($isAjax) {
                return response()->json(['ok' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        $scheduledAt = null;
        $status = 'draft';

        if ($request->publish_mode === 'scheduled') {
            if (!$request->filled('scheduled_at')) {
                $msg = 'Debes indicar fecha y hora para programar.';
                if ($isAjax) {
                    return response()->json(['ok' => false, 'message' => $msg], 422);
                }
                return back()->with('error', $msg);
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
        $useTemplate = $request->boolean('use_template');
        $analyzeImages = $request->boolean('analyze_images');
        $generatedCaption = trim((string) $request->input('generated_caption', ''));
        $captionType = $request->input('caption_type', '');

        // Obtener rutas de imágenes para posible análisis
        $imagePaths = $group->items->pluck('image_path')->toArray();

        // PRIORIDAD 1: Si hay un caption pre-generado (desde análisis Instagram o E-commerce), usarlo directamente
        if (!empty($generatedCaption) && in_array($captionType, ['instagram', 'ecommerce'])) {
            $caption = $generatedCaption;
        }
        // PRIORIDAD 2: Si se solicita usar plantilla con análisis de imágenes
        elseif ($useTemplate && $analyzeImages && $collection->caption_template_id) {
            $captionGenerator = app(CaptionGeneratorService::class);

            // Analizar imágenes y obtener variables
            $imageData = $captionGenerator->analyzeImages($imagePaths);

            // Generar caption con la plantilla y variables de imagen
            $collection->load('captionTemplate');
            if ($collection->captionTemplate) {
                $templateCaption = $captionGenerator->generateTemplateText(
                    $collection->caption_template_id,
                    $imageData['variables']
                );
                // Agregar hashtags/CTAs solo si la plantilla no los tiene
                $caption = $captionGenerator->appendHashtagsAndCta($templateCaption ?? '');
            }
        }
        // PRIORIDAD 3: Si solo se usa plantilla sin análisis
        elseif ($useTemplate && $collection->caption_template_id) {
            $collection->load('captionTemplate');
            if ($collection->captionTemplate) {
                $spintaxService = app(SpintaxService::class);
                $templateCaption = $spintaxService->process($collection->captionTemplate->template_text);
                // Agregar hashtags/CTAs solo si la plantilla no los tiene
                $captionGenerator = app(CaptionGeneratorService::class);
                $caption = $captionGenerator->appendHashtagsAndCta($templateCaption ?? '');
            }
        }
        // PRIORIDAD 4: Caption vacío: usar generador automático completo
        elseif ($caption === '') {
            $captionGenerator = app(CaptionGeneratorService::class);
            $caption = $captionGenerator->generateForCarousel(
                $collection->caption_template_id,
                $imagePaths,
                $analyzeImages
            );

            // Si aún está vacío (no hay plantillas/CTAs/hashtags configurados), usar default
            if (trim($caption) === '') {
                $caption = $collection->default_caption ?? '';
            }
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

        // Crear producto en E-commerce si se solicita
        if ($request->boolean('create_ecommerce')) {
            $analysisData = $request->input('ecommerce_analysis_data');

            if (empty($analysisData)) {
                $msg = 'Debe analizar las imágenes antes de crear el producto en E-commerce.';
                if ($isAjax) {
                    return response()->json(['ok' => false, 'message' => $msg], 422);
                }
                return back()->with('error', $msg);
            }

            try {
                $analysis = json_decode($analysisData, true);
                $this->createEcommerceProduct(
                    $analysis,
                    $group->items()->orderBy('sort_order')->orderBy('id')->take(4)->get(),
                    $request->input('ecommerce_price'),
                    $request->input('ecommerce_stock')
                );
            } catch (\Exception $e) {
                Log::error('Error creando producto E-commerce desde Instagram: ' . $e->getMessage());
                // No retornamos error para no interrumpir la publicación de Instagram
            }
        }

        // Recargar el post para obtener datos actualizados
        $post->refresh();

        if ($request->publish_mode === 'now') {
            // Ejecuta inmediatamente (no requiere queue:work)
            Bus::dispatchSync(new PublishInstagramPostJob($post->id));

            // Recargar para obtener status actualizado después de publicar
            $post->refresh();

            $msg = "Carrusel '{$group->name}' publicado (o en proceso).";

            if ($isAjax) {
                return response()->json([
                    'ok' => true,
                    'message' => $msg,
                    'post' => [
                        'id' => $post->id,
                        'status' => $post->status,
                        'status_text' => $this->getStatusText($post->status),
                        'published_at' => $post->published_at ? Carbon::parse($post->published_at)->timezone(config('app.timezone'))->format('Y-m-d H:i') : null,
                        'error_message' => $post->error_message,
                    ],
                    'group' => [
                        'id' => $group->id,
                        'name' => $group->name,
                    ],
                ]);
            }

            return back()->with('ok', $msg);
        }

        $collection->update(['status' => 'scheduled']);
        $msg = "Carrusel '{$group->name}' programado.";

        if ($isAjax) {
            return response()->json([
                'ok' => true,
                'message' => $msg,
                'post' => [
                    'id' => $post->id,
                    'status' => $post->status,
                    'status_text' => $this->getStatusText($post->status),
                    'scheduled_at' => $scheduledAt ? $scheduledAt->timezone(config('app.timezone'))->format('Y-m-d H:i') : null,
                ],
                'group' => [
                    'id' => $group->id,
                    'name' => $group->name,
                ],
            ]);
        }

        return back()->with('ok', $msg);
    }

    /**
     * Obtiene el texto de estado legible
     */
    protected function getStatusText(string $status): string
    {
        return match ($status) {
            'scheduled' => 'Programado',
            'publishing' => 'Publicando...',
            'published' => 'Publicado',
            'failed' => 'Fallido',
            default => 'Borrador',
        };
    }

    /**
     * Analiza las imágenes de un grupo y genera un caption con las variables detectadas (AJAX)
     */
    public function analyzeGroupImages(Request $request, InstagramCollection $collection, InstagramCollectionGroup $group)
    {
        if ($group->instagram_collection_id !== $collection->id) {
            return response()->json(['ok' => false, 'message' => 'Grupo no válido'], 404);
        }

        $group->load('items');

        if ($group->items->isEmpty()) {
            return response()->json([
                'ok' => false,
                'message' => 'Este carrusel no tiene imágenes para analizar.',
            ]);
        }

        $imagePaths = $group->items->pluck('image_path')->toArray();

        $captionGenerator = app(CaptionGeneratorService::class);
        $imageAnalyzer = app(\App\Domain\Instagram\Services\ImageAnalyzerService::class);

        // Analizar imágenes
        $analysis = $imageAnalyzer->analyzeMultiple($imagePaths);

        // Generar caption completo usando la plantilla de la colección + análisis de imágenes
        $caption = $captionGenerator->generateForCarousel(
            $collection->caption_template_id,
            $imagePaths,
            true // analyze_images = true
        );

        return response()->json([
            'ok' => true,
            'caption' => $caption,
            'analysis_data' => $analysis,
        ]);
    }

    /**
     * Analiza las imágenes y genera una descripción E-commerce (AJAX)
     */
    public function analyzeEcommerce(Request $request, InstagramCollection $collection, InstagramCollectionGroup $group)
    {
        if ($group->instagram_collection_id !== $collection->id) {
            return response()->json(['ok' => false, 'message' => 'Grupo no válido'], 404);
        }

        $group->load('items');

        if ($group->items->isEmpty()) {
            return response()->json([
                'ok' => false,
                'message' => 'Este carrusel no tiene imágenes para analizar.',
            ]);
        }

        $imagePaths = $group->items->pluck('image_path')->toArray();
        $imageAnalyzer = app(\App\Domain\Instagram\Services\ImageAnalyzerService::class);
        $captionGenerator = app(CaptionGeneratorService::class);

        // Analizar imágenes
        $analysis = $imageAnalyzer->analyzeMultiple($imagePaths);

        // Generar descripción E-commerce
        $description = $imageAnalyzer->generateEcommerceDescription($analysis);

        // Agregar hashtags y CTAs de configuración
        $descriptionWithExtras = $captionGenerator->appendHashtagsAndCta($description);

        return response()->json([
            'ok' => true,
            'description' => $descriptionWithExtras,
            'analysis_data' => $analysis,
        ]);
    }

    /**
     * Crea un producto en E-commerce basado en el análisis de imágenes
     */
    protected function createEcommerceProduct(array $analysis, $items, ?float $price, ?int $stock): void
    {
        $tenantinfo = TenantInfo::first();
        $imageAnalyzer = app(ImageAnalyzerService::class);

        // Generar nombre y descripción del producto
        $productName = $imageAnalyzer->generateProductName($analysis);
        $productDescription = $imageAnalyzer->generateEcommerceDescription($analysis);

        // Obtener o crear categoría "Otros"
        $categoryId = $this->getOrCreateOtrosCategory($tenantinfo);

        // Generar código único
        $code = $this->generateProductCode();

        // Determinar manage_stock
        $manageStock = ($stock !== null && $stock > 0) ? 1 : 0;

        // Crear producto
        $clothing = new ClothingCategory();
        $clothing->name = $productName;
        $clothing->code = $code;
        $clothing->description = htmlspecialchars($productDescription);
        $clothing->price = $price ?? 0;
        $clothing->stock = $stock ?? 0;
        $clothing->manage_stock = $manageStock;
        $clothing->status = 1;
        $clothing->trending = 0;
        $clothing->is_contra_pedido = 0;
        $clothing->save();

        // Ligar categoría al producto
        $pivotCategory = new PivotClothingCategory();
        $pivotCategory->category_id = $categoryId;
        $pivotCategory->clothing_id = $clothing->id;
        $pivotCategory->save();

        // Guardar hasta 4 imágenes
        $imageCount = 0;
        foreach ($items as $item) {
            if ($imageCount >= 4) break;

            $productImage = new ProductImage();
            $productImage->clothing_id = $clothing->id;
            $productImage->image = $item->image_path;
            $productImage->save();

            $imageCount++;
        }

        Log::info("Producto E-commerce creado desde Instagram: {$clothing->id} - {$productName}");
    }

    /**
     * Obtiene o crea la categoría "Otros" y departamento si es necesario
     */
    protected function getOrCreateOtrosCategory(?TenantInfo $tenantinfo): int
    {
        // Si el tenant maneja departamentos
        if ($tenantinfo && isset($tenantinfo->manage_department) && $tenantinfo->manage_department == 1) {
            // Buscar o crear departamento "Otros"
            $departmentExist = Department::where('department', 'Otros')
                ->first();
            if (!$departmentExist) {
                $order = Department::orderBy('order', 'desc')->first();
                $department = new Department();
                $department->department = 'Otros';
                $department->order = $order->order + 1;
                $department->save();
            }
            // Buscar o crear categoría "Otros" en ese departamento
            $category = Categories::where('name', 'Otros')
                ->where('department_id', $department->id)
                ->first();

            if (!$category) {
                $category = new Categories();
                $category->name = 'Otros';
                $category->description = 'Otros';
                $category->slug = 'otros';
                $category->department_id = $department->id;
                $category->status = 1;
                $category->save();
            }

            return $category->id;
        }

        // Sin departamentos, buscar o crear categoría "Otros"
        $category = Categories::where('name', 'Otros')->first();

        if (!$category) {
            // Obtener cualquier departamento existente o crear uno default
            $department = Department::first();

            if (!$department) {
                $department = Department::create(['department' => 'Default']);
            }

            $category = new Categories();
            $category->name = 'Otros';
            $category->slug = 'otros';
            $category->department_id = $department->id;
            $category->status = 1;
            $category->save();
        }

        return $category->id;
    }

    /**
     * Genera un código de producto único
     */
    protected function generateProductCode(): string
    {
        $prefix = 'P';
        $randomNumbers = str_pad(mt_rand(1, 9999999999999), 13, '0', STR_PAD_LEFT);
        $code = $prefix . $randomNumbers;

        // Verificar unicidad
        while (ClothingCategory::where('code', $code)->exists()) {
            $randomNumbers = str_pad(mt_rand(1, 9999999999999), 13, '0', STR_PAD_LEFT);
            $code = $prefix . $randomNumbers;
        }

        return $code;
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

    /**
     * Programa múltiples carruseles en lote (AJAX)
     */
    public function massSchedule(Request $request, InstagramCollection $collection)
    {
        $request->validate([
            'start_time' => 'required|string',
            'interval_hours' => 'required|integer|min:1|max:48',
            'group_ids' => 'required|array|min:1',
            'group_ids.*' => 'integer',
        ]);

        $account = InstagramAccount::where('is_active', true)->latest()->first();
        if (!$account) {
            return response()->json(['ok' => false, 'message' => 'No hay cuenta Instagram conectada.'], 422);
        }

        $startTime = Carbon::createFromFormat('Y-m-d\TH:i', $request->start_time, config('app.timezone'));
        $intervalHours = $request->interval_hours;
        $groupIds = $request->group_ids;

        if ($startTime->isPast()) {
            return response()->json(['ok' => false, 'message' => 'La fecha de inicio debe ser en el futuro.'], 422);
        }

        $scheduledCount = 0;
        $currentTime = $startTime->copy();

        foreach ($groupIds as $groupId) {
            $group = InstagramCollectionGroup::where('id', $groupId)
                ->where('instagram_collection_id', $collection->id)
                ->whereNull('instagram_post_id')
                ->first();

            if (!$group) {
                continue; // Skip if already published or doesn't belong to collection
            }

            $group->load('items');

            if ($group->items->count() < 1 || $group->items->count() > 10) {
                continue; // Skip invalid groups
            }

            // Determinar el caption a usar
            $caption = '';

            // PRIORIDAD 1: Usar el caption pre-generado si existe
            if (!empty($group->generated_caption)) {
                $caption = $group->generated_caption;
            }
            // PRIORIDAD 2: Generar con plantilla si está marcado
            elseif ($group->use_template && $collection->caption_template_id) {
                $captionGenerator = app(CaptionGeneratorService::class);
                $imagePaths = $group->items->pluck('image_path')->toArray();

                if ($group->analyze_images) {
                    $imageData = $captionGenerator->analyzeImages($imagePaths);
                    $collection->load('captionTemplate');
                    if ($collection->captionTemplate) {
                        $templateCaption = $captionGenerator->generateTemplateText(
                            $collection->caption_template_id,
                            $imageData['variables']
                        );
                        $caption = $captionGenerator->appendHashtagsAndCta($templateCaption ?? '');
                    }
                } else {
                    $spintaxService = app(SpintaxService::class);
                    $collection->load('captionTemplate');
                    if ($collection->captionTemplate) {
                        $templateCaption = $spintaxService->process($collection->captionTemplate->template_text);
                        $caption = $captionGenerator->appendHashtagsAndCta($templateCaption ?? '');
                    }
                }
            }
            // PRIORIDAD 3: Usar caption default
            else {
                $caption = $collection->default_caption ?? '';
            }

            // Crear el post programado
            $post = InstagramPost::create([
                'instagram_account_id' => $account->id,
                'clothing_id' => null,
                'tenant_domain' => $collection->tenant_domain,
                'type' => 'feed',
                'caption' => $caption,
                'status' => 'scheduled',
                'scheduled_at' => $currentTime->copy(),
            ]);

            // Bloquear el grupo
            $group->update(['instagram_post_id' => $post->id]);

            // Agregar medios al post
            foreach ($group->items()->orderBy('sort_order')->orderBy('id')->get() as $item) {
                InstagramPostMedia::create([
                    'instagram_post_id' => $post->id,
                    'media_path' => $item->image_path,
                    'sort_order' => $item->sort_order,
                ]);
            }

            $scheduledCount++;
            $currentTime->addHours($intervalHours);
        }

        if ($scheduledCount === 0) {
            return response()->json([
                'ok' => false,
                'message' => 'No se pudo programar ningún carrusel. Verifica que tengan imágenes y no estén publicados.',
            ], 422);
        }

        return response()->json([
            'ok' => true,
            'message' => "Se programaron {$scheduledCount} carrusel(es) exitosamente.",
            'scheduled_count' => $scheduledCount,
        ]);
    }

    /**
     * Agrega un carrusel a la cola de publicación automática (AJAX)
     */
    public function addToQueue(Request $request, InstagramCollection $collection, InstagramCollectionGroup $group)
    {
        if ($group->instagram_collection_id !== $collection->id) {
            return response()->json(['ok' => false, 'message' => 'Grupo no válido'], 404);
        }

        if (!empty($group->instagram_post_id)) {
            return response()->json(['ok' => false, 'message' => 'Este carrusel ya fue publicado/programado.'], 422);
        }

        $account = InstagramAccount::where('is_active', true)->latest()->first();
        if (!$account) {
            return response()->json(['ok' => false, 'message' => 'No hay cuenta Instagram conectada.'], 422);
        }

        $group->load('items');

        if ($group->items->count() < 1) {
            return response()->json(['ok' => false, 'message' => 'Este carrusel no tiene imágenes.'], 422);
        }

        if ($group->items->count() > 10) {
            return response()->json(['ok' => false, 'message' => 'Un carrusel no puede tener más de 10 imágenes.'], 422);
        }

        // Obtener configuración de cola
        $settings = InstagramCaptionSettings::getOrCreate();
        $intervalHours = $settings->queue_interval_hours ?? 4;
        $startHour = $settings->queue_start_hour ?? '09:00';
        $endHour = $settings->queue_end_hour ?? '21:00';

        // Calcular próximo slot disponible
        $nextSlot = $this->calculateNextQueueSlot($intervalHours, $startHour, $endHour);

        // Determinar el caption a usar
        $caption = '';
        if (!empty($group->generated_caption)) {
            $caption = $group->generated_caption;
        } elseif ($group->use_template && $collection->caption_template_id) {
            $captionGenerator = app(CaptionGeneratorService::class);
            $imagePaths = $group->items->pluck('image_path')->toArray();

            if ($group->analyze_images) {
                $imageData = $captionGenerator->analyzeImages($imagePaths);
                $collection->load('captionTemplate');
                if ($collection->captionTemplate) {
                    $templateCaption = $captionGenerator->generateTemplateText(
                        $collection->caption_template_id,
                        $imageData['variables']
                    );
                    $caption = $captionGenerator->appendHashtagsAndCta($templateCaption ?? '');
                }
            } else {
                $spintaxService = app(SpintaxService::class);
                $collection->load('captionTemplate');
                if ($collection->captionTemplate) {
                    $templateCaption = $spintaxService->process($collection->captionTemplate->template_text);
                    $caption = $captionGenerator->appendHashtagsAndCta($templateCaption ?? '');
                }
            }
        } else {
            $caption = $collection->default_caption ?? '';
        }

        // Crear el post programado
        $post = InstagramPost::create([
            'instagram_account_id' => $account->id,
            'clothing_id' => null,
            'tenant_domain' => $collection->tenant_domain,
            'type' => 'feed',
            'caption' => $caption,
            'status' => 'scheduled',
            'scheduled_at' => $nextSlot,
        ]);

        // Bloquear el grupo
        $group->update(['instagram_post_id' => $post->id]);

        // Agregar medios al post
        foreach ($group->items()->orderBy('sort_order')->orderBy('id')->get() as $item) {
            InstagramPostMedia::create([
                'instagram_post_id' => $post->id,
                'media_path' => $item->image_path,
                'sort_order' => $item->sort_order,
            ]);
        }

        return response()->json([
            'ok' => true,
            'message' => "Carrusel agregado a la cola para {$nextSlot->format('d/m/Y H:i')}",
            'post' => [
                'id' => $post->id,
                'status' => 'scheduled',
                'status_text' => 'Programado',
                'scheduled_at' => $nextSlot->timezone(config('app.timezone'))->format('Y-m-d H:i'),
            ],
            'group' => [
                'id' => $group->id,
                'name' => $group->name,
            ],
        ]);
    }

    /**
     * Calcula el próximo slot disponible en la cola
     */
    protected function calculateNextQueueSlot(int $intervalHours, string $startHour, string $endHour): Carbon
    {
        $timezone = config('app.timezone');

        // Obtener el último post programado
        $lastScheduledPost = InstagramPost::where('status', 'scheduled')
            ->whereNotNull('scheduled_at')
            ->orderByDesc('scheduled_at')
            ->first();

        // Obtener también el último post publicado
        $lastPublishedPost = InstagramPost::where('status', 'published')
            ->whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->first();

        $now = Carbon::now($timezone);

        // Determinar el punto de partida
        $startFrom = $now->copy();

        if ($lastScheduledPost && $lastScheduledPost->scheduled_at) {
            $lastScheduledTime = Carbon::parse($lastScheduledPost->scheduled_at, $timezone);
            if ($lastScheduledTime->gt($startFrom)) {
                $startFrom = $lastScheduledTime->copy();
            }
        }

        // Agregar el intervalo
        $nextSlot = $startFrom->copy()->addHours($intervalHours);

        // Parsear horas de inicio y fin
        [$startH, $startM] = explode(':', $startHour);
        [$endH, $endM] = explode(':', $endHour);

        // Ajustar si está fuera del horario activo
        $slotHour = (int) $nextSlot->format('H');
        $slotMinute = (int) $nextSlot->format('i');
        $startHourInt = (int) $startH;
        $endHourInt = (int) $endH;

        // Si el slot es antes de la hora de inicio, mover a la hora de inicio
        if ($slotHour < $startHourInt || ($slotHour == $startHourInt && $slotMinute < (int) $startM)) {
            $nextSlot->setTime($startHourInt, (int) $startM, 0);
        }

        // Si el slot es después de la hora de fin, mover al día siguiente a la hora de inicio
        if ($slotHour > $endHourInt || ($slotHour == $endHourInt && $slotMinute > (int) $endM)) {
            $nextSlot->addDay()->setTime($startHourInt, (int) $startM, 0);
        }

        // Asegurar que no sea en el pasado
        if ($nextSlot->lte($now)) {
            $nextSlot = $now->copy()->addMinutes(5);
            // Ajustar horario nuevamente
            $slotHour = (int) $nextSlot->format('H');
            if ($slotHour < $startHourInt) {
                $nextSlot->setTime($startHourInt, (int) $startM, 0);
            } elseif ($slotHour > $endHourInt) {
                $nextSlot->addDay()->setTime($startHourInt, (int) $startM, 0);
            }
        }

        return $nextSlot;
    }

    /**
     * Guarda el caption generado para un grupo (AJAX)
     */
    public function saveGroupCaption(Request $request, InstagramCollection $collection, InstagramCollectionGroup $group)
    {
        if ($group->instagram_collection_id !== $collection->id) {
            return response()->json(['ok' => false, 'message' => 'Grupo no válido'], 404);
        }

        // Si ya generó post, no permitir cambios
        if (!empty($group->instagram_post_id)) {
            return response()->json(['ok' => false, 'message' => 'Este carrusel está bloqueado.'], 422);
        }

        $request->validate([
            'generated_caption' => 'nullable|string',
            'caption_type' => 'nullable|string|in:template,instagram,ecommerce',
            'use_template' => 'nullable|boolean',
            'analyze_images' => 'nullable|boolean',
        ]);

        $group->update([
            'generated_caption' => $request->input('generated_caption'),
            'caption_type' => $request->input('caption_type'),
            'use_template' => $request->boolean('use_template'),
            'analyze_images' => $request->boolean('analyze_images'),
        ]);

        return response()->json(['ok' => true]);
    }
}
