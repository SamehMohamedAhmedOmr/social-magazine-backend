<?php

namespace Modules\Settings\Services\CMS;

use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Settings\ExcelExports\ShippingRuleExport;
use Modules\Settings\Repositories\ShippingRuleRepository;
use Modules\Settings\Transformers\ShippingRulesResource;

class ShippingRuleService extends LaravelServiceClass
{
    private $shipping_rule_repo;

    public function __construct(ShippingRuleRepository $shipping_rule_repo)
    {
        $this->shipping_rule_repo = $shipping_rule_repo;
    }

    public function index()
    {
        if (request('is_pagination')) {
            list($shipping_rules, $pagination) = parent::paginate($this->shipping_rule_repo, null);
        } else {
            $shipping_rules = parent::list($this->shipping_rule_repo);

            $pagination = null;
        }

        $shipping_rules = ShippingRulesResource::collection($shipping_rules);
        return ApiResponse::format(200, $shipping_rules, [], $pagination);
    }

    public function store()
    {
        $shipping_rule = $this->shipping_rule_repo->create(request()->all());

        $shipping_rule = ShippingRulesResource::make($shipping_rule);

        return ApiResponse::format(200, $shipping_rule, 'Shipping Rule added successfully');
    }

    public function show($id)
    {
        $shipping_rule = $this->shipping_rule_repo->get($id);

        $shipping_rule = ShippingRulesResource::make($shipping_rule);

        return ApiResponse::format(200, $shipping_rule);
    }

    public function update($id)
    {
        $shipping_rule = $this->shipping_rule_repo->update($id, request()->all());

        $shipping_rule = ShippingRulesResource::make($shipping_rule);

        return ApiResponse::format(200, $shipping_rule, 'Shipping Rule updated successfully');
    }

    public function delete($id)
    {
        $shipping_rule = $this->shipping_rule_repo->delete($id);

        return ApiResponse::format(200, $shipping_rule, 'Shipping Rule deleted successfully');
    }

    public function export(){
        $file_path = ExcelExportHelper::export('Shipping-Rules', \App::make(ShippingRuleExport::class));

        return ApiResponse::format(200, $file_path);
    }
}
