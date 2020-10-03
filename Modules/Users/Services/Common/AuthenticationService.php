<?php

namespace Modules\Users\Services\Common;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Notifications\Services\CMS\EmailService;
use Modules\Users\Entities\Researcher;
use Modules\Users\Facades\UsersErrorsHelper;
use Modules\Users\Repositories\AddressRepository;
use Modules\Users\Repositories\ResetPasswordRepository;
use Modules\Users\Repositories\UserRepository;
use Modules\Users\Transformers\UserResource;
use Illuminate\Support\Facades\Hash;

class AuthenticationService extends LaravelServiceClass
{
    private $user_repo;
    private $reset_password_repo;
    private $address_repo;
    private $email_service;
    private $MAGAZINE_EDITOR_TYPE = 1;
    private $JOURNAL_EDITOR_DIRECTOR_TYPE = 2;
    private $REFEREES_TYPE = 3;
    private $RESEARCHER_TYPE = 4;

    public function __construct(
        UserRepository $user,
        ResetPasswordRepository $resetPassword,
        AddressRepository $address,
        EmailService $email_service
    )
    {
        $this->user_repo = $user;
        $this->reset_password_repo = $resetPassword;
        $this->address_repo = $address;
        $this->email_service = $email_service;
    }

    /**
     * Handles User login
     *
     * @return JsonResponse
     */
    public function loginAsUser()
    {
        $loginStatus = $this->user_repo->AuthAttempt();

        if ($loginStatus) {
            $user =  Auth::user();

            if ($user->user_type != $this->RESEARCHER_TYPE) { // should 4 to be Researcher
                UsersErrorsHelper::unAuthenticated();
            }

            $tokenResult = $user->createToken(env('APP_NAME'));

            $user = $this->user_repo->update($user->id, ['token_last_renew' => Carbon::now()]);

            $token = $tokenResult->token;
            (request('remember_me'))? $token->expires_at = Carbon::now()->addMonths(2) : $token->expires_at = Carbon::now()->addMonths(1);
            $token->save();

            // Determine The Response Shape in login
            $auth = new UserResource($user, $tokenResult->accessToken, $token->expires_at);

            return ApiResponse::format(200, $auth, 'login Successfully');
        } else {
            UsersErrorsHelper::unAuthenticated();
        }
    }

    /**
     * Handles Admin login
     *
     * @return JsonResponse
     */
    public function loginAsAdmin()
    {
        $loginStatus = $this->user_repo->AuthAttempt();

        if ($loginStatus) {
            $user =  Auth::user();

            if ($user->user_type == $this->RESEARCHER_TYPE) { // should not be 4 to be Admin
                UsersErrorsHelper::unAuthenticated();
            }

            $tokenResult = $user->createToken(env('APP_NAME'));

            $user = $this->user_repo->update($user->id, ['token_last_renew' => Carbon::now()]);

            $token = $tokenResult->token;
            (request('remember_me'))? $token->expires_at = Carbon::now()->addMinutes(120) : $token->expires_at = Carbon::now()->addMinutes(60);
            $token->save();

            // Determine The Response Shape in login
            $auth = new UserResource($user, $tokenResult->accessToken, $token->expires_at);

            return ApiResponse::format(200, $auth, 'login Successfully');
        } else {
            UsersErrorsHelper::unAuthenticated();
        }
    }

    /**
     * Handles Register
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function register()
    {
        DB::beginTransaction();
        try {
            //Create User Record
            $user = $this->user_repo->create([
                'name' => request('name'),
                'email' => request('email'),
                'password' => bcrypt(request('password')),
                'user_type' => $this->RESEARCHER_TYPE, // RESEARCHER_TYPE = 4
            ]);

            // Create Researcher Record
            $client = new Researcher();
            $client->phone = request('phone');
            $user->client()->save($client);

            // Save token Last Renew
            $tokenResult = $user->createToken(env('APP_NAME'));
            $user = $this->user_repo->update($user->id, ['token_last_renew' => Carbon::now()]);

            // Save New Token
            $token = $tokenResult->token;
            $token->expires_at = Carbon::now()->addMonths(1);
            $token->save();

            // Determine The Response Shape in register
            $auth = new UserResource($user, $tokenResult->accessToken, $token->expires_at, true);

            DB::commit();
            return ApiResponse::format(200, $auth, 'Register Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('something went wrong');
        }
    }


    public function logout()
    {
        $value = false;
        if (Auth::check()) {
            $value = Auth::user()->token()->revoke();
        }
        return ApiResponse::format(200, $value, 'Logout Successfully');
    }

    /**
     * Handles Reset Password
     *
     * @return JsonResponse
     */
    public function resetPassword()
    {
        $oldPassword = request('old_password');
        $check = Hash::check($oldPassword, Auth::user()->password);
        if ($check) {
            Auth::user()->password = bcrypt(request('new_password'));
            Auth::user()->save();
            return ApiResponse::format(200, [], 'Your password changed successfully');
        } else {
            UsersErrorsHelper::inCorrectPassword();
        }
    }

    /**
     * Create token password forget
     *
     * @param  [string] email
     * @return JsonResponse
     */
    public function forgetPassword()
    {
        $user = $this->user_repo->getData(['email' => request('email')]);

        $passwordReset = $this->reset_password_repo->updateOrCreate(
            [
                'email' => $user->email
            ],
            [
                'email' => $user->email,
                'token' => mt_rand(100001, 999999)
            ]
        );

        if ($user && $passwordReset) {
            $render_data = [
                'token' => $passwordReset->token,
                'recipient' => $user->name,
            ];

            // Send email with token
            $this->email_service->email(
                $user->email,
                'users',
                'Reset-Password.reset-password',
                '['.config('app.name').'] Please reset your Password',
                $render_data
            );
        }

        return ApiResponse::format(200, null, 'We have e-mailed your password reset link!');
    }

    /**
     * Reset password
     *
     */
    public function forgetChangePassword()
    {
        $passwordReset = $this->reset_password_repo->getData(
            [
                ['token', request('token')],
                ['email', request('email')]
            ]
        );

        if (!$passwordReset) {
            UsersErrorsHelper::tokenInvalid();
        }

        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $this->reset_password_repo->delete($passwordReset->id);
            UsersErrorsHelper::tokenExpired();
        }

        // Get the user object
        $user = $this->user_repo->getData(['email' => $passwordReset->email]);

        // Update User object
        $user = $this->user_repo->update($user->id, ['password' => bcrypt(request('password')) ]);

        // delete reset password record
        $this->reset_password_repo->delete($passwordReset->id);

        // Sending Confirmation Email
        $render_data = [
            'recipient' => $user->name,
        ];

        $this->email_service->email(
            $user->email,
            'users',
            'Reset-Password.success-reset-password',
            '['.config('app.name').'] Password Change Successfully',
            $render_data
        );


        return ApiResponse::format(200, null,
            'Your password change Successfully, Please login with the new password');
    }
}
