<?php

namespace Modules\PreArticle\Helpers;


class ArticleSubjectCollection
{

    public function ARCHAEOLOGY()
    {
        return [
            'key' => $this->ARCHAEOLOGY_KEY(),
            'name' => 'الأثار',
        ];
    }
    public function ARCHAEOLOGY_KEY(){
        return 'ARCHAEOLOGY';
    }

    public function SOCIOLOGY()
    {
        return [
            'key' => $this->SOCIOLOGY_KEY(),
            'name' => 'الاجتماع',
        ];
    }
    public function SOCIOLOGY_KEY(){
        return 'SOCIOLOGY';
    }

    public function LITERATURE()
    {
        return [
            'key' => $this->LITERATURE_KEY(),
            'name' => 'الأداب',
        ];
    }
    public function LITERATURE_KEY(){
        return 'LITERATURE';
    }

    public function TOURIST_GUIDES()
    {
        return [
            'key' => $this->TOURIST_GUIDES_KEY(),
            'name' => 'الارشاد السياحي',
        ];
    }
    public function TOURIST_GUIDES_KEY(){
        return 'TOURIST_GUIDES';
    }

    public function MEDIA()
    {
        return [
            'key' => $this->MEDIA_KEY(),
            'name' => 'الاعلام',
        ];
    }
    public function MEDIA_KEY(){
        return 'MEDIA';
    }

    public function HISTORY()
    {
        return [
            'key' => $this->HISTORY_KEY(),
            'name' => 'التاريخ',
        ];
    }
    public function HISTORY_KEY(){
        return 'HISTORY';
    }

    public function GEOGRAPHY()
    {
        return [
            'key' => $this->GEOGRAPHY_KEY(),
            'name' => 'الجغرافيا',
        ];
    }
    public function GEOGRAPHY_KEY(){
        return 'GEOGRAPHY';
    }

    public function PHILOSOPHICAL_STUDIES()
    {
        return [
            'key' => $this->PHILOSOPHICAL_STUDIES_KEY(),
            'name' => 'الدراسات الفلسفية',
        ];
    }
    public function PHILOSOPHICAL_STUDIES_KEY(){
        return 'PHILOSOPHICAL_STUDIES';
    }

    public function ARTS()
    {
        return [
            'key' => $this->ARTS_KEY(),
            'name' => 'الفنون',
        ];
    }
    public function ARTS_KEY(){
        return 'ARTS';
    }

    public function LANGUAGES()
    {
        return [
            'key' => $this->LANGUAGES_KEY(),
            'name' => 'اللغات',
        ];
    }
    public function LANGUAGES_KEY(){
        return 'LANGUAGES';
    }

    public function INFORMATION()
    {
        return [
            'key' => $this->INFORMATION_KEY(),
            'name' => 'المعلومات',
        ];
    }
    public function INFORMATION_KEY(){
        return 'INFORMATION';
    }

    public function LIBRARIES()
    {
        return [
            'key' => $this->LIBRARIES_KEY(),
            'name' => 'المكتبات',
        ];
    }
    public function LIBRARIES_KEY(){
        return 'LIBRARIES';
    }

    public function PSYCHOLOGY()
    {
        return [
            'key' => $this->PSYCHOLOGY_KEY(),
            'name' => 'علم النفس',
        ];
    }
    public function PSYCHOLOGY_KEY(){
        return 'PSYCHOLOGY';
    }


}
