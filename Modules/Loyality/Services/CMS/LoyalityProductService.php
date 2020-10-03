<?php

namespace Modules\Loyality\Services\CMS;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Loyality\Repositories\CMS\LoyalityProductsRepository;
use Modules\Loyality\Transformers\CMS\LoyalityProductResource;

class LoyalityProductService extends LaravelServiceClass
{
    private $loyality_program_repo;

    public function __construct(LoyalityProductsRepository $loyality_program_repo)
    {
        $this->loyality_program_repo = $loyality_program_repo;
    }

    public function all()
    {
        $response = $this->loyality_program_repo->all();
        return LoyalityProductResource::collection($response['data']);
    }

    public function pagination()
    {
        $per_page = request('per_page') ?: 15;
        $response = $this->loyality_program_repo->pagination($per_page);
        return ApiResponse::format(200,
            LoyalityProductResource::collection($response['data']),
            'Successfully',
            $response['pagination']);
    }

    public function store()
    {
        $data = $this->loyality_program_repo->create(request()->all());
        return ApiResponse::format(201, LoyalityProductResource::make($data), 'Created');
    }

    public function show($product_id)
    {
        $data = $this->loyality_program_repo->get($product_id, [], 'product_id');
        return ApiResponse::format(200, LoyalityProductResource::make($data), 'Exists');
    }

    public function update($product_id)
    {
        $data = $this->loyality_program_repo->update($product_id, request()->all(), [], 'product_id');
        return ApiResponse::format(200, LoyalityProductResource::make($data), 'Updated');
    }

    public function delete($product_id)
    {
        $this->loyality_program_repo->delete($product_id, [], 'product_id');
        return ApiResponse::format(204, [], 'Deleted');
    }
}
