<?php


namespace Modules\WareHouse\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\WareHouse\Entities\Order\OrderNotes;

class OrderNotesRepository extends LaravelRepositoryClass
{

    public function __construct(OrderNotes $orderNotes)
    {
        $this->model = $orderNotes;
        $this->cache = 'order_notes';
    }

    public function paginate($per_page = 15, $conditions = [], $search_keys = [], $sort_key = 'id', $sort_order = 'asc', $lang = null)
    {
        $query = $this->filtering($search_keys);

        return parent::paginate($query, $per_page, $conditions, $sort_key, $sort_order);
    }

    public function all($conditions = [], $search_keys = null)
    {
        $query = $this->filtering($search_keys);

        return $query->where($conditions)->get();
    }

    private function filtering($search_keys){

        $query = $this->model;

        $query = ($search_keys) ? $query->where('note', 'LIKE', '%'.$search_keys.'%') : $query;

        return $query;
    }


}
