<?php

namespace Modules\Notifications\Services\CMS;

use Exception;
use Illuminate\Http\JsonResponse;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\WebPushConfig;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Firebase\Exception\FirebaseException;
use Modules\Notifications\Repositories\DeviceTokenRepository;
use Modules\Users\Repositories\ProductNotificationRepository;

class FirebaseNotificationService extends LaravelServiceClass
{
    private $messaging;
    private $device_token_repository;
    private $product_notification_repo;
    public $products = [];

    public function __construct(DeviceTokenRepository $device_token_repository,
                                ProductNotificationRepository $product_notification_repo
                                )
    {
        $this->device_token_repository = $device_token_repository;
        $this->product_notification_repo = $product_notification_repo;

        $this->initializing();
    }
    /* Connecting with firebase */
    public function initializing()
    {
        $factory = (new Factory)
            ->withServiceAccount(storage_path() . '/json/firebase_credentials.json');

        $this->messaging = ($factory)->createMessaging();
    }

    /* Helper Method return Array of Android Tokens, IOS Tokens, Web Tokens */
    public function separateDevicesToken($device_tokens)
    {
        $android_tokens = [];
        $ios_tokens = [];
        $web_tokens = [];

        foreach ($device_tokens as $device_token) {
            if ($device_token['device_os'] == 'IOS') {
                $ios_tokens [] = $device_token['device_token'];
            } elseif ($device_token['device_os'] == 'ANDROID') {
                $android_tokens [] = $device_token['device_token'];
            } elseif ($device_token['device_os'] == 'WEB') {
                $web_tokens [] = $device_token['device_token'];
            }
        }

        return [$android_tokens,$ios_tokens,$web_tokens];
    }

    /* Attach Android Configuration and Send Notification */
    public function notifyAndroid($message, $android_tokens, $title = null, $body = null, $color = null)
    {
        $title = ($title) ? $title : request('title');
        $body = ($body) ? $body : request('body');
        $color = ($color) ? $color : request('color');

        $config = AndroidConfig::fromArray([
            'ttl' => '36000s',
            'priority' => 'normal',
            'notification' => [
                'title' => $title,
                'body' => $body,
                'color' => $color,
            ],
        ]);

        $message = $message->withAndroidConfig($config);
        $this->messaging->sendMulticast($message, $android_tokens);
    }

    /* Attach Web Configuration and Send Notification */
    public function notifyWeb($message, $web_tokens, $title = null, $body = null, $link = null)
    {
        $title = ($title) ? $title : request('title');
        $body = ($body) ? $body : request('body');
        $link = ($link) ? $link : request('link');

        $config = WebPushConfig::fromArray([
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'fcm_options' => [
                'link' => $link,
            ],
        ]);

        $message = $message->withWebPushConfig($config);

        $this->messaging->sendMulticast($message, $web_tokens);
    }

    /* Attach IOS Configuration and Send Notification */
    public function notifyIOS($message, $ios_tokens, $title = null, $body = null)
    {
        $title = ($title) ? $title : request('title');
        $body = ($body) ? $body : request('body');

        $config = ApnsConfig::fromArray([
            'headers' => [
                'apns-priority' => '10',
            ],
            'payload' => [
                'aps' => [
                    'alert' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'badge' => 1,
                ],
            ],
        ]);

        $message = $message->withApnsConfig($config);

        $this->messaging->sendMulticast($message, $ios_tokens);
    }

    public function notify($message, $device_tokens, $title = null, $body = null, $color = null, $link = null)
    {
        list($android_tokens, $ios_tokens, $web_tokens) = $this->separateDevicesToken($device_tokens);

        if (count($ios_tokens)) {
            $this->notifyIOS($message, $ios_tokens, $title, $body);
        }

        if (count($android_tokens)) {
            $this->notifyAndroid($message, $android_tokens, $title, $body, $color);
        }

        if (count($web_tokens)) {
            $this->notifyWeb($message, $web_tokens, $title, $body, $link);
        }
    }

    /**
     * Notify
     * @return JsonResponse
     * @throws Exception
     */
    public function send()
    {
        try {
            $message = CloudMessage::new();

            if (request()->has('target')) {
                switch (request('target')) {
                    case 'IOS':
                        $tokens = $this->device_token_repository->all(['device_os' => 'IOS']);
                        break;
                    case 'ANDROID':
                        $tokens = $this->device_token_repository->all(['device_os' => 'ANDROID']);
                        break;
                    case 'WEB':
                        $tokens = $this->device_token_repository->all(['device_os' => 'WEB']);
                        break;
                    default: // All users
                        $tokens = $this->device_token_repository->all();
                        break;
                }
            } else { // get Device token of array of users
                $tokens = $this->device_token_repository->whereIn('user_id', request('users'));
            }

            $device_tokens = array_chunk($tokens->toArray(), 500);

            // we can only notify 500 at a time
            foreach ($device_tokens as $device_token) {
                $this->notify($message, $device_token);
            }

            return ApiResponse::format(200, [], 'successfully');
        } catch (FirebaseException $e) {
            throw new Exception('something went wrong');
        }
    }

    /**
     * Notify
     * @return JsonResponse
     * @throws Exception
     */
    public function ProductNotifyMe()
    {
        try {
            $message = CloudMessage::new();

            if (count($this->products)){
                $products_id = $this->products->pluck('product_id');
                $product_notifications = $this->product_notification_repo->getData($products_id);

                foreach ($this->products as $single_product){
                    $users = $product_notifications->where('product_id', $single_product['product_id'])->pluck('user_id')->toArray();

                    $tokens = $this->device_token_repository->whereIn('user_id', $users);

                    if (count($tokens)){
                        $device_tokens = array_chunk($tokens->toArray(), 500);

                        // we can only notify 500 at a time
                        foreach ($device_tokens as $device_token) {
                            $this->notify($message, $device_token, $single_product['title'],$single_product['body']);
                        }
                    }
                }

                $this->product_notification_repo->deleteBulk($products_id);
            }

            return ApiResponse::format(200, [], 'successfully');
        } catch (FirebaseException $e) {
            throw new Exception('something went wrong');
        }
    }
}
