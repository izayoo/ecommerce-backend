<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class PaymayaService
{
    const FORM = 'paymaya';

    protected function createAuthorizationToken()
    {
        return base64_encode(env('PAYMAYA_PUBLIC_KEY') . ':' . env('PAYMAYA_SECRET_KEY'));
    }

    public function createCheckout(Order $order)
    {
        $orderProducts = '';
        foreach ($order->orderProducts as $index => $product)
        {
            if ($index != 0) $orderProducts .= ',';
            $orderProducts .= '{
                  "name": "' . $product->product->name . '",
                  "code": "' . $product->product->slug . '",
                  "description": "' . $product->product->description . '",
                  "quantity": "' . $product->quantity . '",
                  "amount": {
                    "value": "' . $product->product->price . '"
                  },
                  "totalAmount": {
                    "value": "' . ($product->quantity * $product->product->price) . '"
                  }
                }';
        }

        $client = new Client();
        $response = $client->request('POST', env('PAYMAYA_API_URL') .'/checkout/v1/checkouts', [
            'body' => '{
              "totalAmount": {
                "currency": "PHP",
                "value": "' . $order->total . '",
                "details": {
                  "shippingFee": "' . $order->shipping . '",
                  "subtotal": "' . $order->subtotal . '"
                }
              },
              "buyer": {
                "contact": {
                  "phone": "' . $order->user->country_code . $order->user->mobile_no . '",
                  "email": "' . $order->user->email . '"
                },
                "billingAddress": {
                  "line1": "' . $order->user->billingAddress->address1 . '",
                  "line2": "' . $order->user->billingAddress->address2 . '",
                  "city": "' . $order->user->billingAddress->city . '",
                  "state": "' . $order->user->billingAddress->region . '",
                  "countryCode": "PH"
                },
                "shippingAddress": {
                  "firstName": "' . $order->user->fname . '",
                  "lastName": "' . $order->user->lname . '",
                  "phone": "' . $order->user->country_code . $order->user->mobile_no . '",
                  "email": "' . $order->user->email . '",
                  "line1": "' . $order->user->shippingAddress->address1 . '",
                  "line2": "' . $order->user->shippingAddress->address2 . '",
                  "city": "' . $order->user->shippingAddress->city . '",
                  "state": "' . $order->user->shippingAddress->region . '",
                  "countryCode": "PH"
                },
                "firstName": "' . $order->user->fname . '",
                "lastName": "' . $order->user->lname . '",
                "birthday": "' . substr($order->user->birthdate,0,10) . '"
              },
              "redirectUrl": {
                "success": "' . env('APP_URL') . '/my-account/active-tickets",
                "failure": "' . env('APP_URL') . '/my-account/my-orders",
                "cancel": "' . env('APP_URL') . '/my-account/my-orders"
              },
              "items": [
                ' . $orderProducts . '
              ],
              "requestReferenceNumber": "' . $order->order_no . '"
            }',
            'headers' => [
                'accept' => 'application/json',
                'authorization' => 'Basic ' . $this->createAuthorizationToken(),
                'content-type' => 'application/json',
            ],
        ]);

        return json_decode((string)$response->getBody());
    }
}
