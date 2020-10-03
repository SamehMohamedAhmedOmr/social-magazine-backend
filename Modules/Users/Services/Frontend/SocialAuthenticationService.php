<?php

namespace Modules\Users\Services\Frontend;

use Carbon\Carbon;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Users\Repositories\ResearcherRepository;
use Modules\Users\Repositories\UserRepository;
use Modules\Users\Transformers\AuthResource;
use Modules\Users\Transformers\UserResource;

class SocialAuthenticationService extends LaravelServiceClass
{
    private $user_repo;
    private $client_repo;
    private $client_type = 2;

    public function __construct(UserRepository $user, ResearcherRepository $client)
    {
        $this->user_repo = $user;
        $this->client_repo = $client;
    }
    /**
     * Handles Login With FaceBook
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function facebookAuth()
    {
        try {
            DB::beginTransaction();
            $getInfo = Socialite::driver('facebook')->stateless()->userFromToken(request('social_token'));
            return $this->facebookAuthentication($getInfo);
        } catch (Exception $e) {
            DB::rollBack();
            throw new AuthenticationException('There\'s no user with that name in Facebook');
        }
    }

    public function facebookAuthentication($user_information)
    {
        $facebook_user = $this->user_repo->getData(['email' => $user_information->email]);

        if (!$facebook_user) {
            $facebook_client_user = $this->client_repo->getData(['facebook_id' => $user_information->id]);
            if (!$facebook_client_user) {
                // Register
                $facebook_user = $this->facebookRegister($user_information);
            } else { // found client but mail changed
                $facebook_user = $facebook_client_user->user;
            }
        }

        // Save token Last Renew
        $tokenResult = $facebook_user->createToken(env('APP_NAME'));
        $facebook_user = $this->user_repo->update($facebook_user->id, ['token_last_renew' => Carbon::now()]);

        // Save New Token
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addMonths(1);
        $token->save();

        $auth = new UserResource($facebook_user, $tokenResult->accessToken, $token->expires_at, true);

        DB::commit();
        return ApiResponse::format(200, $auth, 'Successful login');
    }

    public function facebookRegister($user_information)
    {
        $email = (isset($user_information->email)) ? $user_information->email : $user_information->id.'@retailk.com';

        // Create User Object
        $facebook_user = $this->user_repo->create([
            'name' => $user_information->name,
            'email' => $email,
            'password' => bcrypt(rand(10, 20)),
            'user_type' => $this->client_type, // client type = 2
        ]);


        $client_data =[
            'facebook_id' => $user_information->id,
            'user_id' => $facebook_user->id
        ];
        // Create Researcher Record
        $this->client_repo->create($client_data);
        return $facebook_user;
    }
}
