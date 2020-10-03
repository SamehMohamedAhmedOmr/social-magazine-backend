<?php

namespace Modules\WareHouse\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Auth;

class PaymentEntryLogs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $payment_entry_log_repo;
    private $payment_entry_data;
    private $payment_entry_id;
    private $log_type;

    /**
     * Create a new job instance.
     *
     * @param $payment_entry_log_repo
     * @param $payment_entry_data
     * @param $payment_entry_id
     * @param $log_type
     */
    public function __construct($payment_entry_log_repo, $payment_entry_data, $payment_entry_id, $log_type)
    {
        $this->payment_entry_log_repo = $payment_entry_log_repo;
        $this->payment_entry_data = $payment_entry_data;
        $this->payment_entry_id = $payment_entry_id;
        $this->log_type = $log_type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->payment_entry_log_repo->create([
            'purchase_payment_entry' =>  collect($this->payment_entry_data),
            'log_type' => $this->log_type,
            'user_id' => Auth::id(),
            'payment_entry_id' => $this->payment_entry_id
        ]);
    }
}
