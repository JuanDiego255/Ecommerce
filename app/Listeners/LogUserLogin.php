<?php

namespace App\Listeners;

use App\Models\Log;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class LogUserLogin
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
        //
        $user = $event->user;
        $existingLog = DB::table('logs')
            ->where('user_id', $user->id)
            ->whereNull('exit_date')
            ->first();
        if (!$existingLog) {
            $log = new Log();
            $log->entry_date = Carbon::now('America/Costa_Rica')->toDateTimeString();
            $log->type = 'log';
            $log->user_id = $user->id;
            $log->save();
        }
    }
}
