<?php

namespace Modules\Settings\Services\CMS;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Settings\Repositories\PriceListTypeRepository;
use Modules\Settings\Transformers\PriceListTypeResource;

class PriceListTypeService extends LaravelServiceClass
{
    private $price_list_type_repo;


    public function __construct(PriceListTypeRepository $price_list_type_repo)
    {
        $this->price_list_type_repo = $price_list_type_repo;
    }

    public function index()
    {
        if (request('is_pagination')) {
            list($price_list_types, $pagination) = parent::paginate($this->price_list_type_repo, null, false);
        } else {
            $price_list_types = $this->price_list_type_repo->all();
            $pagination = null;
        }

        $price_list_types = PriceListTypeResource::collection($price_list_types);
        return ApiResponse::format(200, $price_list_types, [], $pagination);
    }

    public function store()
    {
        $price_list_type = $this->price_list_type_repo->create([
            'name' => request('name'),
        ]);

        $price_list_type = PriceListTypeResource::make($price_list_type);

        return ApiResponse::format(200, $price_list_type, 'Price list Type added successfully');
    }

    public function show($id)
    {
        $price_list_type = $this->price_list_type_repo->get($id);

        $price_list_type = PriceListTypeResource::make($price_list_type);

        return ApiResponse::format(200, $price_list_type);
    }

    public function update($request)
    {
        $validated_data = collect($request->validated());

        $validated_data = $validated_data->except(['price_list_type']);

        $price_list_type = $this->price_list_type_repo->update(request('price_list_type'), $validated_data->toArray());

        $price_list_type = PriceListTypeResource::make($price_list_type);

        return ApiResponse::format(200, $price_list_type, 'Price list Type updated successfully');
    }

    public function delete($id)
    {
        $price_list_type = $this->price_list_type_repo->delete($id);

        return ApiResponse::format(200, $price_list_type);
    }
}
