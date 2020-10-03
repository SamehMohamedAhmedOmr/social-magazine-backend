<?php

namespace Modules\WareHouse\ExcelExports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Base\Facade\ExcelExportHelper;
use Modules\WareHouse\Services\CMS\Exports\OrderExportService;
use Modules\WareHouse\Transformers\CMS\Order\OrderResource;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrderExport implements FromArray , WithHeadings, ShouldAutoSize, WithStyles, WithEvents
{

    private $exportService;

    private $data_length = 0;

    public function __construct(OrderExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    /**
     * @inheritDoc
     */
    public function array(): array
    {
        $orders = $this->exportService->getBulkForExport();

        $orders->load([
            'orderItems.toppings.currentLanguage',
            'orderItems.product.currentLanguage',
            'orderItems.product.favorites',
        ]);

        foreach ($orders as $order) {
            $items = [];
            foreach ($order->orderItems as $item) {
                if (isset($item->product)) {
                    $items [] = $item;
                }
            }
            $order->orderItems = $items;
        }

        $orders->load([
            'address.district.language',
            'paymentMethod.currentLanguage',
            'timeSection',
            'shipment',
            'user.client',

            'loyality',
        ]);

        $orders = OrderResource::collection($orders);

        $orders = collect($orders)->toArray();
        $orders = $this->exportService->prepareData($orders);

        $this->data_length = count($orders);
        return $orders;
    }

    /**
     * @inheritDoc
     */
    public function headings(): array
    {
        return [
            "Order Date",
            "Order ID",
            "Customer Name",
            "Mobile",
            "Area",
            "Address",
            "Items",
            "Payment Method",
            "Sub total",
            "Shipping Price",
            "Discount",
            "Order Total",
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return ExcelExportHelper::styles();
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event)
            {
                $length = $this->data_length + 1;
                $cellRange = 'A1:N'.$length;

                ExcelExportHelper::registerEvents($event, $cellRange);
            },
        ];
    }

}
