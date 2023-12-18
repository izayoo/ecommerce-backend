<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\Admin\OrderRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Http\Resources\CampaignResource;
use App\Http\Resources\CartResource;
use App\Services\CartService;

class CartController extends Controller
{
    private CartService $service;

    public function __construct(
        CartService $service
    ) {
        $this->service = $service;
    }

    public function addToCart(AddToCartRequest $request)
    {
        return response()->json([
            'message' => 'Successfully added to cart',
            'data' => new CartResource($this->service->addToCart($request->all()))
        ], 201);
    }

    public function updateCartItem($id, UpdateCartRequest $request)
    {
        return response()->json([
            'message' => 'Successfully updated cart item',
            'data' => CartResource::make($this->service->updateCartItem($id, $request->all()))
        ], 202);
    }

    public function removeCartItem($id)
    {
        $this->service->delete($id);
        return response()->json([], 204);
    }

    public function fetchCart()
    {
        return response()->json([
            'message' => 'Successfully fetched cart',
            'data' => CartResource::collection($this->service->fetchCart())
        ], 201);
    }

    public function checkout(OrderRequest $request)
    {
        return response()->json([
            'message' => 'Successfully placed order',
            'data' => $this->service->checkout($request->all())
        ], 201);
    }

    public function suggestedCampaigns()
    {
        return response()->json([
            'message' => 'Successfully fetched suggested campaigns',
            'data' => CampaignResource::collection($this->service->suggestedCampaigns())
        ], 201);
    }

    public function cartShippingFee()
    {
        return response()->json([
            'message' => 'Successfully fetched shipping fee',
            'data' => $this->service->getAuthCartShippingFee()
        ], 200);
    }
}
