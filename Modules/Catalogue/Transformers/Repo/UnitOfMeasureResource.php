<?php

namespace Modules\Catalogue\Transformers\Repo;

use Illuminate\Support\Str;
use Modules\Catalogue\Repositories\CMS\UnitOfMeasureRepository;
use Modules\Settings\Repositories\LanguageRepository;

class UnitOfMeasureResource
{
    private static $unit_of_measure_repository;

    public static function toArray(LanguageRepository $language_repo, UnitOfMeasureRepository $unit_of_measure_repository)
    {
        $data = [];
        self::$unit_of_measure_repository = $unit_of_measure_repository;
        $data = (new self)->prepareLanguages($language_repo->pluckISOId());

        if (isset(request()->is_active)) {
            $data['is_active'] = (boolean)request()->is_active;
        }

        return $data;
    }

    private function prepareLanguages($iso_ids)
    {
        $data = [];

        if (request('names')) {
            $names = array_values(request('names'));
            $unit_of_measure_languages = [];

            foreach ($names as $name) {
                $unit_of_measure_languages [] = [
                    'language_id' => $iso_ids[$name['language']],
                    'name' => $name['name'],
                ];
            }
            $data['unit_of_measure_languages'] = $unit_of_measure_languages;
        }

        return $data;
    }
}
