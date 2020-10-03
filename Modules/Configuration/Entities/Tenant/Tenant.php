<?php

namespace Modules\Configuration\Entities\Tenant;

use Arr;
use Illuminate\Redis\RedisManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Schema;

class Tenant
{
    private $database,
        $db_host,
        $redis_host,
        $db_port,
        $username,
        $password,
        $redis_port;


    public function __construct($project_information)
    {
        $this->db_host = $project_information['db_host'];
        $this->database = $project_information['db_name'];
        $this->db_port = $project_information['db_port'];
        $this->username = $project_information['db_username'];
        $this->password = $project_information['db_password'];

        $this->redis_host = $project_information['redis_host'];
        $this->redis_port = $project_information['redis_port'];

//        $this->host = '127.0.0.1';
//        $this->port = '3306';
//        $this->database = 'base-retailak-api';
//        $this->username = 'root';
//        $this->password = '';
//
//        $this->redis_host = '127.0.0.1';
//        $this->redis_port = '6379';
    }

    /**
     *
     */
    public function configure()
    {
        config([
            // Database
            'database.connections.mysql.host' => $this->db_host,
            'database.connections.mysql.port' => $this->db_port,
            'database.connections.mysql.database' => $this->database,
            'database.connections.mysql.username' => $this->username,
            'database.connections.mysql.password' => $this->password,
        ]);

        // redis
        config([
            'database.redis.tenant-connection' => [
                'host' => $this->redis_host,
                'password' => null,
                'port' => $this->redis_port,
                'database' => 0
            ]
        ]);

        DB::purge('mysql');

        DB::reconnect('mysql');

        Schema::connection('mysql')->getConnection()->reconnect();

        app()->forgetInstance('redis');
        app()->forgetInstance('redis.connection');
        app()->singleton('redis', function ($app) {
            $config = $app->make('config')->get('database.redis', []);

            return new RedisManager($app, Arr::pull($config, 'client', 'phpredis'), $config);
        });

        app()->bind('redis.connection', function ($app) {
            return $app['redis']->connection();
        });

        return $this;
    }

    /**
     *
     */
    public function use()
    {
        app()->forgetInstance('mysql');

        app()->instance('mysql', $this);

        return $this;
    }

}
