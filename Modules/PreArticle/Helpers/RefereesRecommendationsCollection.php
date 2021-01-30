<?php

namespace Modules\PreArticle\Helpers;


class RefereesRecommendationsCollection
{

    public function ACCEPTED()
    {
        return [
            'key' => $this->ACCEPTED_KEY(),
            'name' => 'مقبول',
        ];
    }
    public function ACCEPTED_KEY(){
        return 'ACCEPTED';
    }

    public function PARTIAL_REVIEW()
    {
        return [
            'key' => $this->PARTIAL_REVIEW_KEY(),
            'name' => 'مراجعة جزئية',
        ];
    }
    public function PARTIAL_REVIEW_KEY(){
        return 'PARTIAL_REVIEW';
    }

    public function FULL_REVIEW()
    {
        return [
            'key' => $this->FULL_REVIEW_KEY(),
            'name' => 'مراجعة كلية',
        ];
    }
    public function FULL_REVIEW_KEY(){
        return 'FULL_REVIEW';
    }

    public function REJECTED()
    {
        return [
            'key' => $this->REJECTED_KEY(),
            'name' => 'رفض',
        ];
    }
    public function REJECTED_KEY(){
        return 'REJECTED';
    }

    public function UNABLE_TO_JUDGEMENT()
    {
        return [
            'key' => $this->UNABLE_TO_JUDGEMENT_KEY(),
            'name' => 'غير قادر على التحكيم',
        ];
    }
    public function UNABLE_TO_JUDGEMENT_KEY(){
        return 'UNABLE_TO_JUDGEMENT';
    }


}
