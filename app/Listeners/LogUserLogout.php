<?php

namespace App\Listeners;

use App\Models\Log;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class LogUserLogout
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $user = $event->user;

        if ($user) {
            // Actualizar el registro activo (sin exit_date)
            DB::table('logs')
                ->where('user_id', $user->id)
                ->whereNull('exit_date')
                ->update([
                    'exit_date' => Carbon::now('America/Costa_Rica')->toDateTimeString(),
                    'updated_at' => Carbon::now('America/Costa_Rica')->toDateTimeString(),
                ]);
        }
    }
}
