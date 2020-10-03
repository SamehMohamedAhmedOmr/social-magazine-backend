<?php

namespace Modules\Settings\Services\CMS;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Settings\Repositories\CurrencyRepository;
use Modules\Settings\Transformers\CurrencyResource;

class CurrencyService extends LaravelServiceClass
{
    private $currency_repo;


    public function __construct(CurrencyRepository $currency_repo)
    {
        $this->currency_repo = $currency_repo;
    }

    public function index()
    {
        if (request('is_pagination')) {
            list($currencies, $pagination) = parent::paginate($this->currency_repo, null, false);
        } else {
            $currencies = $this->currency_repo->all();
            $pagination = null;
        }

        $currencies = CurrencyResource::collection($currencies);
        return ApiResponse::format(200, $currencies, [], $pagination);
    }
}
