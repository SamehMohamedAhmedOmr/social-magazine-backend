<?php

namespace Modules\Users\Services\CMS;

use Illuminate\Http\JsonResponse;
use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Users\ExcelExports\AdminExport;
use Modules\Users\Facades\UsersTypesHelper;
use Modules\Users\Repositories\CMSUsersRepository;
use Modules\Users\Repositories\UserRepository;
use Modules\Users\Transformers\AdminResource;

class CMSUsersService extends LaravelServiceClass
{
    private $user_repo;
    private $adminRepository;
    protected $admin_type = 1;

    public function __construct(UserRepository $user_repo, CMSUsersRepository $adminRepository)
    {
        $this->user_repo = $user_repo;
        $this->adminRepository = $adminRepository;
    }

    public function index()
    {
        if (request('is_pagination')) {
            list($users, $pagination) = parent::paginate($this->user_repo, null, true, [
                'user_type' =>  UsersTypesHelper::RESEARCHER_TYPE()
            ]);
        } else {
            $users = parent::list($this->user_repo, true, [
                'user_type' =>  UsersTypesHelper::RESEARCHER_TYPE()
            ]);
            $pagination = null;
        }

        $users->load([
            'roles',
        ]);

        $users = AdminResource::collection($users);
        return ApiResponse::format(200, $users, null, $pagination);
    }

    /**
     * Handles Add New CMSUser
     *
     * @param null $request
     * @return JsonResponse
     */
    public function store($request = null)
    {
        $user_data = $request->all();
        $user_data['user_type'] =  UsersTypesHelper::RESEARCHER_TYPE();
        $user_data['password'] = bcrypt($user_data['password']);

        $user =  $this->user_repo->create($user_data);

        $this->user_repo->syncRoles($user, $request->roles);

        $user->load([
            'roles',
        ]);

        $user = AdminResource::make($user);
        return ApiResponse::format(201, $user, 'CMSUser Added!');
    }

    public function show($id)
    {
        $user = $this->user_repo->get($id, [
            'user_type' =>  UsersTypesHelper::RESEARCHER_TYPE()
        ]);

        $user->load([
            'roles',
        ]);

        $user = AdminResource::make($user);
        return ApiResponse::format(201, $user);
    }

    public function update($id, $request = null)
    {
        $user = $this->user_repo->update($id, $request->only('name', 'email', 'is_active'));

        if ($request->roles) {
            $this->user_repo->syncRoles($user, $request->roles);
        }

        $user->load([
            'roles',
        ]);

        $user = AdminResource::make($user);
        return ApiResponse::format(200, $user);
    }

    public function delete($id)
    {
        $user = $this->user_repo->delete($id);
        return ApiResponse::format(200, $user, 'CMSUser Deleted!');
    }

    public function export(){
        $file_path = ExcelExportHelper::export('Admins', \App::make(AdminExport::class));

        return ApiResponse::format(200, $file_path);
    }

}
