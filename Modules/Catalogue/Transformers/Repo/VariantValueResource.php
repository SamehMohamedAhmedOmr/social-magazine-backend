<?php

namespace Modules\Catalogue\Transformers\Repo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Catalogue\Repositories\CMS\VariantValueRepository;
use Modules\Settings\Repositories\LanguageRepository;

class VariantValueResource
{
    private $language_repo;
    private $variant_value_repo;

    public function __construct(LanguageRepository $language_repo, VariantValueRepository $variant_value_repo)
    {
        $this->language_repo = $language_repo;
        $this->variant_value_repo = $variant_value_repo;
    }

    public function toArray(Request $request) : array
    {
        $data = $request->has('names') ? $this->prepareLanguages($this->language_repo->pluckISOId(), $request->names, $request->variant_value) : [];

        if ($request->has('variant_id')) {
            $data['variant_id'] = $request->variant_id;
        }

        if ($request->has('value')) {
            $data['value'] = $request->value;
        }

        if ($request->has('code')) {
            $data['code'] = $request->code;
        }

        if ($request->has('palette_image')) {
            $path = Storage::putFile('public/images/variants-values/palette/', $request->palette_image);
            $data['palette_image'] = $path;
        }

        if ($request->has('is_active')) {
            $data['is_active'] = (boolean)$request->is_active;
        }

        return $data;
    }


    private function prepareLanguages($iso_ids, $names, $variant_value_id = null)
    {
        $data = [];
        $variant_value_languages = [];

        foreach ($names as $name) {
            $slug = Str::slug($name['name'], '-');

            do {
                $check_slug = $this->variant_value_repo->getSlugIfDuplication($slug, $variant_value_id);
                $check_slug = $check_slug == null
                    ? array_search($slug, array_column($variant_value_languages, 'slug')) : null;
                $explode_number = explode('-', $check_slug);
                $number = array_key_exists(1, $explode_number) ? ((integer)$explode_number[1])+1 : 1;
                $slug = $check_slug != null || $check_slug !== false ? $slug.'-'.$number : $slug;
            } while ($check_slug != null || $check_slug !== false);

            $variant_value_languages [] = [
                'language_id' => $iso_ids[$name['language']],
                'name' => $name['name'],
                'slug' => $slug
            ];
        }
        $data['variant_value_languages'] = $variant_value_languages;

        return $data;
    }
}
