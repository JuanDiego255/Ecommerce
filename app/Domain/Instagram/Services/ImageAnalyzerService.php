<?php

namespace App\Domain\Instagram\Services;

use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\Feature\Type;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Servicio para analizar imágenes y extraer características visuales
 * Usa Google Cloud Vision API para análisis avanzado con fallback a GD de PHP
 */
class ImageAnalyzerService
{
    protected ?ImageAnnotatorClient $visionClient = null;
    protected bool $visionAvailable = false;

    /**
     * Mapeo de labels de Google Vision a descripciones en español
     */
    protected array $labelTranslations = [
        // Prendas
        'dress' => 'vestido',
        'gown' => 'vestido de gala',
        'skirt' => 'falda',
        'blouse' => 'blusa',
        'shirt' => 'camisa',
        't-shirt' => 'camiseta',
        'top' => 'top',
        'pants' => 'pantalón',
        'trousers' => 'pantalón',
        'jeans' => 'jeans',
        'shorts' => 'shorts',
        'jacket' => 'chaqueta',
        'coat' => 'abrigo',
        'sweater' => 'suéter',
        'cardigan' => 'cardigan',
        'jumpsuit' => 'enterizo',
        'romper' => 'enterizo corto',
        'bodysuit' => 'body',
        'swimsuit' => 'traje de baño',
        'bikini' => 'bikini',
        'lingerie' => 'lencería',
        'underwear' => 'ropa interior',
        'pajamas' => 'pijama',
        'suit' => 'traje',
        'blazer' => 'blazer',
        'vest' => 'chaleco',
        'hoodie' => 'hoodie',
        'leggings' => 'leggins',
        'clothing' => 'prenda',
        'outerwear' => 'ropa de abrigo',
        'sportswear' => 'ropa deportiva',
        'activewear' => 'ropa deportiva',
        'formal wear' => 'ropa formal',
        'cocktail dress' => 'vestido de cóctel',
        'evening dress' => 'vestido de noche',
        'maxi dress' => 'maxi vestido',
        'mini dress' => 'mini vestido',
        'wrap dress' => 'vestido cruzado',

        // Materiales y texturas
        'silk' => 'seda',
        'cotton' => 'algodón',
        'linen' => 'lino',
        'denim' => 'mezclilla',
        'leather' => 'cuero',
        'lace' => 'encaje',
        'velvet' => 'terciopelo',
        'satin' => 'satén',
        'chiffon' => 'gasa',
        'knit' => 'tejido de punto',
        'wool' => 'lana',
        'polyester' => 'poliéster',
        'sequin' => 'lentejuelas',
        'embroidery' => 'bordado',
        'crochet' => 'crochet',
        'tulle' => 'tul',
        'mesh' => 'malla',

        // Patrones
        'pattern' => 'estampado',
        'floral' => 'floral',
        'stripes' => 'rayas',
        'striped' => 'rayado',
        'polka dot' => 'lunares',
        'plaid' => 'cuadros',
        'checkered' => 'a cuadros',
        'animal print' => 'animal print',
        'leopard print' => 'estampado de leopardo',
        'geometric' => 'geométrico',
        'abstract' => 'abstracto',
        'paisley' => 'paisley',
        'tie-dye' => 'tie-dye',
        'camouflage' => 'camuflaje',
        'tropical' => 'tropical',

        // Estilos
        'elegant' => 'elegante',
        'casual' => 'casual',
        'formal' => 'formal',
        'vintage' => 'vintage',
        'bohemian' => 'bohemio',
        'minimalist' => 'minimalista',
        'romantic' => 'romántico',
        'sporty' => 'deportivo',
        'chic' => 'chic',
        'trendy' => 'trendy',
        'classic' => 'clásico',
        'modern' => 'moderno',

        // Detalles
        'ruffle' => 'con volantes',
        'bow' => 'con lazo',
        'button' => 'con botones',
        'zipper' => 'con cierre',
        'pocket' => 'con bolsillos',
        'collar' => 'con cuello',
        'sleeve' => 'manga',
        'v-neck' => 'escote en V',
        'off-shoulder' => 'hombros descubiertos',
        'backless' => 'espalda descubierta',
        'high-waisted' => 'tiro alto',
        'low-cut' => 'escotado',
        'fitted' => 'ajustado',
        'loose' => 'holgado',
        'flowing' => 'fluido',
        'structured' => 'estructurado',
        'asymmetric' => 'asimétrico',
        'pleated' => 'plisado',
        'gathered' => 'fruncido',
        'tiered' => 'escalonado',
        'layered' => 'en capas',
        'cropped' => 'corto',
        'long' => 'largo',
        'midi' => 'midi',

        // Ocasiones
        'party' => 'fiesta',
        'wedding' => 'boda',
        'beach' => 'playa',
        'office' => 'oficina',
        'date' => 'cita',
        'vacation' => 'vacaciones',
    ];

    public function __construct()
    {
        $this->initializeVisionClient();
    }

    /**
     * Inicializa el cliente de Google Cloud Vision si las credenciales están disponibles
     */
    protected function initializeVisionClient(): void
    {
        try {
            $credentialsPath = env('GOOGLE_CLOUD_CREDENTIALS');

            if ($credentialsPath && file_exists($credentialsPath)) {
                $this->visionClient = new ImageAnnotatorClient([
                    'credentials' => $credentialsPath,
                ]);
                $this->visionAvailable = true;
            } elseif (env('GOOGLE_APPLICATION_CREDENTIALS')) {
                $this->visionClient = new ImageAnnotatorClient();
                $this->visionAvailable = true;
            }
        } catch (\Exception $e) {
            Log::warning('Google Cloud Vision no disponible: ' . $e->getMessage());
            $this->visionAvailable = false;
        }
    }

    /**
     * Analiza imagen usando Google Cloud Vision API
     */
    public function analyzeWithVision(string $imagePath): ?array
    {
        if (!$this->visionAvailable || !$this->visionClient) {
            return null;
        }

        try {
            $fullPath = Storage::disk('public')->path($imagePath);

            if (!file_exists($fullPath)) {
                return null;
            }

            $imageContent = file_get_contents($fullPath);

            $response = $this->visionClient->annotateImage($imageContent, [
                Type::LABEL_DETECTION,
                Type::IMAGE_PROPERTIES,
                Type::OBJECT_LOCALIZATION,
            ]);

            $result = [
                'labels' => [],
                'labels_es' => [],
                'colors' => [],
                'objects' => [],
                'detected_prenda' => null,
                'detected_material' => null,
                'detected_pattern' => null,
                'detected_style' => null,
                'detected_details' => [],
            ];

            // Procesar etiquetas
            $labels = $response->getLabelAnnotations();
            foreach ($labels as $label) {
                $labelText = strtolower($label->getDescription());
                $score = $label->getScore();

                if ($score >= 0.6) {
                    $result['labels'][] = ['text' => $labelText, 'score' => $score];
                    $translated = $this->translateLabel($labelText);
                    if ($translated) {
                        $result['labels_es'][] = $translated;
                        $this->categorizeLabel($labelText, $translated, $result);
                    }
                }
            }

            // Procesar colores dominantes
            $imageProps = $response->getImagePropertiesAnnotation();
            if ($imageProps) {
                $dominantColors = $imageProps->getDominantColors();
                if ($dominantColors) {
                    foreach ($dominantColors->getColors() as $colorInfo) {
                        $color = $colorInfo->getColor();
                        $result['colors'][] = [
                            'r' => (int)$color->getRed(),
                            'g' => (int)$color->getGreen(),
                            'b' => (int)$color->getBlue(),
                            'score' => $colorInfo->getScore(),
                            'pixel_fraction' => $colorInfo->getPixelFraction(),
                        ];
                    }
                }
            }

            // Procesar objetos detectados
            $objects = $response->getLocalizedObjectAnnotations();
            foreach ($objects as $object) {
                $objectName = strtolower($object->getName());
                $result['objects'][] = ['name' => $objectName, 'score' => $object->getScore()];
                $translated = $this->translateLabel($objectName);
                if ($translated && !in_array($translated, $result['labels_es'])) {
                    $result['labels_es'][] = $translated;
                    $this->categorizeLabel($objectName, $translated, $result);
                }
            }

            return $result;

        } catch (\Exception $e) {
            Log::error('Error en análisis de Vision API: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Traduce un label de inglés a español
     */
    protected function translateLabel(string $label): ?string
    {
        $label = strtolower(trim($label));

        if (isset($this->labelTranslations[$label])) {
            return $this->labelTranslations[$label];
        }

        foreach ($this->labelTranslations as $en => $es) {
            if (str_contains($label, $en) || str_contains($en, $label)) {
                return $es;
            }
        }

        return null;
    }

    /**
     * Categoriza un label detectado en prenda, material, patrón, estilo o detalle
     */
    protected function categorizeLabel(string $labelEn, string $labelEs, array &$result): void
    {
        $prendas = ['vestido', 'falda', 'blusa', 'camisa', 'camiseta', 'top', 'pantalón', 'jeans',
            'shorts', 'chaqueta', 'abrigo', 'suéter', 'cardigan', 'enterizo', 'body', 'bikini',
            'traje de baño', 'blazer', 'hoodie', 'leggins', 'prenda', 'maxi vestido', 'mini vestido'];

        $materiales = ['seda', 'algodón', 'lino', 'mezclilla', 'cuero', 'encaje', 'terciopelo',
            'satén', 'gasa', 'tejido de punto', 'lana', 'lentejuelas', 'bordado', 'crochet', 'tul', 'malla'];

        $patrones = ['estampado', 'floral', 'rayas', 'rayado', 'lunares', 'cuadros', 'animal print',
            'geométrico', 'abstracto', 'tropical', 'tie-dye'];

        $estilos = ['elegante', 'casual', 'formal', 'vintage', 'bohemio', 'minimalista',
            'romántico', 'deportivo', 'chic', 'trendy', 'clásico', 'moderno'];

        $detalles = ['con volantes', 'con lazo', 'con botones', 'con cierre', 'con bolsillos',
            'con cuello', 'escote en V', 'hombros descubiertos', 'espalda descubierta',
            'ajustado', 'holgado', 'fluido', 'plisado', 'asimétrico'];

        if (in_array($labelEs, $prendas) && !$result['detected_prenda']) {
            $result['detected_prenda'] = $labelEs;
        } elseif (in_array($labelEs, $materiales) && !$result['detected_material']) {
            $result['detected_material'] = $labelEs;
        } elseif (in_array($labelEs, $patrones) && !$result['detected_pattern']) {
            $result['detected_pattern'] = $labelEs;
        } elseif (in_array($labelEs, $estilos) && !$result['detected_style']) {
            $result['detected_style'] = $labelEs;
        } elseif (in_array($labelEs, $detalles)) {
            if (!in_array($labelEs, $result['detected_details'])) {
                $result['detected_details'][] = $labelEs;
            }
        }
    }

    /**
     * Mapeo de colores RGB a nombres en español
     */
    protected array $colorNames = [
        'negro' => [[0, 0, 0], [40, 40, 40]],
        'blanco' => [[240, 240, 240], [255, 255, 255]],
        'gris' => [[100, 100, 100], [180, 180, 180]],
        'rojo' => [[150, 0, 0], [255, 80, 80]],
        'rosa' => [[200, 100, 150], [255, 180, 210]],
        'fucsia' => [[200, 0, 150], [255, 100, 200]],
        'naranja' => [[200, 80, 0], [255, 150, 50]],
        'amarillo' => [[200, 180, 0], [255, 255, 100]],
        'verde' => [[0, 100, 0], [100, 200, 100]],
        'verde menta' => [[100, 200, 150], [180, 255, 200]],
        'azul' => [[0, 0, 150], [100, 100, 255]],
        'azul cielo' => [[100, 150, 200], [180, 210, 255]],
        'azul marino' => [[0, 0, 80], [50, 50, 130]],
        'morado' => [[80, 0, 120], [150, 50, 200]],
        'lila' => [[150, 100, 200], [210, 180, 255]],
        'café' => [[80, 40, 20], [150, 100, 60]],
        'beige' => [[180, 160, 130], [240, 220, 190]],
        'crema' => [[230, 220, 200], [255, 250, 240]],
        'coral' => [[200, 100, 80], [255, 150, 120]],
        'turquesa' => [[0, 150, 150], [100, 220, 220]],
        'dorado' => [[180, 150, 50], [220, 190, 100]],
        'plateado' => [[160, 160, 170], [210, 210, 220]],
        'vino' => [[80, 0, 30], [130, 30, 60]],
        'terracota' => [[180, 80, 50], [220, 120, 80]],
    ];

    /**
     * Palabras clave para detectar tipo de prenda desde nombre de archivo
     */
    protected array $prendaKeywords = [
        'vestido' => ['vestido', 'dress', 'maxi', 'mini'],
        'blusa' => ['blusa', 'blouse', 'top', 'camisa', 'shirt'],
        'pantalón' => ['pantalon', 'pants', 'jean', 'jeans', 'jogger'],
        'falda' => ['falda', 'skirt', 'minifalda'],
        'short' => ['short', 'shorts', 'bermuda'],
        'conjunto' => ['conjunto', 'set', 'coord', 'outfit'],
        'enterizo' => ['enterizo', 'jumpsuit', 'romper', 'overall'],
        'chaqueta' => ['chaqueta', 'jacket', 'blazer', 'cardigan', 'abrigo'],
        'suéter' => ['sueter', 'sweater', 'hoodie', 'buzo'],
        'crop top' => ['crop', 'croptop'],
        'body' => ['body', 'bodysuit'],
        'bikini' => ['bikini', 'swimsuit', 'traje de baño'],
        'leggins' => ['leggins', 'leggings', 'licra'],
        'maxi dress' => ['maxidress', 'maxi dress', 'vestido largo'],
    ];

    /**
     * Analiza una imagen y extrae sus características
     * Intenta usar Google Cloud Vision primero, luego fallback a GD
     */
    public function analyze(string $imagePath): array
    {
        // Intentar con Google Cloud Vision primero
        $visionResult = $this->analyzeWithVision($imagePath);

        if ($visionResult) {
            // Obtener color principal del análisis de Vision
            $mainColor = null;
            if (!empty($visionResult['colors'])) {
                $firstColor = $visionResult['colors'][0];
                $mainColor = $this->getColorName([$firstColor['r'], $firstColor['g'], $firstColor['b']]);
            }

            return [
                'color_principal' => $mainColor,
                'color_secundario' => null,
                'colores_hex' => array_map(function($c) {
                    return sprintf('#%02x%02x%02x', $c['r'], $c['g'], $c['b']);
                }, array_slice($visionResult['colors'], 0, 5)),
                'es_claro' => false,
                'es_oscuro' => false,
                'tiene_estampado' => !empty($visionResult['detected_pattern']),
                'tipo_prenda' => $visionResult['detected_prenda'],
                'caracteristica_tela' => $visionResult['detected_material'],
                'vision_data' => $visionResult,
            ];
        }

        // Fallback a análisis con GD
        return $this->analyzeWithGD($imagePath);
    }

    /**
     * Analiza imagen usando PHP GD (fallback)
     */
    protected function analyzeWithGD(string $imagePath): array
    {
        $fullPath = Storage::disk('public')->path($imagePath);

        if (!file_exists($fullPath)) {
            return $this->getDefaultAnalysis();
        }

        $imageInfo = @getimagesize($fullPath);
        if (!$imageInfo) {
            return $this->getDefaultAnalysis();
        }

        $image = $this->loadImage($fullPath, $imageInfo['mime']);
        if (!$image) {
            return $this->getDefaultAnalysis();
        }

        try {
            $colors = $this->extractDominantColors($image, 5);
            $mainColor = $this->getColorName($colors[0] ?? [128, 128, 128]);
            $secondaryColor = isset($colors[1]) ? $this->getColorName($colors[1]) : null;

            $brightness = $this->calculateBrightness($colors[0] ?? [128, 128, 128]);
            $isPattern = $this->detectPattern($image);

            $fileName = basename($imagePath);
            $prendaType = $this->detectPrendaFromFilename($fileName);

            imagedestroy($image);

            return [
                'color_principal' => $mainColor,
                'color_secundario' => $secondaryColor,
                'colores_hex' => array_map(fn($c) => sprintf('#%02x%02x%02x', $c[0], $c[1], $c[2]), $colors),
                'es_claro' => $brightness > 0.5,
                'es_oscuro' => $brightness < 0.4,
                'tiene_estampado' => $isPattern,
                'tipo_prenda' => $prendaType,
                'caracteristica_tela' => $this->getTipoTela($isPattern, $brightness),
                'vision_data' => null,
            ];
        } catch (\Exception $e) {
            if (isset($image) && $image) {
                imagedestroy($image);
            }
            return $this->getDefaultAnalysis();
        }
    }

    /**
     * Analiza múltiples imágenes y combina los resultados
     */
    public function analyzeMultiple(array $imagePaths): array
    {
        $allColors = [];
        $allPrendas = [];
        $allMateriales = [];
        $allPatrones = [];
        $allEstilos = [];
        $allDetalles = [];
        $allLabels = [];
        $patterns = [];
        $analyses = [];
        $hasVisionData = false;

        foreach ($imagePaths as $path) {
            $analysis = $this->analyze($path);
            $analyses[] = $analysis;

            if ($analysis['color_principal']) {
                $allColors[] = $analysis['color_principal'];
            }
            if ($analysis['tipo_prenda']) {
                $allPrendas[] = $analysis['tipo_prenda'];
            }
            if (!empty($analysis['caracteristica_tela'])) {
                $allMateriales[] = $analysis['caracteristica_tela'];
            }
            $patterns[] = $analysis['tiene_estampado'];

            // Combinar datos de Vision API si está disponible
            if (!empty($analysis['vision_data'])) {
                $hasVisionData = true;
                $vd = $analysis['vision_data'];

                if ($vd['detected_material']) {
                    $allMateriales[] = $vd['detected_material'];
                }
                if ($vd['detected_pattern']) {
                    $allPatrones[] = $vd['detected_pattern'];
                }
                if ($vd['detected_style']) {
                    $allEstilos[] = $vd['detected_style'];
                }
                foreach ($vd['detected_details'] ?? [] as $detail) {
                    $allDetalles[] = $detail;
                }
                foreach ($vd['labels_es'] ?? [] as $label) {
                    $allLabels[] = $label;
                }
            }
        }

        // Color más frecuente
        $colorCounts = array_count_values($allColors);
        arsort($colorCounts);
        $mainColor = array_key_first($colorCounts) ?? 'variado';

        // Colores únicos para descripción
        $uniqueColors = array_unique($allColors);
        $colorDescription = count($uniqueColors) > 2
            ? 'tonos variados'
            : implode(' y ', array_slice($uniqueColors, 0, 2));

        // Tipo de prenda más frecuente
        $prendaCounts = array_count_values(array_filter($allPrendas));
        arsort($prendaCounts);
        $mainPrenda = array_key_first($prendaCounts);

        // Material más frecuente
        $materialCounts = array_count_values(array_filter($allMateriales));
        arsort($materialCounts);
        $mainMaterial = array_key_first($materialCounts);

        // Patrón más frecuente
        $patternCounts = array_count_values(array_filter($allPatrones));
        arsort($patternCounts);
        $mainPattern = array_key_first($patternCounts);

        // Estilo más frecuente
        $estiloCounts = array_count_values(array_filter($allEstilos));
        arsort($estiloCounts);
        $mainEstilo = array_key_first($estiloCounts);

        // Mayoría tiene estampado?
        $patternCount = count(array_filter($patterns));
        $hasPattern = $patternCount > count($patterns) / 2 || !empty($mainPattern);

        // Detalles únicos detectados
        $uniqueDetalles = array_unique($allDetalles);

        // Labels únicos detectados (para características adicionales)
        $uniqueLabels = array_unique($allLabels);

        return [
            'color_principal' => $mainColor,
            'colores_descripcion' => $colorDescription,
            'colores_unicos' => $uniqueColors,
            'tipo_prenda' => $mainPrenda,
            'material' => $mainMaterial,
            'patron' => $mainPattern,
            'estilo' => $mainEstilo,
            'detalles' => $uniqueDetalles,
            'tiene_estampado' => $hasPattern,
            'total_imagenes' => count($imagePaths),
            'analisis_individual' => $analyses,
            'labels_detectados' => $uniqueLabels,
            'tiene_vision_data' => $hasVisionData,
        ];
    }

    /**
     * Genera texto descriptivo basado en el análisis
     */
    public function generateDescription(array $analysis): string
    {
        $parts = [];

        // Color
        if (!empty($analysis['color_principal']) && $analysis['color_principal'] !== 'variado') {
            $colorAdj = $this->getColorAdjective($analysis['color_principal']);
            $parts[] = $colorAdj;
        } elseif (!empty($analysis['colores_descripcion'])) {
            $parts[] = "en {$analysis['colores_descripcion']}";
        }

        // Tipo de prenda
        if (!empty($analysis['tipo_prenda'])) {
            $parts[] = $analysis['tipo_prenda'];
        }

        // Estampado
        if ($analysis['tiene_estampado'] ?? false) {
            $parts[] = $this->getRandomElement(['estampado', 'con diseño', 'con patrón']);
        }

        return implode(' ', $parts);
    }

    /**
     * Genera variables para usar en plantillas spintax
     */
    public function generateTemplateVariables(array $analysis): array
    {
        $color = $analysis['color_principal'] ?? 'elegante';
        $prenda = $analysis['tipo_prenda'] ?? 'prenda';
        $hasPattern = $analysis['tiene_estampado'] ?? false;
        $material = $analysis['material'] ?? null;
        $patron = $analysis['patron'] ?? null;
        $estilo = $analysis['estilo'] ?? null;
        $detalles = $analysis['detalles'] ?? [];

        // Generar la sección de detalles dinámicamente
        $detallesSection = $this->generateDetallesSection($analysis);

        return [
            '{color}' => $color,
            '{COLOR}' => ucfirst($color),
            '{tipo_prenda}' => $prenda,
            '{TIPO_PRENDA}' => ucfirst($prenda),
            '{adjetivo_color}' => $this->getColorAdjective($color),
            '{caracteristica}' => $this->getCaracteristica($material, $hasPattern, $patron),
            '{estilo}' => $estilo ?: $this->getEstilo($prenda, $hasPattern),
            '{ocasion}' => $this->getOcasion($prenda),
            '{material}' => $material ?: $this->getRandomElement(['tela suave', 'material cómodo', 'acabado premium']),
            '{patron}' => $patron ?: ($hasPattern ? 'estampado' : 'liso'),
            '{detalles_section}' => $detallesSection,
        ];
    }

    /**
     * Genera la sección de "Detalles:" con bullet points basados en el análisis
     */
    protected function generateDetallesSection(array $analysis): string
    {
        $bullets = [];
        $color = $analysis['color_principal'] ?? null;
        $prenda = $analysis['tipo_prenda'] ?? null;
        $material = $analysis['material'] ?? null;
        $patron = $analysis['patron'] ?? null;
        $estilo = $analysis['estilo'] ?? null;
        $detalles = $analysis['detalles'] ?? [];
        $hasPattern = $analysis['tiene_estampado'] ?? false;
        $labels = $analysis['labels_detectados'] ?? [];

        // Bullet 1: Material o característica de tela
        if ($material) {
            $bullets[] = "• Material: {$material}";
        } elseif ($hasPattern && $patron) {
            $bullets[] = "• Diseño: {$patron}";
        } else {
            $bullets[] = "• " . $this->getRandomElement(['Tela suave', 'Material cómodo', 'Acabado premium']);
        }

        // Bullet 2: Color y estilo
        if ($color && $color !== 'variado' && $color !== 'neutro') {
            $bullets[] = "• Color {$color}" . ($estilo ? ", estilo {$estilo}" : '');
        } elseif ($estilo) {
            $bullets[] = "• Estilo {$estilo}";
        } else {
            $bullets[] = "• " . $this->getRandomElement([
                'Ideal para cualquier ocasión',
                'Versátil y cómodo',
                'Diseño moderno',
            ]);
        }

        // Bullet 3: Detalles adicionales detectados o ocasión
        if (!empty($detalles)) {
            $detailList = array_slice($detalles, 0, 2);
            $bullets[] = "• " . ucfirst(implode(', ', $detailList));
        } elseif ($prenda) {
            $bullets[] = "• Perfecto para " . $this->getOcasion($prenda);
        } else {
            $bullets[] = "• " . $this->getRandomElement([
                'Perfecto para salidas',
                'Ideal para el día a día',
                'Para ocasiones especiales',
            ]);
        }

        return implode("\n", $bullets);
    }

    /**
     * Obtiene una característica basada en material y patrón
     */
    protected function getCaracteristica(?string $material, bool $hasPattern, ?string $patron): string
    {
        if ($material) {
            return $material;
        }

        if ($hasPattern) {
            if ($patron) {
                return "diseño {$patron}";
            }
            return $this->getRandomElement(['estampado único', 'diseño exclusivo', 'patrón moderno']);
        }

        return $this->getRandomElement(['tela suave', 'acabado premium', 'material de calidad']);
    }

    // =========================================
    // Métodos privados de análisis de imagen
    // =========================================

    protected function loadImage(string $path, string $mime)
    {
        return match ($mime) {
            'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($path),
            'image/png' => @imagecreatefrompng($path),
            'image/gif' => @imagecreatefromgif($path),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : null,
            default => null,
        };
    }

    protected function extractDominantColors($image, int $count = 5): array
    {
        $width = imagesx($image);
        $height = imagesy($image);

        // Muestrear píxeles (no todos para eficiencia)
        $sampleSize = min(100, $width, $height);
        $stepX = max(1, (int)($width / $sampleSize));
        $stepY = max(1, (int)($height / $sampleSize));

        $colors = [];

        for ($x = 0; $x < $width; $x += $stepX) {
            for ($y = 0; $y < $height; $y += $stepY) {
                $rgb = imagecolorat($image, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;

                // Agrupar colores similares (reducir a 32 niveles)
                $r = (int)($r / 8) * 8;
                $g = (int)($g / 8) * 8;
                $b = (int)($b / 8) * 8;

                $key = "{$r},{$g},{$b}";
                $colors[$key] = ($colors[$key] ?? 0) + 1;
            }
        }

        // Ordenar por frecuencia
        arsort($colors);

        // Tomar los más frecuentes
        $dominant = [];
        foreach (array_slice(array_keys($colors), 0, $count) as $key) {
            $parts = explode(',', $key);
            $dominant[] = [(int)$parts[0], (int)$parts[1], (int)$parts[2]];
        }

        return $dominant;
    }

    protected function getColorName(array $rgb): string
    {
        $r = $rgb[0];
        $g = $rgb[1];
        $b = $rgb[2];

        $bestMatch = 'neutro';
        $bestDistance = PHP_INT_MAX;

        foreach ($this->colorNames as $name => [$min, $max]) {
            // Verificar si el color está en el rango
            if ($r >= $min[0] && $r <= $max[0] &&
                $g >= $min[1] && $g <= $max[1] &&
                $b >= $min[2] && $b <= $max[2]) {
                return $name;
            }

            // Calcular distancia al centro del rango
            $centerR = ($min[0] + $max[0]) / 2;
            $centerG = ($min[1] + $max[1]) / 2;
            $centerB = ($min[2] + $max[2]) / 2;

            $distance = sqrt(
                pow($r - $centerR, 2) +
                pow($g - $centerG, 2) +
                pow($b - $centerB, 2)
            );

            if ($distance < $bestDistance) {
                $bestDistance = $distance;
                $bestMatch = $name;
            }
        }

        return $bestMatch;
    }

    protected function calculateBrightness(array $rgb): float
    {
        // Fórmula de luminosidad percibida
        return (0.299 * $rgb[0] + 0.587 * $rgb[1] + 0.114 * $rgb[2]) / 255;
    }

    protected function detectPattern($image): bool
    {
        $width = imagesx($image);
        $height = imagesy($image);

        // Muestrear regiones y comparar variación de color
        $regions = [];
        $regionSize = min(50, $width / 4, $height / 4);

        for ($rx = 0; $rx < 4; $rx++) {
            for ($ry = 0; $ry < 4; $ry++) {
                $startX = (int)($rx * $width / 4);
                $startY = (int)($ry * $height / 4);

                $colors = [];
                for ($x = $startX; $x < $startX + $regionSize && $x < $width; $x += 5) {
                    for ($y = $startY; $y < $startY + $regionSize && $y < $height; $y += 5) {
                        $rgb = imagecolorat($image, $x, $y);
                        $colors[] = $rgb;
                    }
                }

                if (!empty($colors)) {
                    $regions[] = $colors;
                }
            }
        }

        // Comparar variación entre regiones
        $variations = 0;
        foreach ($regions as $region) {
            $unique = count(array_unique($region));
            $total = count($region);
            if ($total > 0 && $unique / $total > 0.3) {
                $variations++;
            }
        }

        // Si más de la mitad de las regiones tienen alta variación, probablemente es estampado
        return $variations > count($regions) / 2;
    }

    protected function detectPrendaFromFilename(string $filename): ?string
    {
        $filename = strtolower($filename);
        $filename = str_replace(['_', '-', '.'], ' ', $filename);

        foreach ($this->prendaKeywords as $prenda => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($filename, $keyword)) {
                    return $prenda;
                }
            }
        }

        return null;
    }

    protected function getTipoTela(bool $isPattern, float $brightness): string
    {
        if ($isPattern) {
            return $this->getRandomElement([
                'estampado elegante',
                'diseño único',
                'patrón moderno',
                'print exclusivo',
            ]);
        }

        if ($brightness > 0.7) {
            return $this->getRandomElement([
                'tela fresca',
                'material ligero',
                'acabado suave',
            ]);
        }

        return $this->getRandomElement([
            'tela de calidad',
            'material premium',
            'acabado elegante',
        ]);
    }

    protected function getColorAdjective(string $color): string
    {
        $adjectives = [
            'negro' => ['elegante negro', 'clásico negro', 'sofisticado negro'],
            'blanco' => ['fresco blanco', 'puro blanco', 'impecable blanco'],
            'rojo' => ['vibrante rojo', 'intenso rojo', 'apasionado rojo'],
            'rosa' => ['delicado rosa', 'romántico rosa', 'dulce rosa'],
            'azul' => ['sereno azul', 'elegante azul', 'profundo azul'],
            'verde' => ['fresco verde', 'natural verde', 'vibrante verde'],
            'amarillo' => ['luminoso amarillo', 'alegre amarillo', 'radiante amarillo'],
            'morado' => ['místico morado', 'elegante morado', 'sofisticado morado'],
            'beige' => ['neutro beige', 'versátil beige', 'elegante beige'],
            'café' => ['cálido café', 'terroso café', 'natural café'],
        ];

        return $this->getRandomElement($adjectives[$color] ?? ["hermoso {$color}", "elegante {$color}"]);
    }

    protected function getEstilo(string $prenda, bool $hasPattern): string
    {
        $estilos = [
            'vestido' => ['femenino', 'elegante', 'versátil', 'romántico'],
            'blusa' => ['casual', 'chic', 'moderno', 'fresco'],
            'pantalón' => ['cómodo', 'moderno', 'versátil', 'casual'],
            'conjunto' => ['coordinado', 'trendy', 'completo', 'combinado'],
            'falda' => ['femenino', 'coqueto', 'fresco', 'elegante'],
        ];

        $base = $estilos[$prenda] ?? ['moderno', 'elegante', 'versátil'];

        if ($hasPattern) {
            $base = array_merge($base, ['llamativo', 'único', 'statement']);
        }

        return $this->getRandomElement($base);
    }

    protected function getOcasion(string $prenda): string
    {
        $ocasiones = [
            'vestido' => ['salidas', 'eventos', 'citas', 'ocasiones especiales'],
            'blusa' => ['el día a día', 'la oficina', 'salidas casuales', 'cualquier ocasión'],
            'pantalón' => ['el día a día', 'looks casuales', 'la oficina', 'cualquier ocasión'],
            'conjunto' => ['salidas', 'eventos', 'el fin de semana', 'ocasiones especiales'],
            'enterizo' => ['eventos', 'salidas nocturnas', 'ocasiones especiales', 'fiestas'],
        ];

        return $this->getRandomElement($ocasiones[$prenda] ?? ['cualquier ocasión', 'el día a día', 'salidas']);
    }

    protected function getRandomElement(array $array): string
    {
        return $array[array_rand($array)];
    }

    protected function getDefaultAnalysis(): array
    {
        return [
            'color_principal' => null,
            'color_secundario' => null,
            'colores_hex' => [],
            'es_claro' => false,
            'es_oscuro' => false,
            'tiene_estampado' => false,
            'tipo_prenda' => null,
            'caracteristica_tela' => 'material de calidad',
        ];
    }
}
