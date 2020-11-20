<?php

namespace Modules\PreArticle\Helpers;

use Modules\PreArticle\Facades\StatusFilterKey;

class StatusFilterCollection
{

    public function NEW()
    {
        return [
            'key' => StatusFilterKey::NEW(),
            'name' => 'مقالات جديدة',
        ];
    }


}
