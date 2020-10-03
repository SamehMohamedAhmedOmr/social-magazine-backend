<?php

namespace Modules\Settings\Http\Controllers\Frontend;

use Illuminate\Routing\Controller;
use Modules\Settings\Http\Requests\Frontend\PageRequest;
use Modules\Settings\Services\Frontend\PagesService;

class PagesController extends Controller
{
    private $pages_service;

    public function __construct(PagesService $pages_service)
    {
        $this->pages_service = $pages_service;
    }

    public function show(PageRequest $request)
    {
        return $this->pages_service->show($request->page_url);
    }

}
