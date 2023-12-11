<?php

namespace App\Listeners;

use App\Events\ClearPageCache;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

class ClearPageCacheListener
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
     * @param  \App\Events\ClearPageCache  $event
     * @return void
     */
    public function handle(ClearPageCache $event)
    {
        Cache::flush(); // Очистка кэша
    }
}
