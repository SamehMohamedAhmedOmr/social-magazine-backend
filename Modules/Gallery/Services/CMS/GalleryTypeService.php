<?php

namespace Modules\Gallery\Services\CMS;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Gallery\Repositories\GalleryRepository;
use Modules\ACL\Transformers\PermissionResource;
use Modules\Gallery\Repositories\GalleryTypeRepository;

class GalleryTypeService extends LaravelServiceClass
{
    private $gallery_type_repo;

    public function __construct(GalleryTypeRepository $gallery_type_repo)
    {
        $this->gallery_type_repo = $gallery_type_repo;
    }

    public function all()
    {
        if (request('is_pagination')) {
            list($gallery_type, $pagination) = parent::paginate($this->gallery_type_repo, null, false);
        } else {
            $gallery_type = $this->gallery_type_repo->all();
            $pagination = null;
        }

        $gallery_type = PermissionResource::collection($gallery_type);
        return ApiResponse::format(200, $gallery_type, [], $pagination);
    }
}
