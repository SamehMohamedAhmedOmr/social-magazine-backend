<?php

namespace Modules\Catalogue\Http\Middleware;

use Closure;
use Illuminate\Foundation\Application as App;
use Illuminate\Http\Request;
use Illuminate\Session\SessionManager as Session;
use Modules\WareHouse\Repositories\DistrictRepository;
use Modules\WareHouse\Repositories\WarehouseRepository;

class DetectWarehouse
{
    protected $app;
    protected $session;

    private $district_repository;
    private $warehouse_repository;

    /**
     * Init new object.
     *
     * @param App $app
     * @param Session $session
     * @param DistrictRepository $district_repository
     * @param WarehouseRepository $warehouse_repository
     */
    public function __construct(
        App $app,
        Session $session,
        DistrictRepository $district_repository,
        WarehouseRepository $warehouse_repository
    ) {
        $this->app = $app;
        $this->session = $session;

        $this->district_repository = $district_repository;
        $this->warehouse_repository = $warehouse_repository;
    }
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $district_id = $request->header('district-id');
        $district_id = trim($district_id);
        $district_id = (int)$district_id;

        $warehouses_id = $this->detectWarehouse($district_id);

        $this->session->put('district_id', $district_id);
        $this->session->put('warehouses_id', $warehouses_id);

        return $next($request);
    }

    private function detectWarehouse($district_id = null)
    {
        $district_exist = false;
        $warehouses_id = [];

        if ($district_id) {
            $district = $this->district_repository->getDistrict($district_id);
            if ($district) {
                $warehouses = $district->warehouse;
                if ($warehouses) {
                    $district_exist = true;
                    $warehouses_id = $warehouses->pluck('id');
                }
            }
        }

        if ($district_exist == false) {
            $warehouse = $this->warehouse_repository->getDefault();
            $warehouses_id [] = $warehouse->id;
        }

        return $warehouses_id;
    }
}
