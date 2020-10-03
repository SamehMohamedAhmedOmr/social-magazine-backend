<?php

namespace Modules\Catalogue\Transformers\Repo;

use Illuminate\Support\Str;
use Modules\Catalogue\Repositories\CMS\CategoryRepository;
use Modules\Settings\Repositories\LanguageRepository;

class CategoryResource
{
    private static $category_repository;

    public static function toArray(
        LanguageRepository $language_repo,
        CategoryRepository $category_repository,
        $image_icon = [],
        $category_id = null
    ) {
        $data = [];
        self::$category_repository = $category_repository;
        $data = (request('names')) ? self::prepareLanguages($language_repo->pluckISOId(), $category_id) : [];

        if (request()->code) {
            $data['code'] = request()->code;
        }

        if (isset(request()->is_active)) {
            $data['is_active'] = (boolean)request()->is_active;
        }

        if (request()->has('parent_id')) {
            $data['parent_id'] = request()->parent_id;
        }

        if (isset($image_icon['image'])){
            $data['image'] = $image_icon['image'];
        }

        if (isset($image_icon['icon'])){
            $data['icon'] = $image_icon['icon'];
        }

        return $data;
    }

    private static function prepareLanguages($iso_ids, $category_id)
    {
        $data = [];

        $names = array_values(request('names'));
        $category_languages = [];

        foreach ($names as $name) {
            $slug = Str::slug($name['name'], '-');

            do {
                $check_slug = self::$category_repository->getSlugIfDuplication($slug, $category_id);
                $check_slug = $check_slug == null
                    ? array_search($slug, array_column($category_languages, 'slug')) : null;
                $explode_number = explode('-', $check_slug);
                $number = array_key_exists(1, $explode_number) ? ((integer)$explode_number[1])+1 : 1;
                $slug = $check_slug != null || $check_slug !== false ? $slug.'-'.$number : $slug;
            } while ($check_slug != null || $check_slug !== false);

            $category_languages [] = [
                'language_id' => $iso_ids[$name['language']],
                'name' => $name['name'],
                'slug' => $slug
            ];
        }
        $data['category_languages'] = $category_languages;

        return $data;
    }
}
