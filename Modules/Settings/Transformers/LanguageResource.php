<?php

namespace Modules\Settings\Transformers;

class LanguageResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */

    public function toArray($collection, $arrayOrNot = null)
    {
        $data = [];
        if (request()->getMethod() == 'GET') {
            if ($arrayOrNot) {
                foreach ($collection as $item) {
                    $data[] = [
                        'id' => $item->id,
                        'name' => $item->name,
                        'iso' => $item->iso,
                        'is_active' => $item->is_active
                    ];
                }
            } else {
                $data = [
                    'id' => $collection->id,
                    'name' => $collection->name,
                    'iso' => $collection->iso,
                    'is_active' => $collection->is_active
                ];
            }
        } elseif (request()->getMethod() == 'POST') {
            $data = [
                'name' => request()->name,
                'iso' => request()->iso,
                'is_active' => request()->is_active
            ];
        } elseif (request()->getMethod() == 'PUT' || request()->getMethod() == 'PATCH') {
            $data = [];
            $data = request()->name ? ['name' => request()->name] : $data;
            $data = request()->iso ? ['iso' => request()->iso] : $data;
            $data = request()->is_active ? ['is_active' => request()->is_active] : $data;
        }

        return $data;
    }
}
