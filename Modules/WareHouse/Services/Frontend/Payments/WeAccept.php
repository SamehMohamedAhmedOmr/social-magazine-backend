<?php

namespace Modules\WareHouse\Services\Frontend\Payments;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Modules\WareHouse\Exceptions\PaymentException;
use Modules\WareHouse\Repositories\OrderRepository;
use Modules\WareHouse\Repositories\PaymentEntryLogsRepository;

class WeAccept
{
    const PENDING_STATUS = 0;

    protected $base_url;
    protected $api_key;
    protected $user_name;
    protected $password;
    protected $iframe_id;
    protected $client;
    protected $integration_card_id;
    protected $secret_key;
    protected $payment_log_repo;
    protected $order_repo;

    public function __construct(
        Client $client,
        PaymentEntryLogsRepository $payment_log_repo,
        OrderRepository $order_repo
    )
    {
        $this->base_url = config('warehouse.payments.we_accept.base_url');
        $this->api_key = config('warehouse.payments.we_accept.api_key');
        $this->user_name = config('warehouse.payments.we_accept.user_name');
        $this->password = config('warehouse.payments.we_accept.password');
        $this->iframe_id = config('warehouse.payments.we_accept.iframe_id');
        $this->integration_card_id = config('warehouse.payments.we_accept.integration_card_id');
        $this->secret_key = config('warehouse.payments.we_accept.secret_key');
        $this->client = $client;
        $this->payment_log_repo = $payment_log_repo;
        $this->order_repo = $order_repo;
    }

    public function create($price)
    {
        $profile = $this->authentication();
        $userData = $this->userData(Auth::user());
        $order = $this->createOrder(Auth::id(), $userData, $price, $profile->profile->id, $profile->token);
        $payment = $this->generatePaymentKey($profile->token, $order->id, $price, $userData);

        return [
            'frame_url' => $this->base_url."acceptance/iframes/{$this->iframe_id}?payment_token={$payment->token}",
            'order_id' => $order->id,
        ];
    }

    protected function authentication()
    {
        if ($this->api_key) {
            $data = [
                "api_key" => $this->api_key,
            ];
        } else {
            $data = [
                'username' => $this->user_name,
                'password' => $this->password
            ];
        }

        $requestHeaders = [
            'Content-Type' => 'application/json',
            'Content-Length' =>  strlen(json_encode($data)),
        ];

        $profile = $this->sendRequest('auth/tokens', $data, 'post', $requestHeaders);

        throw_if(
            isset($profile->message) || !isset($profile->token),
            new PaymentException(['we_accept' => 'Payment profile error'])
        );

        return $profile;
    }

    protected function createOrder($user_id, $userData, $price, $merchant_id, $auth_token)
    {
        unset($userData['shipping_method']);

        $data = [
            "delivery_needed" => "false",
            "merchant_id" => $merchant_id,
            "merchant_order_id" => $user_id . rand(1, 5) . \Str::random(2),
            "amount_cents" => $price * 100,
            "currency" => "EGP",
            "items" => [],
            "shipping_data" => $userData
        ];

        $requestHeaders = [
            'Content-Type' => 'application/json',
            'Content-Length' =>  strlen(json_encode($data)),
        ];

        $order = $this->sendRequest('ecommerce/orders?token='.$auth_token, $data, 'post', $requestHeaders);

        throw_if(!isset($order->id), new PaymentException(['we_accept' => 'Enable to create order']));

        return $order;
    }

    protected function generatePaymentKey($auth_token, $order_id, $price, $user_data)
    {
        $paymentData = [
            "amount_cents" => $price * 100,
            "currency" => "EGP",
            "order_id" => $order_id,
            "card_integration_id" => $this->integration_card_id,
            "billing_data" => $user_data,
        ];

        $requestHeaders = [
            'Content-Type' => 'application/json',
            'Content-Length: ' . strlen(json_encode($paymentData)),
        ];

        $payment = $this->sendRequest(
            'acceptance/payment_keys?token=' . $auth_token,
            $paymentData,
            'post',
            $requestHeaders
        );

        throw_if(!isset($payment->token), new PaymentException(['we_accept' => 'Enable to do payment']));

        return $payment;
    }

    protected function userData($user)
    {
        return [
                "first_name" => $user->name,
                "phone_number" => $user->client->phone,
                "last_name" => 'NA',
                "email" => $user->email,
                "apartment" => 'NA',
                "floor" => 'NA',
                "street" => 'NA',
                "building" => 'NA',
                "postal_code" => 'NA',
                "country" => 'NA',
                "city" => 'NA',
                "shipping_method" => "NA",
                "state" => "NA",
        ];
    }

    protected function sendRequest($endPoint, $data, $method = 'get', $requestHeaders = [])
    {
        try {
            $res = $this->client->$method($this->base_url . $endPoint, [
                'json' => $data,
                'headers' => $requestHeaders
            ]);
            return json_decode($res->getBody()->getContents());
        } catch (\Exception $e) {
            throw new PaymentException(['we_accept' => 'Payment Error']);
        }
    }

    ##### CallBack
    public function createLog($success, $order_id, $request)
    {
        $this->payment_log_repo->create([
            'text' => $request,
            'order_id' => $order_id ?? 0,
            'success' => $success ?? '',
        ]);
    }

    public function validateCallbackHmac($request)
    {
        $required_params = [
            'amount_cents', 'created_at', 'currency', 'error_occured', 'has_parent_transaction', 'id',
            'integration_id', 'is_3d_secure', 'is_auth', 'is_capture', 'is_refunded', 'is_standalone_payment', 'is_voided', 'order',
            'owner', 'pending', 'source_data_pan', 'source_data_sub_type', 'source_data_type', 'success',
        ];

        $concated_string = '';
        $hashed_hmac = $request->hmac;
        foreach ($required_params as $param) {
            $concated_string .= $request->has($param) ? $request[$param] : '';
        }
        $hashed_string = hash_hmac('sha512', $concated_string, $this->secret_key);
        if (md5($hashed_string) === md5($hashed_hmac)) {
            return true;
        } else {
            return false;
        }
    }

    public function updateOrder($order_id, $success)
    {
        if ($success) {
            $this->order_repo->update($order_id, ['status' => self::PENDING_STATUS], [], 'payment_order_id');
        }
    }
}
