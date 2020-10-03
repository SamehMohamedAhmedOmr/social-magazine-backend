<?php

namespace Modules\Catalogue\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;

class AddToCache implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $cache_key;
    private $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($cache_key, $data)
    {
        $this->cache_key = $cache_key;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Cache::forever($this->cache_key.'_'.$this->data->id, $this->data);

        if ($this->data->languages != null) {
            foreach ($this->data->languages as $language) {
                Cache::forever($this->cache_key.'_'.$language->slug, $this->data);
            }
        }
    }
}
