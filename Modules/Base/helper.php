<?php

// get Language for any Api Request
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Storage;
use \Modules\Settings\Repositories\LanguageRepository;
use \Modules\Settings\Entities\Language;

/**
 * @return array iso for the active languages
 */
function getActiveISO()
{
    return (new LanguageRepository(new Language()))->getActiveISO();
}

// get Lang ID
function getLang()
{
    $lang_iso = app('request')->header('Accept-Language');
    $lang_repo =  new LanguageRepository(new Language());
    return  $lang_repo->getLangId($lang_iso);
}

// get all active languages
function getAllActiveLanguages()
{
    $lang_repo =  new LanguageRepository(new Language());
    return $lang_repo->getLang();
}

// upload file take two arguments (Request File , the name of the folder to be store in)
function uploadImage($image, $folder)
{
    // store Image using Storage Facades
    $path = Storage::putFile('public/images'. $folder, $image);
    /*
        $path  = "xx/yy/zz/img.png" ,  code below return only the image name
        by split the path and get last index
    */
    $path =  explode('/', $path);
    return $path[count($path)-1];
}

function uploadFiles($file, $folder)
{
    $path = Storage::putFile('public/files'. $folder, $file);

    $path =  explode('/', $path);
    return $path[count($path)-1];
}

// delete Image permanently , for update object only
function deleteImage($image, $folder)
{
    $old_image_path =  explode('/', $image);
    $old_image_name =  $old_image_path[count($old_image_path)-1];
    return Storage::delete('public/images/'. $folder .'/' .$old_image_name);
}

// get Full Image Path
function getImagePath($folder_name, $image)
{
    $path = 'public/images/'. $folder_name .'/'. $image ;
    return url(Storage::url($path));
}

function getFilePath($folder_name, $file)
{
    $path = 'public/'. $folder_name .'/'. $file ;
    return url(Storage::url($path));
}

// prepare before validation
function prepareBeforeValidation($request, $data = ['data'], $key = null)
{
    $input = $request->all();
    $request_methods = collect([
        'GET','DELETE','PUT','PATCH'
    ]);
    // set request id in the request body
    if ($request_methods->contains($request->getMethod()) && $key) {
        $input[$key] = request($key);
    }
    // check  for json fields to transfer it's format to array format
    foreach ($data as $json_data) {
        if (isset($input[$json_data]) && is_string($input[$json_data])) {
            $input[$json_data] = json_decode($input[$json_data], true);
        }
    }
    $request->replace($input);
}


// prepare object languages and add languages_id to use it in store or update
function prepareObjectLanguages($data)
{
    // get all active languages data
    $languages = getAllActiveLanguages();
    $objects = [];
    foreach ($data as $key => $value) {
        $attributes = $value;
        $filtered = $languages->where('iso', $value['lang'])->first();
        $attributes['language_id'] = $filtered->id;
        $objects[] = $attributes;
    }
    return $objects;
}

function generatePDF($view_name, $attached_data, $pdf_name, $to_email = false)
{
    $pdf_file_name = $pdf_name.'.pdf';
    $pdf = PDF::loadView($view_name, $attached_data);
    if ($to_email) {
        $content = $pdf->download()->getOriginalContent();

        Storage::put('pdf/'.$pdf_file_name, $content);
        return storage_path('app\\pdf\\'.$pdf_file_name);
    }
    return $pdf->download($pdf_file_name);
}

function preparePaginationKeys($has_active_key = true)
{
    $pagination_number = (request('per_page') ? request('per_page') : 15);
    $sort_key =  (request('sort_key') ? request('sort_key') : 'id');
    $sort_order =  (request('sort_order') ? request('sort_order') : 'asc');
    $search_key = request('search_key');

    if ($has_active_key) {
        $active =  request()->has('is_active') ? request('is_active') : null;
        (isset($active)) ? $conditions['is_active']  = $active : $conditions = [];
    } else {
        $conditions = [];
    }
    return [$pagination_number,$sort_key,$sort_order,$conditions,$search_key];
}
