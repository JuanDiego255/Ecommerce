<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Barbero extends Model
{
    use HasFactory;


    protected $table = 'barberos';


    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'slot_minutes',
        'salario_base',
        'monto_por_servicio',
        'work_start',
        'work_end',
        'work_days',
        'activo',
        'user_id',
        'buffer_minutes',
        'photo_path',
        'commission_rate'
    ];


    protected $casts = [
        'activo' => 'boolean',
    ];


    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'barbero_servicio')
            ->withPivot(['price_cents', 'duration_minutes', 'activo'])
            ->withTimestamps();
    }
    public function citas()
    {
        return $this->hasMany(Cita::class, 'barbero_id');
    }
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
    public function excepciones()
    {
        return $this->hasMany(\App\Models\BarberoExcepcion::class);
    }
    public function bloques()
    {
        return $this->hasMany(\App\Models\BarberoBloque::class);
    }
    public function trabajos()
    {
        return $this->hasMany(\App\Models\BarberoPhoto::class);
    }
}
