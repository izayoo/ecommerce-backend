<?php

namespace App\Services;


use App\Enum\Constants;
use App\Models\Campaign;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\User;

class DashboardService
{
    public function getActiveProductCount()
    {
        return Product::where('status', Constants::STATUS_ACTIVE)->count();
    }

    public function getActiveCampaignCount()
    {
        return Campaign::where('status', Constants::STATUS_ACTIVE)->count();
    }

    public function getTotalUserCount()
    {
        return User::count();

    }

    public function getCartCount()
    {
        return Cart::count();
    }

    public function getCompletedOrderCount(array $params)
    {
        $data = Order::where('status', Constants::ORDER_STATUS_COMPLETED);
        if(array_key_exists('date_from', $params) && array_key_exists('date_to', $params)) {
            $data = $data->where('created_at', '>=', $params['date_from'])
                ->where('created_at', '<=', $params['date_to']);
        }
        return $data->count();
    }

    public function getCancelledOrderCount(array $params)
    {
        $data = Order::where('status', Constants::ORDER_STATUS_PAYMENT_FAILED);
        if(array_key_exists('date_from', $params) && array_key_exists('date_to', $params)) {
            $data = $data->where('created_at', '>=', $params['date_from'])
                ->where('created_at', '<=', $params['date_to']);
        }
        return $data->count();
    }

    public function getForShippingOrderCount(array $params)
    {
        $data = Order::where('status', Constants::ORDER_STATUS_FOR_SHIPPING);
        if(array_key_exists('date_from', $params) && array_key_exists('date_to', $params)) {
            $data = $data->where('created_at', '>=', $params['date_from'])
                ->where('created_at', '<=', $params['date_to']);
        }
        return $data->count();
    }

    public function getForDonationOrderCount(array $params)
    {
        $data = OrderProduct::where('is_for_donation', Constants::STATUS_ACTIVE)->whereHas('order', function($query){
            $query->where('status', '!=' , -1);
        });
        if(array_key_exists('date_from', $params) && array_key_exists('date_to', $params)) {
            $data = $data->where('created_at', '>=', $params['date_from'])
            ->where('created_at', '<=', $params['date_to']);
        }
        return $data->count();
    }

    public function getTotalSale(array $params)
    {
        $data = Order::where('status', Constants::ORDER_STATUS_COMPLETED);
        if(array_key_exists('date_from', $params) && array_key_exists('date_to', $params)) {
            $data = $data->where('created_at', '>=', $params['date_from'])
                ->where('created_at', '<=', $params['date_to']);
        }
        return number_format($data->sum('total'), 2);
    }
}
