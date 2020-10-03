<?php


namespace Modules\FrontendUtilities\Services\CMS;

use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\FrontendUtilities\ExcelExports\CollectionExport;
use Modules\FrontendUtilities\Repositories\CollectionRepository;
use Modules\FrontendUtilities\Transformers\CollectionResource;

class CollectionService extends LaravelServiceClass
{
    private $collection_repository;

    public function __construct(CollectionRepository $collection_repository)
    {
        $this->collection_repository = $collection_repository;
    }

    public function index()
    {
        if (request('is_pagination')) {
            list($collection, $pagination) = parent::paginate($this->collection_repository, getLang());
        } else {
            $collection = parent::list($this->collection_repository, true);

            $pagination = null;
        }

        $collection->load([
            'products.currentLanguage',
            'currentLanguage'
        ]);

        $collection = CollectionResource::collection($collection);

        return ApiResponse::format(200,$collection , null, $pagination);
    }

    public function store($request = null)
    {
        /* update main Object */
        $collection = $this->collection_repository->create($request->except(['data' , 'products']));

        /* save multi language for collection object*/
        $this->collection_repository->asyncObjectLanguages($collection, $request->data);

        /* save products for collection object*/
        $this->collection_repository->attachProducts($collection, $request->products);

        return ApiResponse::format(200, CollectionResource::make($collection), 'collection created successfully');
    }

    public function update($id, $request = null)
    {
        /* update main Object */
        $collection = $this->collection_repository->update($id, $request->except(['data' , 'products']));
        /* save multi language for collection object*/
        if (isset($request->data)) {
            $this->collection_repository->updateObjectLanguages($collection, $request->data);
        }
        if (isset($request->products)) {
            /* save products for collection object*/
            $this->collection_repository->updateProducts($collection, $request->products);
        }
        return ApiResponse::format(200, CollectionResource::make($collection), 'collection updated successfully');
    }

    public function delete($id)
    {
        $this->collection_repository->delete($id);
        return ApiResponse::format(200, [], 'collection deleted successfully');
    }

    public function show($id)
    {
        $collection = $this->collection_repository->get($id);

        $collection->load([
            'products.languages',
            'language'
        ]);

        return ApiResponse::format(200, CollectionResource::make($collection), 'collection data retrieved successfully');
    }

    public function export(){
        $file_path = ExcelExportHelper::export('Collections', \App::make(CollectionExport::class));

        return ApiResponse::format(200, $file_path);
    }
}
