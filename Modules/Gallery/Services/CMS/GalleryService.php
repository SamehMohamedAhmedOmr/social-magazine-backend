<?php

namespace Modules\Gallery\Services\CMS;

use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Gallery\Facades\GalleryErrorsHelper;
use Modules\Gallery\Facades\GalleryHelper;
use Modules\Gallery\Repositories\GalleryRepository;
use Illuminate\Support\Facades\Storage;
use Modules\Gallery\Repositories\GalleryTypeRepository;
use Modules\Gallery\Transformers\CMS\GalleryResource;

class GalleryService extends LaravelServiceClass
{
    private $gallery_repo;
    private $galleryTypeRepository;

    public function __construct(GalleryRepository $gallery_repo, GalleryTypeRepository $galleryTypeRepository)
    {
        $this->gallery_repo = $gallery_repo;
        $this->galleryTypeRepository = $galleryTypeRepository;
    }

    public function all()
    {
        $search_key = request('search_key');

        $gallery_type = $this->galleryTypeRepository->get($search_key, [], 'key');

        if ($gallery_type) {
            list($gallery, $pagination) = parent::paginate($this->gallery_repo, null, false, [
                'gallery_type_id' => $gallery_type->id
            ]);
            $gallery->load('galleryType');
        } else {
            $gallery = [];
            $pagination = null;
        }

        $gallery = GalleryResource::collection($gallery);
        return ApiResponse::format(200, $gallery, null, $pagination);
    }

    public function uploadImage($image, $gallery_type_key)
    {
        // store Image using Storage Facades
        $gallery_type = $this->galleryTypeRepository->get($gallery_type_key, [], 'key');

        $folder = $gallery_type->folder;

        $folder = GalleryHelper::projectSlug().'/'.$folder;

        $thumbnail = $this->generateThumbnail($folder, $image);

        $path = Storage::putFile('public/images/'. $folder, $image);

        $path =  explode('/', $path);

        $image_name = $path[count($path)-1];

        $gallery = $this->gallery_repo->create([
            'image' => $image_name,
            'thumbnail' => $thumbnail,
            'gallery_type_id' => $gallery_type->id
        ]);


        $gallery = GalleryResource::make($gallery);
        return ApiResponse::format(200, $gallery, 'Image added successfully to gallery');
    }

    public function generateThumbnail($folder, $image_file)
    {
        $destinationPath = 'images/'.$folder.'/thumbnail';

        $thumbnail_image_name = time().'.'.strtolower(
            pathinfo(
                    $image_file->getClientOriginalName(),
                    PATHINFO_FILENAME
                ).'.'.$image_file->getClientOriginalExtension()
        );

        $thumbnail_image_name = str_replace(' ', '_', $thumbnail_image_name);

        $image = Image::make($image_file->path());

        $image->resize(200, null, function ($constraint) {
            $constraint->aspectRatio();
        })->stream();

        Storage::disk('public')->put($destinationPath.'/'.$thumbnail_image_name, $image);

        return $thumbnail_image_name;
    }


    public function delete($id)
    {
        try {
            $gallery = $this->gallery_repo->get($id);

            $gallery->load('galleryType');

            $this->deleteImage($gallery->image, $gallery->galleryType->folder);
            $this->deleteThumbnail($gallery->thumbnail, $gallery->galleryType->folder);

            $gallery = $this->gallery_repo->delete($id);

            return ApiResponse::format(200, $gallery, 'Image Deleted Successfully!');
        } catch (\Exception $exception) {
            GalleryErrorsHelper::cannotDeleteImage();
        }
    }

    public function deleteImage($image, $folder)
    {
        $old_image_path =  explode('/', $image);
        $old_image_name =  $old_image_path[count($old_image_path)-1];
        return Storage::delete('public/images/'. $folder .'/' .$old_image_name);
    }

    public function deleteThumbnail($image, $folder)
    {
        $old_image_path =  explode('/', $image);
        $old_image_name =  $old_image_path[count($old_image_path)-1];
        return Storage::delete('public/images/'. $folder .'/thumbnail/' .$old_image_name);
    }
}
