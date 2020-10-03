<?php

namespace Modules\Settings\Repositories;

use Modules\Base\Facade\LanguageFacade;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Settings\Entities\PaymentMethod;

class PaymentMethodRepository extends LaravelRepositoryClass
{
    public function __construct(PaymentMethod $payment_method)
    {
        $this->model = $payment_method;
        $this->cache_key = 'payment_method';
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

        if ($search_keys) {
            $query = $query->where(function ($q) use ($search_keys){

                $where_conditions = ($search_keys) ? [
                    ['payment_methods_languages.name', 'LIKE', '%'.$search_keys.'%']
                ] : [];

                $or_where_conditions = [];

                $q = LanguageFacade::loadLanguage($q, \Session::get('language_id'), 'languages', $search_keys,
                    $where_conditions, $or_where_conditions);


                $q->orWhere('id', 'LIKE', '%'.$search_keys.'%')
                    ->orWhere('key', 'LIKE', '%'.$search_keys.'%');
            });
        }

        return $query;
    }

    public function syncLanguage($payment_method, $payment_method_languages)
    {
        $payment_method->languages()->sync($payment_method_languages);
        return $payment_method;
    }

    public function updateLanguage($payment_method, $payment_method_languages)
    {
        $payment_method->languages()->detach();
        $payment_method->languages()->attach($payment_method_languages);
        return $payment_method;
    }


}
