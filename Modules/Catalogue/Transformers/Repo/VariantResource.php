<?php

namespace Modules\Catalogue\Transformers\Repo;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Catalogue\Repositories\CMS\VariantRepository;
use Modules\Settings\Repositories\LanguageRepository;

class VariantResource
{
    private $language_repo;
    private $variant_repo;

    public function __construct(LanguageRepository $language_repo, VariantRepository $variant_repo)
    {
        $this->language_repo = $language_repo;
        $this->variant_repo = $variant_repo;
    }

    public function toArray(Request $request) : array
    {
        $data = $request->has('names') ? $this->prepareLanguages($this->language_repo->pluckISOId(), $request->names, $request->variant) : [];

        if ($request->has('is_active')) {
            $data['is_active'] = (boolean)$request->is_active;
        }

        if ($request->has('is_color')) {
            $data['is_color'] = (boolean)$request->is_color;
        }

        return $data;
    }

    private function prepareLanguages($iso_ids, $names, $variant_id = null)
    {
        $data = [];
        $variant_languages = [];

        foreach ($names as $name) {
            $slug = Str::slug($name['name'], '-');

            do {
                $check_slug = $this->variant_repo->getSlugIfDuplication($slug, $variant_id);
                $check_slug = $check_slug == null
                    ? array_search($slug, array_column($variant_languages, 'slug')) : null;
                $explode_number = explode('-', $check_slug);
                $number = array_key_exists(1, $explode_number) ? ((integer)$explode_number[1])+1 : 1;
                $slug = $check_slug != null || $check_slug !== false ? $slug.'-'.$number : $slug;
            } while ($check_slug != null || $check_slug !== false);

            $variant_languages [] = [
                'language_id' => $iso_ids[$name['language']],
                'name' => $name['name'],
                'slug' => $slug
            ];
        }
        $data['variant_languages'] = $variant_languages;

        return $data;
    }
}
