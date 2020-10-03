<?php

namespace Modules\Users\Services\Frontend;

use Illuminate\Support\Facades\Auth;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Catalogue\Repositories\CMS\ProductRepository;
use Modules\Users\Repositories\FavoriteRepository;
use Modules\Users\Transformers\FavoriteResource;

class FavoriteService extends LaravelServiceClass
{
    private $favorite_repo;
    private $product_repo;

    public function __construct(
        FavoriteRepository $user,
        ProductRepository $product_repo
    )
    {
        $this->favorite_repo = $user;
        $this->product_repo = $product_repo;
    }

    public function all()
    {
        $conditions = ['user_id' => Auth::id()];
        if ((!request()->has('is_pagination')) || request('is_pagination')) {
            list($favorites, $pagination) = parent::paginate($this->favorite_repo, null, false, $conditions);
        } else {
            $favorites = $this->favorite_repo->all($conditions);
            $pagination = null;
        }

        $favorites->load([
            'product' =>  function ($query) {
                $query->where(function ($qu) {
                    $qu->whereHas('mainCategory', function ($q) {
                        $q->where('is_active', true);
                    })->whereHas('brand', function ($q) {
                        $q->where('is_active', true);
                    });
                })->where('is_active', true);
            },
            'product.currentLanguage',
            'product.warehouses',
            'product.priceLists',
            'product.price',
            'product.brand.currentLanguage',
            'product.mainCategory.currentLanguage',
            'product.unitOfMeasure.currentLanguage',
        ]);

        $favorites_resource = collect([]);
        foreach ($favorites as $favorite){
            if ($favorite->product){
                $favorites_resource->push($favorite);
            }
        }

        $favorites = FavoriteResource::collection($favorites_resource);
        return ApiResponse::format(200, $favorites, null, $pagination);
    }

    public function store()
    {
        $product = $this->product_repo->get(request('product_id'));

        $product->load('languages');

        $name = ($product->languages) ? $product->languages[0]->name : '';

        $favorite = $this->favorite_repo->updateOrCreate([
            'product_id' => request('product_id'),
            'user_id' => Auth::id()
        ], [
            'product_name' => $name,
            'product_code' => $product->sku,
        ]);

        $favorite->load([
            'product.currentLanguage',
            'product.unitOfMeasure.currentLanguage',
            'product.mainCategory',
            'product.brand',
            'product.categories',
            'product.variations',
            'product.variantTo.currentLanguage',
            'product.warehouses',
            'product.price',
            'product.favorites',
        ]);
        $favorite = FavoriteResource::make($favorite);

        return ApiResponse::format(200, $favorite);
    }

    public function delete($id)
    {
        if (request('favorite_id')) { // if favorite is submitted
            $favorite = $this->favorite_repo->get(request('favorite_id'));
        } else { // if product id is submitted
            $favorite = $this->favorite_repo->get(Auth::id(), ['product_id' => request('product_id')], 'user_id');
        }

        if (isset($favorite)) {
            $this->favorite_repo->deleteItem($favorite);
        }

        return ApiResponse::format(200, null, 'Deleted Successfully');
    }
}
