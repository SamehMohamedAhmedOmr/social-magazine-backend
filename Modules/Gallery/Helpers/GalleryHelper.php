<?php

namespace Modules\Gallery\Helpers;


class GalleryHelper
{
    // get Full Image Path
    public function getImagePath($folder_name, $image)
    {
        $path = 'public/images/'. $folder_name .'/'. $image ;
        return url(\Storage::url($path));
    }

    // get Full Image Path
    public function getThumbnailPath($folder_name, $image)
    {
        $path = 'public/images/'. $folder_name .'/thumbnail/'. $image ;
        return url(\Storage::url($path));
    }

    public function projectSlug(){
        $project_folder = \Session::get(\Session::get('current_sub_domain'));
        return isset($project_folder) ? $project_folder['slug'] : '8000';
    }
}
