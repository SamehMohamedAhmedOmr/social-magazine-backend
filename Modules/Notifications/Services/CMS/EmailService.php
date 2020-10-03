<?php

namespace Modules\Notifications\Services\CMS;

use Illuminate\Support\Facades\Mail;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Notifications\Emails\RetailkBaseMail;

class EmailService extends LaravelServiceClass
{
    public function email($to, $module_name, $view_name, $subject, $render_data, $attachment = null, $filename = null)
    {
        $mailable = new RetailkBaseMail($module_name, $view_name, $subject, $render_data, $attachment, $filename);
        Mail::to($to)->send($mailable);
        return ApiResponse::format(200, 'Your E-mail has been sent successfully.');
    }
}
