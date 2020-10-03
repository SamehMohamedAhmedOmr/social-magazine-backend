<?php

namespace Modules\Settings\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class FontsResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->font_name,
            'font_file' => getFilePath('fonts', $this->font_path),
            'type' => $this->getType(),
        ];
    }

    private function getType(){
        // 0 => regular , 1 => bold , 2 => Italic
        switch ($this->type){
            case '0':
                $type = 'Regular';
                break;
            case '1':
                $type = 'Bold';
                break;
            case '2':
                $type = 'Italic';
                break;
            default:
                $type = null;
                break;
        }
        return $type;
    }
}
