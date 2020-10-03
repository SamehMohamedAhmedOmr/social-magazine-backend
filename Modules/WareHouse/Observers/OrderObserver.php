<?php

namespace Modules\WareHouse\Observers;

use Modules\Base\Facade\UtilitiesHelper;
use Modules\Notifications\Notifications\OrderCreated;
use Modules\Users\Repositories\AdminRepository;
use Modules\WareHouse\Entities\Order\Order;

class OrderObserver
{
    protected $adminRepo;

    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepo = $adminRepository;
    }

    public function creating(Order $object)
    {
        $object->country_id = UtilitiesHelper::getCountryId();

    }

    /**
     * Handle the User "created" event.
     *
     * @param Order $object
     * @return void
     */
    public function created(Order $object)
    {
        $admins = $this->adminRepo->adminsByWarehouse($object->warehouse_id);
        \Notification::send($admins, new OrderCreated($object));
    }
}
