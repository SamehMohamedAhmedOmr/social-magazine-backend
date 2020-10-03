<?php

namespace Modules\ACL\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\ACL\Facade\PermissionHelper;
use Modules\Base\Facade\DbHelper;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table = 'permissions';
        Model::unguard();
        $excludedColumnsFromUpdate = ['created_at'];

        $permissions = $this->getPermissions();

        DbHelper::insertOrUpdate($table, $permissions, $excludedColumnsFromUpdate);
    }

    public function getPermissions()
    {
        $getRouteCollection = \Route::getRoutes(); //get and returns all returns route collection

        $permissions = [];

        $allowable = PermissionHelper::allowable();

        $created_at = Carbon::now();

        $ignored_route_name = collect([
            'admins.',
            'admins.logout',
            'admins.reports.cms.dashboard.statistics'
        ]);

        foreach ($getRouteCollection as $route) {
            $route_name = $route->getName();
            if (!$ignored_route_name->contains($route_name)){
                $contains = \Str::contains($route_name, $allowable);
                if ($route_name && $contains) {
                    $key = PermissionHelper::generateKey($route_name);

                    $name = $this->getName($route_name);

                    $permissions [] = [
                        'route_name' => $route_name,
                        'key' => $key,
                        'name' => ucwords($name),
                        'created_at' => $created_at
                    ];
                }
            }
        }

        return $permissions;
    }


    public function prepareName($route_parts, $count_parts)
    {
        $name = null;
        foreach ($route_parts as $index => $part) {
            if ($index == ($count_parts - 1)) {
                continue;
            }
            $name = ($name) ? $name . ' ' . $part : $part;
        }

        return str_replace('-', ' ', $name);
    }

    public function getName($route_name)
    {
        $route_parts = explode(".", $route_name);

        $count_parts = count($route_parts);
        $route_functions = ($count_parts > 1) ? $route_parts[$count_parts - 1] : '';

        $name = $this->prepareName($route_parts, $count_parts);

        switch ($route_functions) {
            case 'index':
            case 'list':
                $method = 'list';
                break;

            case 'store':
            case 'create':
                $method = 'create';
                break;

            case 'show':
                $method = 'get';
                break;

            case 'update':
                $method = 'update';
                break;

            case 'destroy':
                $method = 'delete';
                break;
            default:
                $method = $this->prepareOthersNames($route_parts, $name);
                break;
        }

        if ($method) {
            $name = $name . ' ' . $method;
        }
        return $name;
    }

    public function prepareOthersNames($route_parts, $name)
    {
        if ($name) {
            $name =  $route_parts[count($route_parts) - 1];
        } else {
            foreach ($route_parts as $index => $part) {
                if ($index == 0) {
                    continue;
                }
                $name = ($name) ? $name . ' ' . $part : $part;
            }
        }

        return $name;
    }
}
