<?php

namespace Modules\PreArticle\Helpers;

class ArticleTypeCollection
{

    public function ORIGINAL_ARTICLE()
    {
        return [
            'key' => $this->ORIGINAL_ARTICLE_KEY(),
            'name' => 'المقالة الأصلية',
        ];
    }
    public function ORIGINAL_ARTICLE_KEY(){
        return 'ORIGINAL_ARTICLE';
    }

}
