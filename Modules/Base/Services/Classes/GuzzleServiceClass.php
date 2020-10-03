<?php

namespace Modules\Base\Services\Classes;

use Modules\Base\Services\Interfaces\GuzzleServiceInterface;
use GuzzleHttp\Client;

class GuzzleServiceClass implements GuzzleServiceInterface
{

    private $client;
    public function __construct(Client $client)
    {
       $this->client = $client;
    }

    public function get($url, $headers = [], $query_params = null)
    {
        $url = ($query_params) ? $url . '?' . $query_params : $url;
        $request = $this->client->get($url, [
            'headers' => $headers
        ]);
        return $request->getBody();
    }

    public function post($url, $params = [], $headers = [])
    {
        $request = $this->client->post($url,  [
            'body' => $params,
            'headers' => $headers
        ]);
        return $request->send();
    }

    public function put($url, $params = [], $headers = [])
    {
        $request = $this->client->put($url,  [
            'body' => $params,
            'headers' => $headers
        ]);
        return $request->send();
    }

    public function patch($url, $params = [], $headers = [])
    {
        $request = $this->client->patch($url,  [
            'body' => $params,
            'headers' => $headers
        ]);
        return $request->send();
    }

    public function delete($url, $params = [], $headers = [])
    {
        $request = $this->client->delete($url, [
            'body' => $params,
            'headers' => $headers
        ]);
        return $request->send();
    }

}
