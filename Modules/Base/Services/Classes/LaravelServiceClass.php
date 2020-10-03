<?php

namespace Modules\Base\Services\Classes;

use Modules\Base\Facade\Pagination;
use Modules\Base\Services\Interfaces\LaravelServiceInterface;

class LaravelServiceClass implements LaravelServiceInterface
{
    protected $repository;

    public function index()
    {
        // TODO: Implement index() method.
    }

    public function store()
    {
        // TODO: Implement store() method.
    }

    public function show($id)
    {
        // TODO: Implement show() method.
    }

    public function update($id)
    {
        // TODO: Implement update() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function paginate($model_repo, $language_id, $has_active_key = true, $extra_conditions = [])
    {
        list($pagination_number, $sort_key, $sort_order, $conditions, $search_key) = Pagination::preparePaginationKeys(
            request('per_page'),
            request('sort_key'),
            request('sort_order'),
            request('search_key'),
            request('is_active'),
            $has_active_key
        );

        $conditions = (isset($extra_conditions)) ? array_merge($conditions, $extra_conditions) : $conditions;

        $model_data = $model_repo->paginate($pagination_number, $conditions, $search_key, $sort_key, $sort_order, $language_id);

        $pagination = Pagination::preparePagination($model_data);

        return [$model_data, $pagination];
    }

    public function list($model_repo, $has_active_key = true, $extra_conditions = [])
    {
        $conditions = Pagination::prepareIsActiveKey(
            request('is_active'),
            $has_active_key
        );

        $conditions = (isset($extra_conditions)) ? array_merge($conditions, $extra_conditions) : $conditions;

        return $model_repo->all($conditions, request('search_key'));
    }
}
