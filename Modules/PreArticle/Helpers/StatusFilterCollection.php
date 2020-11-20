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

    public function NOT_COMPLETED()
    {
        return [
            'key' => StatusFilterKey::NOT_COMPLETED(),
            'name' => 'مقالات غير مكتملة',
        ];
    }

    public function SPECIALIZED_FOR_EDITOR()
    {
        return [
            'key' => StatusFilterKey::SPECIALIZED_FOR_EDITOR(),
            'name' => 'مقالات مخصصة للمحرر',
        ];
    }

    public function DONE_BY_EDITOR()
    {
        return [
            'key' => StatusFilterKey::DONE_BY_EDITOR(),
            'name' => 'مقالات قام بهاالمحرر',
        ];
    }

    public function SPECIALIZED_FOR_REFEREES()
    {
        return [
            'key' => StatusFilterKey::SPECIALIZED_FOR_REFEREES(),
            'name' => 'مقالات مخصصة للتحكيم',
        ];
    }

    public function NOT_BEEN_JUDGED_AT_TIME()
    {
        return [
            'key' => StatusFilterKey::NOT_BEEN_JUDGED_AT_TIME(),
            'name' => 'مقالات لم يتم تحكيمها في التاريخ المحدد',
        ];
    }

    public function BEEN_JUDGED_FROM_ALL()
    {
        return [
            'key' => StatusFilterKey::BEEN_JUDGED_FROM_ALL(),
            'name' => 'مقالات تم تحكيمها من كل المحكمين',
        ];
    }

    public function BEEN_JUDGED_FROM_SOME()
    {
        return [
            'key' => StatusFilterKey::BEEN_JUDGED_FROM_SOME(),
            'name' => 'مقالات تم تحكيمها من بعض المحكمين',
        ];
    }

    public function NEED_REVIEW()
    {
        return [
            'key' => StatusFilterKey::NEED_REVIEW(),
            'name' => 'مقالات تحتاج لمراجعة من المؤلف',
        ];
    }

    public function BEEN_REVIEWED()
    {
        return [
            'key' => StatusFilterKey::BEEN_REVIEWED(),
            'name' => 'مقالات تم مراجعتها من المؤلف',
        ];
    }

    public function NOT_REVIEWED_AT_TIME()
    {
        return [
            'key' => StatusFilterKey::NOT_REVIEWED_AT_TIME(),
            'name' => 'مقالات لم يقم المؤلفون بمراجعتها بعد انتهاء التاريخ المحدد للمراجعة',
        ];
    }

    public function NOT_PUBLISHED()
    {
        return [
            'key' => StatusFilterKey::NOT_PUBLISHED(),
            'name' => 'كل المقالات المعلقة (غير منشورة)',
        ];
    }

    public function FINALLY_ACCEPTED()
    {
        return [
            'key' => StatusFilterKey::FINALLY_ACCEPTED(),
            'name' => 'المقالات المقبولة للنشر نهائيا',
        ];
    }

    public function REJECTED()
    {
        return [
            'key' => StatusFilterKey::REJECTED(),
            'name' => 'المقالات المرفوضة',
        ];
    }

    public function SENT_FOR_PAYMENT()
    {
        return [
            'key' => StatusFilterKey::SENT_FOR_PAYMENT(),
            'name' => 'مقالات مرسلة للمؤلف للسداد',
        ];
    }

}
