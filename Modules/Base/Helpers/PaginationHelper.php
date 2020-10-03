<?php

namespace Modules\Base\Helpers;

use Modules\Base\ResponseShape\PaginationResource;

// Pagination Helper methods

class Pagination
{

    // prepare pagination for collections
    public function preparePagination($object)
    {
        $data = $object->toArray();
        return new PaginationResource($data);
    }

    public function preparePaginationKeys($pagination_number, $sort_key, $sort_order, $search_key, $is_active, $has_active_key = true)
    {
        $pagination_number = isset($pagination_number) ? $pagination_number : 15 ;
        $sort_key =  isset($sort_key) ? $sort_key : 'id' ;
        $sort_order =  isset($sort_order) ? $sort_order : 'asc' ;

        if ($has_active_key) {
            $active =  isset($is_active) ? $is_active : null;
            (isset($active)) ? $conditions['is_active']  = $active : $conditions = [];
        } else {
            $conditions = [];
        }

        return [$pagination_number,$sort_key,$sort_order,$conditions,$search_key];
    }

    public function prepareIsActiveKey($is_active, $has_active_key = true)
    {
        if ($has_active_key) {
            $active =  isset($is_active) ? $is_active : null;
            (isset($active)) ? $conditions['is_active']  = $active : $conditions = [];
        } else {
            $conditions = [];
        }

        return $conditions;
    }

}
