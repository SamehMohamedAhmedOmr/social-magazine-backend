<?php

namespace Modules\PreArticle\Helpers;


class PaymentMethodCollection
{

    public function MANUAL_PAYMENT()
    {
        return [
            'key' => $this->MANUAL_PAYMENT_KEY(),
            'name' => 'سداد يدوي',
        ];
    }
    public function MANUAL_PAYMENT_KEY(){
        return 'MANUAL_PAYMENT';
    }

}
