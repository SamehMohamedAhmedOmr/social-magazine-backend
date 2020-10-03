<?php

namespace Modules\Catalogue\Transformers\Repo;

use Illuminate\Support\Str;
use Modules\Catalogue\Repositories\CMS\BrandRepository;
use Modules\Settings\Repositories\LanguageRepository;

class BrandResource
{
    private static $brand_repository;

    public static function toArray(
        LanguageRepository $language_repo,
        BrandRepository $brand_repository,
        $image_icon = [],
        $brand_id = null
    ) {
        self::$brand_repository = $brand_repository;
        $data = (request('names')) ? self::prepareLanguages($language_repo->pluckISOId(), $brand_id) : [];

        if (isset(request()->is_active)) {
            $data['is_active'] = (boolean)request()->is_active;
        }

        $data['icon'] = $image_icon['icon'];

        return $data;
    }

    private static function prepareLanguages($iso_ids, $brand_id = null)
    {
        $data = [];

        $names = array_values(request('names'));
        $brand_languages = [];

        foreach ($names as $name) {
            $slug = Str::slug($name['name'], '-');

            do {
                $check_slug = self::$brand_repository->getSlugIfDuplication($slug, $brand_id);
                $check_slug = $check_slug == null
                    ? array_search($slug, array_column($brand_languages, 'slug')) : null;
                $explode_number = explode('-', $check_slug);
                $number = array_key_exists(1, $explode_number) ? ((integer)$explode_number[1])+1 : 1;
                $slug = $check_slug != null || $check_slug !== false ? $slug.'-'.$number : $slug;
            } while ($check_slug != null || $check_slug !== false);

            $brand_languages [] = [
                'language_id' => $iso_ids[$name['language']],
                'name' => $name['name'],
                'slug' => $slug
            ];
        }
        $data['brand_languages'] = $brand_languages;

        return $data;
    }
}
