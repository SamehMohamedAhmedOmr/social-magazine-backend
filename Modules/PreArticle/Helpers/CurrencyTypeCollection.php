<?php

namespace Modules\PreArticle\Helpers;


class CurrencyTypeCollection
{

    public function DOLLAR()
    {
        return [
            'key' => $this->DOLLAR_KEY(),
            'name' => 'الدولار',
        ];
    }
    public function DOLLAR_KEY(){
        return 'DOLLAR';
    }

    public function EURO()
    {
        return [
            'key' => $this->EURO_KEY(),
            'name' => 'اليورو',
        ];
    }
    public function EURO_KEY(){
        return 'EURO';
    }

    public function EGYPTIAN_POUND()
    {
        return [
            'key' => $this->DOLLAR_KEY(),
            'name' => 'الجنيه',
        ];
    }
    public function EGYPTIAN_POUND_KEY(){
        return 'EGYPTIAN_POUND';
    }


}
