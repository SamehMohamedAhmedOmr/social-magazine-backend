<?php

namespace Modules\Configuration\Helpers;


/**
 * Class TenantHelper
 */
class TenantHelper
{
    /**
     * @return mixed
     */
    public function getSubDomain()
    {
        $host = request()->getHttpHost(); // With port if there is. Eg: mydomain.com:81
        $domain_parts = explode('.', $host);
        $sub_domain = null;

        if (count($domain_parts) === 3) {
            $sub_domain = $domain_parts[0];
        }
        else {
            $port_parts = explode(':', $host);
            if (count($port_parts) > 1) {
                $sub_domain = $port_parts[1];
            }
        }

        return $sub_domain;
    }

    public function testTenant($sub_domain)
    {
        $project_data = collect([]);

        $project_data->put('id', 1);
        $project_data->put('title', 'localhost');
        $project_data->put('description', 'localhost development');
        $project_data->put('image', null);
        $project_data->put('message', null);
        $project_data->put('active', null);

        $project_data->put('name', null);
        $project_data->put('slug', '8000');
        $project_data->put('port', null);

        $project_data->put('db_host', '127.0.0.1');
        $project_data->put('db_port', '3306');
        $project_data->put('db_name', 'base-retailak-api');
        $project_data->put('db_username', 'root');
        $project_data->put('db_password', '');

        $project_data->put('redis_host', '127.0.0.1');
        $project_data->put('redis_port', '6379');

        $project_data->put('sub_domain', null);
        $project_data->put('success', null);
        $project_data->put('job_status_id', null);
        $project_data->put('port_url', null);
        $project_data->put('sub_domain_url', null);
        $project_data->put('admin_email', 'admin@retailk.com');
        $project_data->put('admin_password', '$2y$10$6m.mbR0RguTHLiQQsc/PH.R/563ruCL81UMs4WCTlDMDQA/BPWm4q');

        $cache_time = 60 * 60 * 24 * 30; // 60 ’ Minute ( 1 hour * 24 hours )
        // cache the response
        \Cache::store('redis')->put($sub_domain, $project_data, $cache_time);
        return $project_data;
    }

}
