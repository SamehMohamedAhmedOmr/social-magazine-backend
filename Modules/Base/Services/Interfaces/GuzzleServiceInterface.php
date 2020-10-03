<?php

namespace Modules\Base\Services\Interfaces;

interface GuzzleServiceInterface
{
    public function get($url, $headers = [],$query_params = null);

    public function post($url, $params = [], $headers = []);

    public function put($url, $params = [], $headers = []);

    public function patch($url, $params = [], $headers = []);

    public function delete($url, $params = [], $headers = []);

}
