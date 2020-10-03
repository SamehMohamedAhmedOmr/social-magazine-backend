<?php

namespace Modules\WareHouse\Transformers\Repo\Order;

use Modules\Settings\Repositories\LanguageRepository;

class OrderStatusResource
{
    public static function toArray(LanguageRepository $language_repo)
    {
        $data = (request('names')) ? self::prepareLanguages($language_repo->pluckISOId()) : [];

        if (isset(request()->is_active)) {
            $data['is_active'] = (boolean)request()->is_active;
        }

        if (isset(request()->key)) {
            $data['key'] = request()->key;
        }

        return $data;
    }

    private static function prepareLanguages($iso_ids)
    {
        $data = [];

        $names = array_values(request('names'));
        $order_status_languages = [];

        foreach ($names as $name) {
            $order_status_languages [] = [
                'language_id' => $iso_ids[$name['language']],
                'name' => $name['name'],
            ];
        }
        $data['order_status_languages'] = $order_status_languages;

        return $data;
    }
}
