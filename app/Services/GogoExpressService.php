<?php

namespace App\Services;

use App\Helpers\Nonce;
use App\Models\Order;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class GogoExpressService
{
    public function createAuthorizationToken()
    {
        $header = $this->base64encode(json_encode(["alg"=> "HS256", "typ" => "JWT"]));
        $payload = $this->base64encode(json_encode([
            "iat" => time(),
            "jti" => time(),
            "sub" => env('GOGOEXPRESS_API_KEY')
        ]));
        $signature = $this->urlSafe(
            hash_hmac('sha256',
                $header . '.' . $payload,
                env('GOGOEXPRESS_SECRET_KEY')
            ));

        return $header . '.' . $payload . '.' . $signature;
    }

    private function base64encode(string $value)
    {
        return $this->urlSafe(base64_encode(utf8_decode($value)));
    }

    private function urlSafe(string $value)
    {
//        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
        return str_replace('+', '-',
            str_replace('/', '_',
                str_replace('=', '', $value
        )));
    }

    public function createOrder(Order $order)
    {
        $client = new Client();
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->createAuthorizationToken(),
        ];
        $orderProducts = '';
        foreach ($order->orderProducts as $index => $product)
        {
            if ($index != 0) $orderProducts .= ',';
            $orderProducts .= '{
                  "type": "product",
                  "description": "' . $product->product->name . '",
                  "amount": ' . $product->product->price . ',
                  "quantity": ' . $product->quantity . ',
                }';
        }

        if ($order->shipping != 0) {
            $orderProducts .= ',{
              "type": "shipping",
              "description": "Shipping fee",
              "amount": ' . $order->shipping . '
            }';
        }
        $body = '{
          "status": "for_pickup",
          "delivery_address": {
            "name": "' . $order->user->fname . $order->user->lname . '",
            "phone_number": "",
            "mobile_number": "' . $order->user->country_code . $order->user->mobile_no . '",
            "line_1": "' . $order->user->shippingAddress->address1 . '",
            "line_2": "' . $order->user->shippingAddress->address2 . '",
            "district": "' . $order->user->shippingAddress->barangay  . '",
            "city": "' . $order->user->shippingAddress->city . '",
            "state": "' . $order->user->shippingAddress->province . '",
            "country": "PH"
          },
          "contact_number": "' . $order->user->country_code . $order->user->mobile_no . '",
          "buyer_name": "' . $order->user->fname . $order->user->lname . '",
          "payment_provider": "other",
          "payment_method": "other",
          "items": [
            ' . $orderProducts . '
          ],
          "currency": "PHP",
          "shipment": "custom",
          "total": ' . $order->total . '
        }';
        $request = new Request('POST', env('GOGOEXPRESS_API_URL') . '/orders', $headers, $body);
        $res = $client->sendAsync($request)->wait();
        echo $res->getBody();

    }

    public function checkShippingRates(User $user)
    {
        $token = $this->createAuthorizationToken();
        $client = new Client();
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ];

        $body = '{
          "data": {
            "attributes": {
              "service_type": "next_day",
              "delivery_address": {
                "line_1": "' . $user->shippingAddress->address1 . '",
                "line_2": "' . $user->shippingAddress->address2 . '",
                "district": "' . $user->shippingAddress->barangay  . '",
                "city": "' . $user->shippingAddress->city . '",
                "state": "' . $user->shippingAddress->province . '",
                "country": "PH"
              },
              "by_region": true
            }
          }
        }';
        $request = new Request('POST', env('GOGOEXPRESS_API_URL') . '/orders/estimates/rates', $headers, $body);
        $response = $client->sendAsync($request)->wait();
        return json_decode((string)$response->getBody());
    }
}
