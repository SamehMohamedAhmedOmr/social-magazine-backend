<?php

namespace Modules\Base\Helpers;

use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Base\ResponseShape\ExportResource;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Modules\Base\Facade\UtilitiesHelper;
class ExcelExportHelper
{

    public function styles()
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents($event, $cellRange)
    {
        $style_array = [
            'font' => ['size' => 14]
        ];

        $event->sheet->getStyle($cellRange)->ApplyFromArray($style_array);
        $event->sheet->getStyle($cellRange)->getAlignment()->setWrapText(true);
        $event->sheet->getStyle($cellRange)->getAlignment()->applyFromArray(
            array('horizontal' => 'center')
        );

        $event->sheet->getDelegate()->getStyle($cellRange)
            ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    }

    public function export($directory ,  $export_class){
        try {
            $date = Carbon::now();
            $project_slug = UtilitiesHelper::projectSlug();
            $pre_path = 'public/Excel/'.$project_slug.'/'.$directory .'/';
            //$path = $directory . 'Reports-'.$date->toDateString().'-'.$date->hour.'h-'.$date->minute.'m';
            $path = $directory . '-Reports';
            $extension = '.xlsx';
            $full_path = $pre_path . $path . $extension;

            Excel::store($export_class, $full_path);

            $file_path = getFilePath('Excel/'.$project_slug.'/'.$directory .'/', $path . $extension);

            return ExportResource::make(['path' => $file_path]);
        }
        catch (\PhpOffice\PhpSpreadsheet\Exception $e){

        }
    }

    public function prepareDataForExport($service, $exportService, $index_method = true){

        $data = ($index_method) ? $service->index() : $service->all();

        $data = \Response::json($data)->getOriginalContent();

        $data = collect($data['body'])->toArray();

        return $exportService->prepareData($data);
    }

    public function prepareSingleAttachment($object,  $key){
        $object = collect($object)->toArray();
        return isset($object[$key]) ? $object[$key] : null;
    }

    public function prepareAttachments($objects, $key){
        $all = [];
        $objects = collect($objects)->toArray();
        foreach ($objects as $object){
            $name = isset($object[$key]) ? $object[$key] : null;

            if (isset($name)){
                $all [] = $name;
            }
        }
        return collect($all)->implode("\n");
    }

}
