<?php


namespace Modules\WareHouse\Services\CMS;

use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\WareHouse\ExcelExports\CountryExport;
use Modules\WareHouse\Facades\CountryErrorsHelper;
use Modules\WareHouse\Repositories\CountryRepository;
use Modules\WareHouse\Transformers\CountryResource;

class CountryService extends LaravelServiceClass
{
    private $country_repository;

    public function __construct(CountryRepository $country_repository)
    {
        $this->country_repository = $country_repository;
    }

    public function index()
    {
        if (request('is_pagination')) {
            list($countries, $pagination) = parent::paginate($this->country_repository, getLang());
        } else {
            $countries = parent::list($this->country_repository);

            $pagination = null;
        }

        $countries->load('currentLanguage');


        return ApiResponse::format(200, CountryResource::collection($countries), 'countries data retrieved successfully', $pagination);
    }

    // store new country accept only request
    public function store($request = null)
    {
        /* Upload Image */
        $image = uploadImage($request->image, '/flags');
        $request_data = $request->all();
        $request_data['image'] = $image;
        /* Save main country Object */
        $country = $this->country_repository->create($request_data);

        $user = \Auth::user();
        $admin = $user->admin;
        $admin->countries()->attach($country->id);

        /* save multi language for banner object*/
        $this->country_repository->asyncObjectLanguages($request->data, $country);
        return ApiResponse::format(200, CountryResource::make($country), 'country created successfully');
    }

    // update country , take id and requests
    public function update($id, $request = null)
    {

        $this->checkDefaultCountryCode($id, $request);

        /* update main banner Object */
        $country = $this->country_repository->update($id, $request->except('data', 'image'));
        /* check for Image */
        if ($request->has('image')) {
            // upload new image
            $new_image = uploadImage($request->image, '/flags');
            deleteImage($country->image, 'flags');
            // save the new image to object array_column
            $country = $this->country_repository->update($id, ['image'=> $new_image]);
        }
        /* save multi language for banner object*/
        if ($request->has('data')) {
            $this->country_repository->asyncObjectLanguages($request->data, $country);
        }
        return ApiResponse::format(200, CountryResource::make($country), 'country updated successfully');
    }

    // delete country accept country id
    public function delete($id)
    {
        $this->checkDefaultCountryCode($id);

        $this->country_repository->delete($id);
        return ApiResponse::format(200, [], 'country deleted successfully');
    }

    public function show($id)
    {
        $country = $this->country_repository->get($id);
        $country->load([
            'language'
        ]);
        return ApiResponse::format(200, CountryResource::make($country), 'Country data retrieved successfully');
    }

    private function checkDefaultCountryCode($id, $request = null){
        $country = $this->country_repository->get($id);
        $default_country_code = config('base.default_country');

        if ($request){
            if ($request->country_code){
                if (($country->country_code == $default_country_code) && ($country->country_code != $request->country_code)){
                    CountryErrorsHelper::cannotUpdateDefaultCountry();
                }
            }
        }
        else{
            if ($country->country_code == $default_country_code){
                CountryErrorsHelper::cannotDeleteDefaultCountry();
            }
        }

    }

    public function export(){
        $file_path = ExcelExportHelper::export('Countries', \App::make(CountryExport::class));

        return ApiResponse::format(200, $file_path);
    }
}
