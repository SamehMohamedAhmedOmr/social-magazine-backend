<?php

namespace Modules\Base\Repositories\Interfaces;

interface LaravelRepositoryInterface
{
    public function all($conditions = []);

    public function paginate($query, $per_page = 15, $conditions = [], $sort_key = 'id', $sort_order = 'asc');

    public function create(array $attributes);

    public function get($value, $conditions = [], $column = 'id');

    public function update($column, $attributes =[], $conditions = [], $value = 'id');

    public function delete($value, $conditions = [], $column = 'id');
}
