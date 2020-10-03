<?php

namespace Modules\Gallery\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Gallery\Entities\Gallery;

class GalleryRepository extends LaravelRepositoryClass
{
    public function __construct(Gallery $gallery)
    {
        $this->model = $gallery;
    }

    public function paginate($per_page = 15, $conditions = [], $search_keys = null, $sort_key = 'id', $sort_order = 'asc', $lang = null)
    {
        return parent::paginate($this->model, $per_page, $conditions, $sort_key, $sort_order);
    }
}
