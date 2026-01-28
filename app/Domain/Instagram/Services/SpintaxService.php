<?php

namespace App\Domain\Instagram\Services;

/**
 * Servicio para procesar texto con sintaxis Spintax
 *
 * Spintax permite crear variaciones de texto usando la sintaxis {opción1|opción2|opción3}
 * El servicio selecciona aleatoriamente una opción de cada bloque.
 *
 * Ejemplo:
 *   Input:  "{Nueva|Hermosa|Linda} {colección|pieza} ✨"
 *   Output: "Hermosa pieza ✨" (una variación aleatoria)
 */
class SpintaxService
{
    /**
     * Procesa un texto con sintaxis spintax y devuelve una variación aleatoria
     *
     * @param string $text Texto con bloques spintax {opción1|opción2|...}
     * @return string Texto con las variaciones aplicadas
     */
    public function process(string $text): string
    {
        // Patrón para encontrar bloques {opción1|opción2|...}
        // Soporta anidamiento básico y caracteres especiales (emojis, acentos, etc.)
        $pattern = '/\{([^{}]+)\}/u';

        // Seguir procesando mientras haya bloques spintax
        // (esto permite soportar anidamiento si es necesario)
        $maxIterations = 50; // Prevenir loops infinitos
        $iterations = 0;

        while (preg_match($pattern, $text) && $iterations < $maxIterations) {
            $text = preg_replace_callback($pattern, function ($matches) {
                $options = explode('|', $matches[1]);

                // Filtrar opciones vacías pero mantener espacios intencionales
                $options = array_values(array_filter($options, function ($opt) {
                    return $opt !== '';
                }));

                if (empty($options)) {
                    return '';
                }

                // Seleccionar una opción aleatoria
                return $options[array_rand($options)];
            }, $text);

            $iterations++;
        }

        return $text;
    }

    /**
     * Valida que el texto tenga una sintaxis spintax correcta
     * (paréntesis balanceados)
     *
     * @param string $text Texto a validar
     * @return bool True si la sintaxis es válida
     */
    public function validate(string $text): bool
    {
        $depth = 0;

        for ($i = 0; $i < mb_strlen($text); $i++) {
            $char = mb_substr($text, $i, 1);

            if ($char === '{') {
                $depth++;
            } elseif ($char === '}') {
                $depth--;
                if ($depth < 0) {
                    return false; // Más cierres que aperturas
                }
            }
        }

        return $depth === 0; // Debe cerrar todos los bloques
    }

    /**
     * Genera múltiples variaciones del mismo texto
     *
     * @param string $text Texto con spintax
     * @param int $count Número de variaciones a generar
     * @return array Lista de variaciones únicas
     */
    public function generateVariations(string $text, int $count = 5): array
    {
        $variations = [];
        $maxAttempts = $count * 3; // Intentar más veces para obtener variaciones únicas
        $attempts = 0;

        while (count($variations) < $count && $attempts < $maxAttempts) {
            $variation = $this->process($text);

            if (!in_array($variation, $variations, true)) {
                $variations[] = $variation;
            }

            $attempts++;
        }

        return $variations;
    }

    /**
     * Cuenta el número aproximado de variaciones posibles
     *
     * @param string $text Texto con spintax
     * @return int Número de variaciones posibles
     */
    public function countPossibleVariations(string $text): int
    {
        $count = 1;
        $pattern = '/\{([^{}]+)\}/u';

        preg_match_all($pattern, $text, $matches);

        foreach ($matches[1] as $block) {
            $options = explode('|', $block);
            $optionCount = count(array_filter($options, fn($opt) => $opt !== ''));
            $count *= max(1, $optionCount);
        }

        return $count;
    }

    /**
     * Extrae todos los bloques spintax del texto
     *
     * @param string $text Texto con spintax
     * @return array Lista de bloques encontrados con sus opciones
     */
    public function extractBlocks(string $text): array
    {
        $blocks = [];
        $pattern = '/\{([^{}]+)\}/u';

        preg_match_all($pattern, $text, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $blocks[] = [
                'full' => $match[0],
                'options' => explode('|', $match[1]),
            ];
        }

        return $blocks;
    }
}
