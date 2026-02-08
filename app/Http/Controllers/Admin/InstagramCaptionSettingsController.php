<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstagramCaptionSettings;
use App\Models\InstagramCta;
use App\Models\InstagramHashtagPool;
use App\Domain\Instagram\Services\CaptionGeneratorService;
use Illuminate\Http\Request;

class InstagramCaptionSettingsController extends Controller
{
    // =========================================
    // CONFIGURACIÓN GENERAL
    // =========================================

    public function index()
    {
        $settingsCaption= InstagramCaptionSettings::getOrCreate();
        $hashtagPools = InstagramHashtagPool::orderBy('name')->get();
        $ctas = InstagramCta::orderByDesc('id')->get();

        $captionGenerator = app(CaptionGeneratorService::class);
        $info = $captionGenerator->getConfigurationInfo();

        return view('admin.instagram.caption-settings.index', compact(
            'settingsCaption',
            'hashtagPools',
            'ctas',
            'info'
        ));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'hashtag_pool_id' => 'nullable|integer|exists:instagram_hashtag_pools,id',
            'auto_select_template' => 'nullable|boolean',
            'auto_add_hashtags' => 'nullable|boolean',
            'auto_add_cta' => 'nullable|boolean',
            'max_hashtags' => 'required|integer|min:1|max:30',
            // Cola de publicación
            'queue_interval_hours' => 'nullable|integer|min:1|max:48',
            'queue_start_hour' => 'nullable|string|regex:/^\d{2}:\d{2}$/',
            'queue_end_hour' => 'nullable|string|regex:/^\d{2}:\d{2}$/',
        ]);

        $settings = InstagramCaptionSettings::getOrCreate();
        $settings->update([
            'hashtag_pool_id' => $request->hashtag_pool_id ?: null,
            'auto_select_template' => $request->boolean('auto_select_template'),
            'auto_add_hashtags' => $request->boolean('auto_add_hashtags'),
            'auto_add_cta' => $request->boolean('auto_add_cta'),
            'max_hashtags' => $request->max_hashtags,
            // Cola de publicación
            'queue_interval_hours' => $request->queue_interval_hours ?: 4,
            'queue_start_hour' => $request->queue_start_hour ?: '09:00',
            'queue_end_hour' => $request->queue_end_hour ?: '21:00',
        ]);

        return back()->with('ok', 'Configuración guardada.');
    }

    // =========================================
    // HASHTAG POOLS
    // =========================================

    public function createHashtagPool()
    {
        return view('admin.instagram.caption-settings.hashtag-pool-add');
    }

    public function storeHashtagPool(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'hashtags' => 'required|string',
            'max_hashtags' => 'required|integer|min:1|max:30',
            'shuffle' => 'nullable|boolean',
        ]);

        InstagramHashtagPool::create([
            'name' => $request->name,
            'hashtags' => $request->hashtags,
            'max_hashtags' => $request->max_hashtags,
            'shuffle' => $request->boolean('shuffle', true),
            'is_active' => true,
            'tenant_domain' => request()->getHost(),
        ]);

        return redirect('/instagram/caption-settings')
            ->with('ok', 'Pool de hashtags creado.');
    }

    public function editHashtagPool($id)
    {
        $pool = InstagramHashtagPool::findOrFail($id);
        return view('admin.instagram.caption-settings.hashtag-pool-edit', compact('pool'));
    }

    public function updateHashtagPool(Request $request, $id)
    {
        $pool = InstagramHashtagPool::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'hashtags' => 'required|string',
            'max_hashtags' => 'required|integer|min:1|max:30',
            'shuffle' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $pool->update([
            'name' => $request->name,
            'hashtags' => $request->hashtags,
            'max_hashtags' => $request->max_hashtags,
            'shuffle' => $request->boolean('shuffle', true),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect('/instagram/caption-settings')
            ->with('ok', 'Pool de hashtags actualizado.');
    }

    public function destroyHashtagPool($id)
    {
        $pool = InstagramHashtagPool::findOrFail($id);

        // Verificar si está siendo usado como default
        $usedBySettings = InstagramCaptionSettings::where('hashtag_pool_id', $pool->id)->count();
        if ($usedBySettings > 0) {
            return back()->with('error', 'Este pool está configurado como predeterminado. Cámbialo primero.');
        }

        $pool->delete();
        return back()->with('ok', 'Pool de hashtags eliminado.');
    }

    // =========================================
    // CTAs
    // =========================================

    public function createCta()
    {
        $types = InstagramCta::types();
        return view('admin.instagram.caption-settings.cta-add', compact('types'));
    }

    public function storeCta(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'cta_text' => 'required|string',
            'type' => 'required|in:dm,whatsapp,store,link,other',
            'weight' => 'required|integer|min:1|max:100',
        ]);

        InstagramCta::create([
            'name' => $request->name,
            'cta_text' => $request->cta_text,
            'type' => $request->type,
            'weight' => $request->weight,
            'is_active' => true,
            'tenant_domain' => request()->getHost(),
        ]);

        return redirect('/instagram/caption-settings')
            ->with('ok', 'CTA creado.');
    }

    public function editCta($id)
    {
        $cta = InstagramCta::findOrFail($id);
        $types = InstagramCta::types();
        return view('admin.instagram.caption-settings.cta-edit', compact('cta', 'types'));
    }

    public function updateCta(Request $request, $id)
    {
        $cta = InstagramCta::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'cta_text' => 'required|string',
            'type' => 'required|in:dm,whatsapp,store,link,other',
            'weight' => 'required|integer|min:1|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $cta->update([
            'name' => $request->name,
            'cta_text' => $request->cta_text,
            'type' => $request->type,
            'weight' => $request->weight,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect('/instagram/caption-settings')
            ->with('ok', 'CTA actualizado.');
    }

    public function destroyCta($id)
    {
        InstagramCta::findOrFail($id)->delete();
        return back()->with('ok', 'CTA eliminado.');
    }

    // =========================================
    // PREVIEW
    // =========================================

    public function previewCaption(Request $request)
    {
        $captionGenerator = app(CaptionGeneratorService::class);

        $options = [
            'include_template' => $request->boolean('include_template', true),
            'include_hashtags' => $request->boolean('include_hashtags', true),
            'include_cta' => $request->boolean('include_cta', true),
        ];

        if ($request->filled('template_id')) {
            $options['template_id'] = $request->template_id;
        }
        if ($request->filled('hashtag_pool_id')) {
            $options['hashtag_pool_id'] = $request->hashtag_pool_id;
        }
        if ($request->filled('max_hashtags')) {
            $options['max_hashtags'] = $request->max_hashtags;
        }

        $caption = $captionGenerator->generate($options);

        return response()->json([
            'ok' => true,
            'caption' => $caption,
        ]);
    }
}
