<?php

namespace Modules\Settings\Services\CMS;

use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Settings\Repositories\LanguageRepository;
use Modules\Settings\Transformers\LanguageResource;

class LanguageService extends LaravelServiceClass
{
    private $language_repo;
    private $language_resource;

    public function __construct(LanguageRepository $language_repo, LanguageResource $language_resource)
    {
        $this->language_repo = $language_repo;
        $this->language_resource = $language_resource;
    }

    public function index()
    {
        $data = $this->language_repo->all([]);
        $data = $this->language_resource->toArray($data, 1);

        return response()->json([
            'message' => 'Successful Query',
            'data' => $data
        ], 200);
    }

    public function store()
    {
        $data = $this->language_resource->toArray(request());
        $this->language_repo->create($data);

        return response()->json([
            'message' => 'Created Successfully',
            'data' => $data
        ], 201);
    }

    public function show($id)
    {
        $data = $this->language_repo->get($id);
        $data = $this->language_resource->toArray($data, 0);

        return response()->json([
            'message' => 'Successful Query',
            'data' => $data
        ], 200);
    }

    public function update($id)
    {
        $resource = $this->language_resource->toArray([]);
        $this->language_repo->update($id, $resource);

        return response()->json([
            'message' => 'Updated Successfully',
            'data' => []
        ], 204);
    }

    public function delete($id)
    {
        $this->language_repo->delete($id);
        return response()->json([
            'message' => 'Updated Successfully',
            'data' => []
        ], 204);
    }
}
