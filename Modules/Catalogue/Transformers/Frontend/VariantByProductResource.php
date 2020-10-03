<?php

namespace Modules\Catalogue\Transformers\Frontend;

use \DB;

class VariantByProductResource
{
    /**
     * @param $variants
     * @return array
     */
    public function toArray($variants)
    {
        $variant_data = [];
        if ($variants !== [] && $variants !== null) {
            $i = 0;
            $variants = $variants->groupBy('variant_id');
            $used_variant_ids = [];
            foreach ($variants as $variant_id => $variant) {
                foreach ($variant as $values) {
                    $variant_exist =  array_search($variant_id, $used_variant_ids);
                    if ($variant_exist === false) {
                        $variant_data[$i]['id'] = $variant_id;
                        $variant_data[$i]['name'] = $values->variant->currentLanguage->name;
                        $variant_data[$i]['values'] = [];
                        $used_variant_ids[] = $variant_id;
                    }
                    $variant_data[$i]['values'] = [
                        'id' => $values->id,
                        'name' => $values->currentLanguage->name,
                        'code' => $values->code,
                        'value' => $values->value,
                        'palette_image' => isset($values->palette_image) ? url(\Storage::url($values->palette_image)) : '',
                    ];
                }
                $i++;
            }
        }
        return $variant_data;
    }
}
