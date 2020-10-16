<?php

namespace Modules\Notifications\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RetailkBaseMail extends Mailable
{
    use Queueable, SerializesModels;

    private $view_name;
    private $render_data;
    private $subject_name;
    private $attachment;
    private $filename;

    /**
     * Create a new message instance.
     *
     * @param $module_name
     * @param $view_name
     * @param $subject
     * @param $render_data
     * @param null $attachment
     * @param null $filename
     */
    public function __construct($module_name, $view_name, $subject, $render_data, $attachment = null, $filename = null)
    {
        $this->view_name = strtolower($module_name).'::'.$view_name;
        $this->render_data = $render_data;
        $this->subject_name = $subject;

        $this->attachment = $attachment;
        $this->filename = $filename;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if (isset($this->attachment) && isset($this->filename)) {
            $mail = $this->from(env('MAIL_USERNAME'), config('app.application_name'))
                ->subject($this->subject_name)
                ->view($this->view_name)
                ->with(['render_data' => $this->render_data])
                ->attach($this->attachment, ['as' => $this->filename]);
        } else {
            $mail = $this->from(env('MAIL_USERNAME'), config('app.application_name'))
                ->subject($this->subject_name)
                ->view($this->view_name)
                ->with(['render_data' => $this->render_data]);
        }

        return $mail;
    }
}
