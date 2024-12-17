<?php

namespace App\Observers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GeneralObserver
{
    public function created($model)
    {
        DB::table('logs')->insert([
            'user_id' => Auth::id(),
            'type' => 'movement',
            'action' => 'insert',
            'entry_date' => null,
            'exit_date' => null,
            'detail' => json_encode([
                'action' => 'insert',
                'model' => get_class($model),
                'data' => $model->getAttributes() // Datos insertados
            ]),
            'created_at' => Carbon::now('America/Costa_Rica')->toDateTimeString(),
            'updated_at' => Carbon::now('America/Costa_Rica')->toDateTimeString(),
        ]);
    }

    // Registro al actualizar un modelo
    public function updated($model)
    {
        DB::table('logs')->insert([
            'user_id' => Auth::id(),
            'type' => 'movement',
            'action' => 'update',
            'entry_date' => null,
            'exit_date' => null,
            'detail' => json_encode([
                'action' => 'update',
                'model' => get_class($model),
                'original' => $model->getOriginal(), // Datos antes del cambio
                'changes' => $model->getChanges()   // Cambios realizados
            ]),
            'created_at' => Carbon::now('America/Costa_Rica')->toDateTimeString(),
            'updated_at' => Carbon::now('America/Costa_Rica')->toDateTimeString(),
        ]);
    }

    // Registro al eliminar un modelo
    public function deleted($model)
    {
        DB::table('logs')->insert([
            'user_id' => Auth::id(),
            'type' => 'movement',
            'action' => 'delete',
            'entry_date' =>null,
            'exit_date' => null,
            'detail' => json_encode([
                'action' => 'delete',
                'model' => get_class($model),
                'data' => $model->getAttributes() // Datos eliminados
            ]),
            'created_at' => Carbon::now('America/Costa_Rica')->toDateTimeString(),
            'updated_at' => Carbon::now('America/Costa_Rica')->toDateTimeString(),
        ]);
    }
}
