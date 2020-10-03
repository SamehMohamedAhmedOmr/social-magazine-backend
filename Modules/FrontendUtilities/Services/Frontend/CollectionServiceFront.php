<?php


namespace Modules\FrontendUtilities\Services\Frontend;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\FrontendUtilities\Repositories\CollectionRepository;
use Modules\FrontendUtilities\Transformers\CollectionResource;

class CollectionServiceFront
{
    private $collection_repository;

    public function __construct(CollectionRepository $collection_repository)
    {
        $this->collection_repository = $collection_repository;
    }

    public function index()
    {
        $sort_key =  (request('sort_key') ? request('sort_key') : 'order');
        $sort_order =  (request('sort_order') ? request('sort_order') : 'desc');
        $banners = $this->collection_repository->getCollectionFront(getLang(), $sort_key, $sort_order);
        return ApiResponse::format(200, CollectionResource::collection($banners), 'collection data retrieved successfully');
    }
}
