<?php

namespace Modules\Catalogue\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;

class RemoveFromCache implements ShouldQueue
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
        if ($this->data->languages != null) {
            foreach ($this->data->languages as $language) {
                if (Cache::has($this->cache_key . '_' . $language->slug)) {
                    Cache::forget($this->cache_key . '_' . $language->slug);
                }
                if (Cache::has($this->cache_key . "_data_$language->slug")) {
                    Cache::forget($this->cache_key . "_data_$language->slug"); // This is for the data from Transformer
                }
            }
        }
        if (Cache::has($this->cache_key . "_data_".$this->data->id)) {
            Cache::forget($this->cache_key . "_data_".$this->data->id); // This is for the data from Transformer
        }
        if (Cache::has($this->cache_key."_". $this->data->id)) {
            Cache::forget($this->cache_key ."_". $this->data->id); // This is for the data from Transformer
        }
    }
}
