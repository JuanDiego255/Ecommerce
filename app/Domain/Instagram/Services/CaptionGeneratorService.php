<?php

namespace App\Domain\Instagram\Services;

use App\Models\InstagramCaptionSettings;
use App\Models\InstagramCaptionTemplate;
use App\Models\InstagramCta;
use App\Models\InstagramHashtagPool;

/**
 * Servicio principal para generar captions variados de Instagram
 *
 * Orquesta la generación combinando:
 * - Plantillas con spintax
 * - Análisis de imágenes (color, tipo de prenda, estampado)
 * - Hashtags mezclados
 * - CTAs rotativos
 */
class CaptionGeneratorService
{
    public function __construct(
        protected SpintaxService $spintaxService,
        protected ?ImageAnalyzerService $imageAnalyzer = null
    ) {
        $this->imageAnalyzer = $imageAnalyzer ?? new ImageAnalyzerService();
    }

    /**
     * Genera un caption completo con todas las variaciones
     *
     * @param array $options Opciones de generación
     * @return string Caption generado
     */
    public function generate(array $options = []): string
    {
        $settings = InstagramCaptionSettings::getOrCreate();

        $templateId = $options['template_id'] ?? null;
        $hashtagPoolId = $options['hashtag_pool_id'] ?? $settings->hashtag_pool_id;
        $maxHashtags = $options['max_hashtags'] ?? $settings->max_hashtags;
        $imagePaths = $options['image_paths'] ?? [];
        $analyzeImages = $options['analyze_images'] ?? false;

        $includeTemplate = $options['include_template'] ?? $settings->auto_select_template;
        $includeHashtags = $options['include_hashtags'] ?? $settings->auto_add_hashtags;
        $includeCta = $options['include_cta'] ?? $settings->auto_add_cta;

        // Analizar imágenes si se solicita
        $imageVariables = [];
        if ($analyzeImages && !empty($imagePaths)) {
            $imageAnalysis = $this->imageAnalyzer->analyzeMultiple($imagePaths);
            $imageVariables = $this->imageAnalyzer->generateTemplateVariables($imageAnalysis);
        }

        $parts = [];

        // 1. Generar texto principal desde plantilla
        if ($includeTemplate) {
            $templateText = $this->generateTemplateText($templateId, $imageVariables);
            if ($templateText) {
                $parts[] = $templateText;
            }
        }

        // 2. Agregar CTA
        if ($includeCta) {
            $ctaText = $this->generateCta();
            if ($ctaText) {
                $parts[] = $ctaText;
            }
        }

        // 3. Agregar hashtags al final
        if ($includeHashtags) {
            $hashtags = $this->generateHashtags($hashtagPoolId, $maxHashtags);
            if ($hashtags) {
                $parts[] = $hashtags;
            }
        }

        return implode("\n\n", array_filter($parts));
    }

    /**
     * Genera texto desde una plantilla (específica o aleatoria ponderada)
     * Soporta variables de imagen: {color}, {tipo_prenda}, {caracteristica}, etc.
     */
    public function generateTemplateText(?int $templateId = null, array $variables = []): ?string
    {
        if ($templateId) {
            $template = InstagramCaptionTemplate::find($templateId);
        } else {
            $template = InstagramCaptionTemplate::selectWeightedRandom();
        }

        if (!$template) {
            return null;
        }

        $text = $template->template_text;

        // Reemplazar variables de imagen antes de procesar spintax
        if (!empty($variables)) {
            $text = str_replace(array_keys($variables), array_values($variables), $text);
        }

        return $this->spintaxService->process($text);
    }

    /**
     * Genera un CTA aleatorio ponderado
     */
    public function generateCta(): ?string
    {
        $cta = InstagramCta::selectWeightedRandom();

        if (!$cta) {
            return null;
        }

        // Procesar spintax en el CTA también
        return $this->spintaxService->process($cta->cta_text);
    }

    /**
     * Genera hashtags desde un pool
     */
    public function generateHashtags(?int $poolId = null, ?int $maxHashtags = null): ?string
    {
        if ($poolId) {
            $pool = InstagramHashtagPool::find($poolId);
        } else {
            // Seleccionar un pool activo al azar
            $pool = InstagramHashtagPool::active()->inRandomOrder()->first();
        }

        if (!$pool) {
            return null;
        }

        return $pool->generateHashtagsString($maxHashtags);
    }

    /**
     * Genera caption para un carrusel/colección cuando el usuario no especificó uno
     *
     * @param int|null $collectionTemplateId Plantilla asignada a la colección (opcional)
     * @param array $imagePaths Rutas de las imágenes para analizar
     * @param bool $analyzeImages Si se debe analizar las imágenes
     * @return string Caption generado
     */
    public function generateForCarousel(
        ?int $collectionTemplateId = null,
        array $imagePaths = [],
        bool $analyzeImages = false
    ): string {
        $settings = InstagramCaptionSettings::getOrCreate();

        $options = [
            'include_template' => true,
            'include_hashtags' => $settings->auto_add_hashtags,
            'include_cta' => $settings->auto_add_cta,
            'max_hashtags' => $settings->max_hashtags,
            'image_paths' => $imagePaths,
            'analyze_images' => $analyzeImages,
        ];

        // Si la colección tiene plantilla asignada, usarla; si no, selección aleatoria
        if ($collectionTemplateId) {
            $options['template_id'] = $collectionTemplateId;
        }

        return $this->generate($options);
    }

    /**
     * Analiza imágenes y devuelve las variables disponibles
     */
    public function analyzeImages(array $imagePaths): array
    {
        if (empty($imagePaths)) {
            return [
                'analysis' => null,
                'variables' => [],
                'description' => '',
            ];
        }

        $analysis = $this->imageAnalyzer->analyzeMultiple($imagePaths);
        $variables = $this->imageAnalyzer->generateTemplateVariables($analysis);
        $description = $this->imageAnalyzer->generateDescription($analysis);

        return [
            'analysis' => $analysis,
            'variables' => $variables,
            'description' => $description,
        ];
    }

    /**
     * Obtiene información de configuración actual para mostrar en UI
     */
    public function getConfigurationInfo(): array
    {
        $settings = InstagramCaptionSettings::getOrCreate();

        return [
            'auto_select_template' => $settings->auto_select_template,
            'auto_add_hashtags' => $settings->auto_add_hashtags,
            'auto_add_cta' => $settings->auto_add_cta,
            'max_hashtags' => $settings->max_hashtags,
            'templates_count' => InstagramCaptionTemplate::active()->count(),
            'hashtag_pools_count' => InstagramHashtagPool::active()->count(),
            'ctas_count' => InstagramCta::active()->count(),
        ];
    }

    /**
     * Lista de variables disponibles para usar en plantillas
     */
    public static function getAvailableVariables(): array
    {
        return [
            '{color}' => 'Color principal detectado (ej: negro, rojo, azul)',
            '{COLOR}' => 'Color principal en mayúscula',
            '{tipo_prenda}' => 'Tipo de prenda detectada (ej: vestido, blusa)',
            '{TIPO_PRENDA}' => 'Tipo de prenda en mayúscula',
            '{adjetivo_color}' => 'Adjetivo + color (ej: elegante negro)',
            '{caracteristica}' => 'Característica de la tela/diseño',
            '{estilo}' => 'Estilo de la prenda (ej: casual, elegante)',
            '{ocasion}' => 'Ocasión sugerida (ej: salidas, día a día)',
        ];
    }
}
