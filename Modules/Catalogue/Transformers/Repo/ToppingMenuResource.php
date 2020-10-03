<?php

namespace Modules\Catalogue\Transformers\Repo;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Catalogue\Repositories\CMS\ToppingMenuRepository;
use Modules\Settings\Repositories\LanguageRepository;

class ToppingMenuResource
{
    private $language_repo;
    private $topping_repo;

    public function __construct(LanguageRepository $language_repo, ToppingMenuRepository $topping_repo)
    {
        $this->language_repo = $language_repo;
        $this->topping_repo = $topping_repo;
    }

    public function toArray(Request $request) : array
    {
        $data = $request->has('names') ? $this->prepareLanguages($this->language_repo->pluckISOId(), $request->names, $request->topping) : [];

        if ($request->has('is_active')) {
            $data['is_active'] = (boolean)$request->is_active;
        }

        if ($request->has('products')) {
            $data['products'] = $request->products;
        }

        return $data;
    }

    private function prepareLanguages($iso_ids, $names, $brand_id = null)
    {
        $data = [];
        $topping_languages = [];

        foreach ($names as $name) {
            $slug = Str::slug($name['name'], '-');

            do {
                $check_slug = $this->topping_repo->getSlugIfDuplication($slug, $brand_id);
                $check_slug = $check_slug == null
                    ? array_search($slug, array_column($topping_languages, 'slug')) : null;
                $explode_number = explode('-', $check_slug);
                $number = array_key_exists(1, $explode_number) ? ((integer)$explode_number[1])+1 : 1;
                $slug = $check_slug != null || $check_slug !== false ? $slug.'-'.$number : $slug;
            } while ($check_slug != null || $check_slug !== false);

            $topping_languages [] = [
                'language_id' => $iso_ids[$name['language']],
                'name' => $name['name'],
                'slug' => $slug
            ];
        }
        $data['topping_languages'] = $topping_languages;

        return $data;
    }
}
