<?php


namespace Modules\WareHouse\Services\CMS\Order;

use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\WareHouse\ExcelExports\OrderNoteExport;
use Modules\WareHouse\Repositories\OrderNotesRepository;
use Modules\WareHouse\Transformers\OrderNotesResource;

class OrderNotesService extends LaravelServiceClass
{
    private $order_notes_repository;

    public function __construct(OrderNotesRepository $order_notes_repository)
    {
        $this->order_notes_repository = $order_notes_repository;
    }

    public function index($request = null)
    {
        if (request('is_pagination')) {
            list($order_notes, $pagination) = parent::paginate($this->order_notes_repository, null,
                false,[
                    'order_id' => ($request) ? $request->order_id : request('order_id')
                ]);
        } else {
            $order_notes = parent::list($this->order_notes_repository, false,[
                'order_id' => ($request) ? $request->order_id : request('order_id')
            ]);
            $pagination = null;
        }

        return ApiResponse::format(200, OrderNotesResource::collection($order_notes), null, $pagination);
    }

    // store new country accept only request
    public function store($request = null)
    {
        $order_note = $this->order_notes_repository->create($request->all());

        return ApiResponse::format(200, OrderNotesResource::make($order_note), 'Order Note created successfully');
    }

    public function show($id)
    {
        $order_note = $this->order_notes_repository->get($id);
        return ApiResponse::format(200, OrderNotesResource::make($order_note));
    }

    // update country , take id and requests
    public function update($id, $request = null)
    {
        $order_note = $this->order_notes_repository->update($id, $request->all());

        return ApiResponse::format(200, OrderNotesResource::make($order_note), 'Order Note updated successfully');
    }

    // delete country accept country id
    public function delete($id)
    {
        $this->order_notes_repository->delete($id);
        return ApiResponse::format(200, [], 'Order Note deleted successfully');
    }

    public function export(){
        $file_path = ExcelExportHelper::export('Order-Notes', \App::make(OrderNoteExport::class));

        return ApiResponse::format(200, $file_path);
    }

}
