<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private DashboardService $service;

    public function __construct(DashboardService $service)
    {
        $this->service = $service;
    }

    public function getGeneralInfoOverview(Request $request)
    {
        return response()->json([
            'data' => [
                'active_products' => $this->service->getActiveProductCount(),
                'active_campaigns' => $this->service->getActiveCampaignCount(),
                'total_users' => $this->service->getTotalUserCount(),
                'total_sales' => $this->service->getTotalSale($request->all()),
            ]
        ]);
    }

    public function getTransactionsOverview(Request $request)
    {
        return response()->json([
            'data' => [
                'cart_count' => $this->service->getCartCount(),
                'orders_completed' => $this->service->getCompletedOrderCount($request->all()),
                'orders_cancelled' => $this->service->getCancelledOrderCount($request->all()),
                'orders_for_shipping' => $this->service->getForShippingOrderCount($request->all()),
                'orders_for_donation' => $this->service->getForDonationOrderCount($request->all()),
            ]
        ]);
    }
}
