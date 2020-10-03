<?php

namespace Modules\Settings\Services\CMS;

use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Settings\ExcelExports\NavigationMenuExport;
use Modules\Settings\Repositories\FrontendSettings\FrontendMenuRepository;
use Modules\Settings\Repositories\FrontendSettings\FrontendSettingsRepository;
use Modules\Settings\Transformers\CMS\FrontendSettings\FrontendMenuResource;

class FrontendMenuNavigationService extends LaravelServiceClass
{
    private $frontend_menu_repository,$frontend_settings_repository;

    public function __construct(FrontendMenuRepository $frontend_menu_repository,
                                FrontendSettingsRepository $frontend_settings_repository)
    {
        $this->frontend_menu_repository = $frontend_menu_repository;
        $this->frontend_settings_repository = $frontend_settings_repository;
    }

    public function index()
    {
        $frontend_setting = $this->frontendSetting();
        if (request('is_pagination')) {
            list($menus, $pagination) = parent::paginate($this->frontend_menu_repository, null, false,[
                'frontend_setting_id' => $frontend_setting->id
            ]);
        } else {
            $menus = parent::list($this->frontend_menu_repository, false,[
                'frontend_setting_id' => $frontend_setting->id
            ]);

            $pagination = null;
        }

        $menus->load(['currentLanguage','navigationMenu']);
        $menus = FrontendMenuResource::collection($menus);

        return ApiResponse::format(200, $menus, [], $pagination);
    }

    public function store($request = null)
    {
        $frontend_setting = $this->frontendSetting();
        $data = [
            'key' => $request->key,
            'order' => $request->order,
            'navigation_type_id' => $request->navigation_type_id,
            'frontend_setting_id' => $frontend_setting->id,
        ];

        $menu = $this->frontend_menu_repository->create($data);

        $languages = prepareObjectLanguages($request->data);

        $menu = $this->frontend_menu_repository->syncLanguage($menu, $languages);

        $menu = FrontendMenuResource::make($menu);

        return ApiResponse::format(200, $menu, 'Menu added successfully');
    }

    public function show($id)
    {
        $frontend_setting = $this->frontendSetting();

        $menu = $this->frontend_menu_repository->get($id,[
            'frontend_setting_id' => $frontend_setting->id
        ]);

        $menu->load(['languages','navigationMenu']);

        $menu = FrontendMenuResource::make($menu);

        return ApiResponse::format(200, $menu);
    }

    public function update($id, $request = null)
    {
        $menu = $this->frontend_menu_repository->update($id, $request->all());

        if (isset($request->data)) {
            $languages = prepareObjectLanguages($request->data);

            $menu = $this->frontend_menu_repository->updateLanguage($menu, $languages);
        }

        $menu = FrontendMenuResource::make($menu);

        return ApiResponse::format(200, $menu, 'Menu updated successfully');
    }

    public function delete($id)
    {
        $menu = $this->frontend_menu_repository->delete($id);

        return ApiResponse::format(200, $menu);
    }

    public function export(){
        $file_path = ExcelExportHelper::export('Menus', \App::make(NavigationMenuExport::class));

        return ApiResponse::format(200, $file_path);
    }

    private function frontendSetting(){
        $frontend_setting = $this->frontend_settings_repository->getFirstRecord();
        if (!$frontend_setting){
            $frontend_setting = $this->frontend_settings_repository->create([
                'country_id' => \Session::get('country_id')
            ]);
        };
        return $frontend_setting;
    }
}
