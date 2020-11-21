<?php

namespace Modules\PreArticle\Helpers;


class AttachmentTypeCollection
{

    public function COVER_PAGE()
    {
        return [
            'key' => $this->COVER_PAGE_KEY(),
            'name' => 'صفحة الغلاف',
        ];
    }
    public function COVER_PAGE_KEY(){
        return 'COVER_PAGE';
    }

    public function ORIGINAL_ARTICLE()
    {
        return [
            'key' => $this->ORIGINAL_ARTICLE_KEY(),
            'name' => 'المقال الأصلي بدون إسم المؤلفين',
        ];
    }
    public function ORIGINAL_ARTICLE_KEY(){
        return 'ORIGINAL_ARTICLE';
    }

    public function SUMMARY_OF_RESEARCH()
    {
        return [
            'key' => $this->SUMMARY_OF_RESEARCH_KEY(),
            'name' => 'مستخلص البحث',
        ];
    }
    public function SUMMARY_OF_RESEARCH_KEY(){
        return 'SUMMARY_OF_RESEARCH';
    }

}
