<?php

namespace App\Services;

use App\Enum\Constants;
use App\Http\Resources\CartResource;
use App\Models\Campaign;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderProduct;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartService extends BaseService
{
    private Cart $model;
    private ProductService $productService;
    private UserService $userService;
    private OrderService $orderService;
    private TicketService $ticketService;
    private PaymayaService $paymayaService;
    private GogoExpressService $gogoExpressService;
    private ShippingService $shippingService;

    public function __construct(
        Cart $model,
        ProductService $productService,
        UserService $userService,
        OrderService $orderService,
        TicketService $ticketService,
        PaymayaService $paymayaService,
        GogoExpressService $gogoExpressService,
        ShippingService $shippingService
    ) {
        parent::__construct($model);
        $this->model = $model;
        $this->productService = $productService;
        $this->userService = $userService;
        $this->orderService = $orderService;
        $this->ticketService = $ticketService;
        $this->paymayaService = $paymayaService;
        $this->gogoExpressService = $gogoExpressService;
        $this->shippingService = $shippingService;
    }

    public function addToCart(array $data)
    {
        $this->productService->checkAvailability($data);

        $user = auth()->user();
        $data['user_id'] = $user->getAuthIdentifier();
        $data['is_for_donation'] = 1;
        $cart = $this->model->where('user_id', $data['user_id'])->where('campaign_id', $data['campaign_id'])
            ->where('product_id', $data['product_id'])->first();
        if($cart) {
            $data['quantity'] += $cart->quantity;
            return $this->update($cart->id, $data);
        }
        return $this->create($data);
    }

    public function fetchCart()
    {
        $user = auth()->user();

        if ($user) return CartResource::collection($this->model->where('user_id', $user->getAuthIdentifier())->get());

        throw new HttpResponseException(response()->json([
            'message' => 'Unauthorized'
        ], 403));
    }

    public function updateCartItem($id, array $data)
    {
        DB::beginTransaction();
        $user = auth()->user()->getAuthIdentifier();
        $cart = $this->find($id);
        if ($cart->user_id != $user) {
            throw new HttpResponseException(response()->json([
                'message' => 'Unauthorized'
            ], 403));
        }

        $this->productService->checkAvailability([
            'product_id' => $cart->product_id,
            'quantity' => $data['quantity']
        ]);


        $cart->quantity = $data['quantity'];
        $cart->is_for_donation = $data['is_for_donation'];
        $cart->save();
        DB::commit();

        return $this->find($cart->id);
    }

    public function checkout(array $data)
    {
        DB::beginTransaction();
        $user = $this->userService->findActive(auth()->user()->getAuthIdentifier());
        if ($user->addresses->count() == 0) {
            throw new HttpResponseException(response()->json([
                "message" => "Please fill up address data."
            ], 400));
        }

        $cart = $this->fetchCart();
        if ($cart->count() == 0) {
            throw new HttpResponseException(response()->json([
                "message" => "Cart is empty."
            ], 400));
        }
        $order = $this->addOrder($data);

        foreach ($cart as $item) {
            $this->productService->checkAvailability([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity
            ]);
            $this->addOrderProduct($order, $item);
        }

        if ($order->payment_method == Constants::PAYMENT_TYPE_COD) {
            $order->status = Constants::ORDER_STATUS_COMPLETED;
        } else {
            $order->status = Constants::ORDER_STATUS_PENDING;
        }
        $order->shipping = 0;
        $order->total = $order->subtotal + $order->shipping;
        $order->save();
        if ($this->orderService->hasForShipping($order)) {
            $order->shipping = $this->getAuthCartShippingFee()['shipping_fee'];
            $order->total = $order->subtotal + $order->shipping;
            $order->save();
        }

        $this->deleteCart();
        if ($order->payment_method == Constants::PAYMENT_TYPE_PAYMAYA) {
            try {
                $payment = $this->paymayaService->createCheckout($order);

            } catch (ClientException $e) {
                $response = $e->getResponse();
                $responseBodyAsString = $response->getBody()->getContents();
                throw new HttpResponseException(response()->json(["data" => $responseBodyAsString]));
            }
        } else {
            $payment = [];
        }
        DB::commit();

        return $payment;
    }

    private function deleteCart()
    {
        $user = auth()->user();
        if ($user) return $this->model->where('user_id', $user->getAuthIdentifier())->delete();

        throw new HttpResponseException(response()->json([
            'message' => 'Unauthorized'
        ], 403));
    }

    private function generateOrderId(): string
    {
        $number = str_pad(mt_rand(1,999999),6,'0',STR_PAD_LEFT);
        $orderNo = date('dmy') . $number;
        if (!$this->orderService->findByOrderNo($orderNo)) {
            return $orderNo;
        } else {
            return $this->generateOrderId();
        }
    }

    private function generateTicketNo()
    {
        $prefix = 'RPL-';
        $number = str_pad(mt_rand(1,99999999),8,'0',STR_PAD_LEFT);
        $hyphenated = $prefix . substr_replace(
            substr_replace($number, '-', 3,0), '-', 8,0);
        if (!$this->ticketService->findByTicketNo($hyphenated)) {
            return $hyphenated;
        } else {
            return $this->generateOrderId();
        }
    }

    private function addOrder(array $data) {
        $additionalData = [
            'user_id' => auth()->user()->getAuthIdentifier(),
            'order_no' => $this->generateOrderId(),
            'subtotal' => 0,
            'shipping' => 0,
            'total' => 0,
            'status' => Constants::ORDER_STATUS_PENDING
        ];
        return $this->orderService->create(array_merge($data, $additionalData));


    }

    private function addOrderProduct(Order $order, mixed $item) {
        $orderProduct = $this->orderService->addOrderProduct($order, $item);
        $product = $orderProduct->product;
        $product->stock = $product->stock - $item->quantity;
        $product->save();
        $order->subtotal = $order->subtotal + ($product->price * $item->quantity);
        $order->save();
    }

    private function rewardTicket(OrderProduct $orderProduct)
    {
        $quantity = $orderProduct->quantity;
        for ($index = 1; $index <= $quantity; $index++) {
            $this->ticketService->create([
                'order_product_id' => $orderProduct->id,
                'ticket_no' => $this->generateTicketNo(),
                'status' => Constants::TICKET_STATUS_PENDING
            ]);
        }
    }

    public function suggestedCampaigns()
    {
        $user = auth()->user();

        if ($user) {
            $campaignIds = $this->model->where('user_id', $user->getAuthIdentifier())->pluck('campaign_id');
            return Campaign::whereNotIn('id', $campaignIds)->inRandomOrder()->take(3)->get();
        }

        throw new HttpResponseException(response()->json([
            'message' => 'Unauthorized'
        ], 403));
    }

    public function getAuthCartShippingFee()
    {
        $userId = auth()->user()->getAuthIdentifier();
        $user = $this->userService->find($userId);
//        $shippingRates =  $this->gogoExpressService->checkShippingRates($user);
        $totalWeight = $this->fetchCartTotalWeightToShip()['total_weight'];
        $shippingBag = '';
        if  ($totalWeight != 0) {
            foreach (Constants::SHIPPING_WEIGHT as $key => $item) {
                if ($totalWeight <= $item) {
                    $shippingBag = $key;
                }
            }
            $area = $this->shippingService->getAreaByLocation(
                $user->shippingAddress->region,
                $user->shippingAddress->province,
                $user->shippingAddress->city
            );

            return [
                "shipping_fee" => $this->shippingService->getShippingFeeByPackagingAndArea($shippingBag, $area)
            ];
        }

        return [
            "shipping_fee" => 0
        ];
    }

    public function fetchCartTotalWeightToShip()
    {
        $cart = $this->fetchCart();
        $total = 0;
        foreach ($cart as $item) {
            if ($item->is_for_donation == 0) {
                $total += $item->product->weight_in_grams * $item->quantity;
            }
        }

        return [
            "total_weight" => $total
        ];
    }

    public function paymayaWebhook(Request $request)
    {
        $status = $request->get('status');
        $reference = $request->get('requestReferenceNumber');
        $checkoutId = $request->get('id');
        $order = $this->orderService->findByOrderNo($reference);
        $shipping = $this->orderService->hasForShipping($order);

        switch ($status) {
            case 'PAYMENT_SUCCESS':
                $order->status = Constants::ORDER_STATUS_COMPLETED;
                $orderProducts = $order->orderProducts;

                foreach ($orderProducts as $orderProduct) {
                    $campaign = $orderProduct->campaign;
                    if ($campaign) {
                        $this->rewardTicket($orderProduct);
                    }
                }
//                if($shipping) $this->gogoExpressService->createOrder($order);

                $this->orderService->sendOrderConfirmation($order);
                break;
            case 'PAYMENT_CANCELLED':
            case 'PAYMENT_EXPIRED':
            case 'PAYMENT_FAILED':
                $order->status = Constants::ORDER_STATUS_PAYMENT_FAILED;
                break;
        }

        $order->checkout_id = $checkoutId;
        $order->save();
    }
}
