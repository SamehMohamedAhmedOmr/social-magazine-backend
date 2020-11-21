<?php

namespace Modules\PreArticle\Helpers;


class PriceTypeCollection
{

    public function ARTICLE_JUDGEMENT_FEES()
    {
        return [
            'key' => $this->ARTICLE_JUDGEMENT_FEES_KEY(),
            'name' => 'رسوم تحكيم المقال (الابتدائي)',
        ];
    }
    public function ARTICLE_JUDGEMENT_FEES_KEY(){
        return 'ARTICLE_JUDGEMENT_FEES';
    }

    public function ARTICLE_ACCEPTANCE_FEES()
    {
        return [
            'key' => $this->ARTICLE_ACCEPTANCE_FEES_KEY(),
            'name' => 'رسوم قبول المقال (النهائي)',
        ];
    }
    public function ARTICLE_ACCEPTANCE_FEES_KEY(){
        return 'ARTICLE_ACCEPTANCE_FEES';
    }

}
