<?php

namespace Modules\Settings\Http\Controllers\CMS;

use Illuminate\Routing\Controller;
use Modules\Settings\Http\Requests\LanguageRequest;
use Modules\Settings\Services\CMS\LanguageService;

class LanguagesController extends Controller
{
    private $language_service;

    public function __construct(LanguageService $language_service, LanguageRequest $language_validation)
    {
        $this->language_service = $language_service;
    }

    public function index()
    {
        return $this->language_service->index();
    }

    public function store()
    {
        return $this->language_service->store();
    }

    public function show()
    {
        return $this->language_service->show(request()->language);
    }

    public function update()
    {
        return $this->language_service->update(request()->language);
    }

    public function destroy()
    {
        return $this->language_service->delete(request()->language);
    }
}
