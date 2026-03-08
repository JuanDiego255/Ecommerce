<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingSection extends Model
{
    use HasFactory;

    protected $fillable = ['section_key', 'titulo', 'subtitulo', 'activo', 'orden'];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // Secciones disponibles con su configuración por defecto
    public static array $defaults = [
        'inicio'    => ['titulo' => 'Bienvenidos', 'subtitulo' => null, 'orden' => 1],
        'nosotros'  => ['titulo' => 'Nosotros', 'subtitulo' => 'Conoce quiénes somos', 'orden' => 2],
        'servicios' => ['titulo' => 'Nuestros Servicios', 'subtitulo' => 'Todo lo que ofrecemos', 'orden' => 3],
        'faq'       => ['titulo' => 'Preguntas Frecuentes', 'subtitulo' => 'Resolvemos tus dudas', 'orden' => 4],
        'blog'      => ['titulo' => 'Blog', 'subtitulo' => 'Nuestras últimas publicaciones', 'orden' => 5],
        'contacto'  => ['titulo' => 'Contáctanos', 'subtitulo' => 'Estamos para ayudarte', 'orden' => 6],
    ];

    public function scopeActivas($query)
    {
        return $query->where('activo', true)->orderBy('orden');
    }

    // Devuelve la sección por key o null si no existe/está inactiva
    public static function findActive(string $key): ?self
    {
        return static::where('section_key', $key)->where('activo', true)->first();
    }

    // Inicializa las secciones por defecto si no existen
    public static function initializeDefaults(): void
    {
        foreach (static::$defaults as $key => $config) {
            static::firstOrCreate(
                ['section_key' => $key],
                [
                    'titulo'   => $config['titulo'],
                    'subtitulo' => $config['subtitulo'],
                    'activo'   => true,
                    'orden'    => $config['orden'],
                ]
            );
        }
    }
}
