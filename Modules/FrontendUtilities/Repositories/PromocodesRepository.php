<?php


namespace Modules\FrontendUtilities\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\FrontendUtilities\Entities\Promocode;

class PromocodesRepository extends LaravelRepositoryClass
{
    protected $model;
    public function __construct(Promocode $promocode)
    {
        $this->model = $promocode;
        $this->cache = 'promocode';
    }

    public function paginate($per_page = 15, $conditions = [], $search_keys = null, $sort_key = 'id', $sort_order = 'asc', $lang = null)
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

        if ($search_keys) {
            $query = $query->where(function ($q) use ($search_keys){
                $q->where('code', 'LIKE', '%'.$search_keys.'%')
                    ->orWhere('id', 'LIKE', '%'.$search_keys.'%')
                    ->orWhere('minimum_price', 'LIKE', '%'.$search_keys.'%')
                    ->orWhere('maximum_price', 'LIKE', '%'.$search_keys.'%');
            });
        }

        return $query;
    }

    public function attachUsers($promocode, $users)
    {
        $promocode->users()->sync($users);
    }

    public function updateUsers($promocode, $users)
    {
        $promocode->users()->detach();
        $promocode->users()->attach($users);
    }

    public function attachProducts($promocode, $products)
    {
        $promocode->products()->sync($products);
    }

    public function updateProducts($promocode, $products)
    {
        $promocode->products()->detach();
        $promocode->products()->attach($products);
    }

    public function attachCategories($promocode, $categories)
    {
        $promocode->categories()->sync($categories);
    }

    public function updateCategories($promocode, $categories)
    {
        $promocode->categories()->detach();
        $promocode->categories()->attach($categories);
    }

    public function attachBrands($promocode, $brands)
    {
        $promocode->brands()->sync($brands);
    }

    public function updateBrands($promocode, $brands)
    {
        $promocode->brands()->detach();
        $promocode->brands()->attach($brands);
    }

    /**
     * Return promocode users count.
     *
     * @param   integer $user_id
     *
     * @return  integer
     */
    public function userCount($promocode, $user_id = false)
    {
        $promocode = is_int($promocode) ? $this->get($promocode): $promocode;
        $query = $promocode->users();
        if ($user_id) {
            $query->where('user_id', $user_id);
        }

        return $query->count();
    }

    /**
     * Return promocode count uses for the given user.
     *
     * @param   mixed   $promocode
     * @param   integer $user_id
     *
     * @return  integer
     */
    public function usagePerUserCount($promocode, $user_id)
    {
        $promocode = is_int($promocode) ? $this->get($promocode): $promocode;
        return $promocode->usage()->where('user_id', $user_id)->count();
    }

    /**
     * Log the given promocode used for the given user.
     *
     * @param   mixed   $promocode
     * @param   integer $order_id
     * @param   integer $sales_order_id
     * @param   integer $user_id
     *
     * @return  integer
     */
    public function logUsage($promocode, $discount, $order_id, $sales_order_id, $user_id)
    {
        $promocode = is_int($promocode) ? $this->get($promocode): $promocode;
        $promocode->total_usage_count++;
        $promocode->save();
        $promocode->usage()->create([
            'discount' => $discount,
            'order_id' => $order_id,
            'sales_order_id' => $sales_order_id,
            'user_id' => $user_id
        ]);
    }
}
