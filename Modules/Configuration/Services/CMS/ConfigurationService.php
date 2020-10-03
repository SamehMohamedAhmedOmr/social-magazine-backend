<?php

namespace Modules\Configuration\Services\CMS;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\GuzzleServiceClass;

class ConfigurationService
{
    private $url, $guzzle_service, $secret_key, $client_id;

    public function __construct(GuzzleServiceClass $guzzle_service)
    {
        $this->url = env('APP_RETAILAK_MANAGEMENT_CMS_URL',
            'http://163.172.8.204:8012/api/');
        $this->secret_key = env('retalik_base_secret_key',
            'N7Q8R9TBUCVEXFYG2J3K4N6P7Q9SATBVDWEXGZH2J3M5N6P8R9SAUCVDWF');
        $this->client_id = env('retalik_base_client_id',
            1);
        $this->client_id = intval($this->client_id);

        $this->guzzle_service = $guzzle_service;
    }

    public function baseConfiguration($slug, $request = [])
    {
        $url =  $this->url . 'projects/client/'.$slug;

        $signature = $this->calcSignature(($request) ? $request->all(): [] , $this->secret_key);

        $response = $this->guzzle_service->get($url,[
            'client-id' =>  $this->client_id,
            'signature' => $signature
        ]);

        $response = json_decode($response);

        $response = ($response->data) ? $response->data : null;

        return $this->prepareProjectResponse($response, $slug);
    }

    public function getAllProjects($request = [])
    {
        $url =  $this->url . 'projects/client?perPage=100000';

        $signature = $this->calcSignature([
                'perPage' => 100000
        ] , $this->secret_key);

        $response = $this->guzzle_service->get($url,[
            'client-id' =>  $this->client_id,
            'signature' => $signature
        ]);

        $response = json_decode($response);

        $response = ($response->data) ? $response->data : null;

        return $this->prepareAllProjects($response);
    }


    /**
     * Calculate the signature.
     *
     * @param  array  $params
     * @param  string $secret
     * @return string
     */
    private function calcSignature($params, $secret)
    {
        ksort($params);
        $shaString = $secret . '';
        foreach ($params as $key => $value)
        {
            $shaString .= $key . '=' . $value;
        }

        return hash('SHA256', $shaString);
    }


    private function prepareProjectResponse($project, $slug = null)
    {
        if ($project){
            $project_data = collect([]);
            $project_data->put('id', $project->id);
            $project_data->put('title', $project->title);
            $project_data->put('description', $project->description);
            $project_data->put('image', $project->image);
            $project_data->put('message', $project->message);
            $project_data->put('active', $project->active);

            $project_data->put('name', $project->name);
            $project_data->put('slug', $project->slug);
            $project_data->put('port', $project->port);

            $project_data->put('db_host', $project->db_host);
            $project_data->put('db_port', $project->db_port);
            $project_data->put('db_name', $project->db_name);
            $project_data->put('db_username', $project->db_username);
            $project_data->put('db_password', $project->db_password);

            $project_data->put('redis_host', $project->redis_host);
            $project_data->put('redis_port', $project->redis_port);

            $project_data->put('sub_domain', $project->sub_domain);
            $project_data->put('success', $project->success);
            $project_data->put('job_status_id', $project->job_status_id);
            $project_data->put('port_url', $project->port_url);
            $project_data->put('sub_domain_url', $project->sub_domain_url);
            $project_data->put('admin_email', $project->admin_email);
            $project_data->put('admin_password', $project->admin_password);


            $slug = isset($slug) ? $slug : $project->slug;
            $cache_time = 60 * 60 * 24; // 60 ’ Minute ( 1 hour * 24 hours )
            // cache the response
            \Cache::store('redis')->put($slug, $project_data, $cache_time);

            return $project_data;
        }
        return $project;
    }

    private function prepareAllProjects($projects){
        $projects_collection = collect([]);
        if (isset($projects)){
            foreach ($projects as $project){
                $project = $this->prepareProjectResponse($project);
                $projects_collection->push($project);
            }
        }
        return $projects_collection;
    }

}
