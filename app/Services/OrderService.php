<?php

namespace App\Services;

use App\Mail\OrderConfirmationMail;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class OrderService extends BaseService
{
    private Order $model;
    private OrderProductService $orderProductService;

    public function __construct(Order $model, OrderProductService $orderProductService) {
        parent::__construct($model);
        $this->model = $model;
        $this->orderProductService = $orderProductService;
    }

    public function fetchProductOrderPaginated(array $query){
        $data = $this->model->where('status', '!=', -1);

        if( isset($query['search']) && $query['search'] ){
            $data = $data->whereHas('orderProducts', function($orderProduct) use ($query) {
                $orderProduct->whereHas('product', function($product) use ($query){
                    $product->where('name', 'LIKE', '%'. $query['search'] .'%');
                });

                $orderProduct->orWhereHas('campaign', function($campaign) use ($query){
                    $campaign->where('name', 'LIKE', '%'. $query['search'] .'%');
                });
            });
        }

        if( isset($query['status']) && $query['status'] != null){
            $data = $data->where('status', $query['status']);
        }

        if( isset($query['date_from']) && $query['date_from']){
            $data = $data->where('created_at', '>=', $query['date_from']);
        }

        if( isset($query['date_to']) && $query['date_to'] ){
            $data = $data->where('created_at', '<=', $query['date_to']);
        }


        if( isset($query['paginate']) && $query['paginate'] ){
            $pageConfig = $this->setPageConfig($query);
            return $data = $data->paginate($pageConfig['perPage'], ['*'], 'page', $pageConfig['page']);
        }

        $data = $data->get();
        return $data;
    }

    public function findByOrderNo(string $orderNo)
    {
        return $this->model->where('order_no', $orderNo)->first();
    }

    public function addOrderProduct(Order $order, mixed $item)
    {
        $data = [
            'order_id' => $order->id,
            'quantity' => $item->quantity,
            'product_id' => $item->product_id,
            'campaign_id' => $item->campaign_id,
            'is_for_donation' =>$item->is_for_donation
        ];

        return $this->orderProductService->create($data);
    }

    public function fetchOrdersByUser(int $userId)
    {
        return $this->model->where('user_id', $userId)->orderBy('created_at', 'DESC')->get();
    }

    public function sendOrderConfirmation(Order $order)
    {
        $data = [
            "order" => $order,
            "user" => $order->user
        ];
        Mail::to($order->user->email)->send(new OrderConfirmationMail($data));
    }

    public function hasForShipping(Order $order)
    {
        return count($order->orderProducts->filter(function($product) {
            return $product->is_for_donation == 0;
        })) > 0;
    }
}
