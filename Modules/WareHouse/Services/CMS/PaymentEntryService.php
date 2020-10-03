<?php

namespace Modules\WareHouse\Services\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\WareHouse\Jobs\PaymentEntryLogs;
use Modules\WareHouse\Repositories\PaymentEntryLogsRepository;
use Modules\WareHouse\Repositories\PaymentEntryRepository;
use Modules\WareHouse\Repositories\PaymentEntryTypeRepository;
use Modules\WareHouse\Repositories\PurchaseInvoicesRepository;
use Modules\WareHouse\Transformers\PaymentEntryResource;

class PaymentEntryService extends LaravelServiceClass
{
    private $payment_entry_repo;
    private $payment_entry_type_repo;
    private $payment_entry_log_repo;
    private $purchase_invoice_repo;

    public function __construct(
        PaymentEntryRepository $payment_entry_repo,
        PurchaseInvoicesRepository $purchase_invoice_repo,
        PaymentEntryTypeRepository $payment_entry_type_repo,
        PaymentEntryLogsRepository $payment_entry_log_repo
    )
    {
        $this->payment_entry_repo = $payment_entry_repo;
        $this->payment_entry_type_repo = $payment_entry_type_repo;
        $this->payment_entry_log_repo = $payment_entry_log_repo;

        $this->purchase_invoice_repo = $purchase_invoice_repo;
    }

    public function index()
    {
        if (request('is_pagination')) {
            list($payment_entries, $pagination) = parent::paginate($this->payment_entry_repo, null, false);
        } else {
            $payment_entries = $this->payment_entry_repo->all([], $this->payment_entry_repo->relationShips());
            $pagination = null;
        }

        $payment_entries = PaymentEntryResource::collection($payment_entries);
        return ApiResponse::format(200, $payment_entries, [], $pagination);
    }

    /**
     * store Purchase Receipt
     *
     * @return JsonResponse
     */
    public function store()
    {
        $payment_entry_type = $this->payment_entry_type_repo->get(request('payment_entry_type'), [], 'key');

        // store Purchase payment entry main data
        $payment_entry_data = request()->all();
        $payment_entry_data['payment_entry_type_id'] = $payment_entry_type->id;
        $payment_entry = $this->payment_entry_repo->create($payment_entry_data, $this->payment_entry_repo->relationShips());

        PaymentEntryLogs::dispatch($this->payment_entry_log_repo, $payment_entry_data, $payment_entry->id, 'ADDED');

        $payment_entry = PaymentEntryResource::make($payment_entry);
        return ApiResponse::format(200, $payment_entry, 'Payment Entry Added Successfully');
    }

    public function show($id)
    {
        $payment_entry = $this->payment_entry_repo->get($id, [], 'id', $this->payment_entry_repo->relationShips());
        $payment_entry = PaymentEntryResource::make($payment_entry);
        return ApiResponse::format(200, $payment_entry);
    }

    /**
     * update Purchase Receipt
     *
     * @param $id
     * @return JsonResponse
     */
    public function update($id)
    {
        $payment_entry_data = request()->all();

        if (request()->has('payment_entry_type_id')) {
            $payment_entry_type = $this->payment_entry_type_repo->get(request('payment_entry_type'), [], 'key');
            $payment_entry_data['payment_entry_type_id'] = $payment_entry_type->id;
        }

        // store purchase receipt main data
        $payment_entry = $this->payment_entry_repo->update($id, request()->all(), [], 'id', $this->payment_entry_repo->relationShips());

        PaymentEntryLogs::dispatch($this->payment_entry_log_repo, $payment_entry_data, $payment_entry->id, 'UPDATED');

        $payment_entry = PaymentEntryResource::make($payment_entry);
        return ApiResponse::format(200, $payment_entry, 'Payment Entry updated Successfully');
    }

    /**
     * Delete Purchase Receipt
     *
     * @param $id
     * @return JsonResponse
     */
    public function delete($id)
    {
        $payment_entry = $this->payment_entry_repo->get($id);

        PaymentEntryLogs::dispatch($this->payment_entry_log_repo, $payment_entry, $payment_entry->id, 'DELETED');

        $payment_entry = $this->payment_entry_repo->delete($id);
        return ApiResponse::format(200, $payment_entry, 'Payment Entry Deleted Successfully');
    }

    public function getWithPDF($id)
    {
        $payment_entry = $this->payment_entry_repo->get($id, [], 'id', $this->payment_entry_repo->relationShips());

        $purchase_entry_name = '['.config("app.name").'] Purchase Entry PO-'.$id;

        $pdf = generatePDF('warehouse::pdf.purchasePaymentEntry', [
            'payment_entry' => $payment_entry
        ], $purchase_entry_name);

        $data = chunk_split(base64_encode(($pdf)));
        return ApiResponse::format(200, ['pdf' => $data], 'PDF Generated');
    }
}
