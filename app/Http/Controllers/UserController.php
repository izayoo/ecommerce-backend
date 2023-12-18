<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeAddressRequest;
use App\Http\Requests\UpdateAddressesRequest;
use App\Http\Requests\UserDetailRequest;
use App\Http\Resources\CampaignTicketsResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderResourceWithProducts;
use App\Http\Resources\TicketResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(
        UserService $userService
    ) {
        $this->userService = $userService;
    }

    public function changeAddress(ChangeAddressRequest $request)
    {
        return response()->json([
            "message" => "Successfully updated address",
            "data" => $this->userService->addOrUpdateAccountAddress($request->all())
        ]);
    }

    public function updateAccountDetails(UserDetailRequest $request)
    {
        return response()->json([
            'message' => "Successfully updated account details",
            'data' => $this->userService->updateAccountDetails($request->all())
        ]);
    }

    public function fetchAccountDetails()
    {
        return response()->json([
            'data' => $this->userService->fetchAuthUser()
        ]);
    }

    public function fetchAccountAddress()
    {
        $userId = auth()->user()->getAuthIdentifier();
        return response()->json([
            'data' => $this->userService->fetchAuthUserAddress($userId)
        ]);
    }

    public function fetchAccountActiveTickets()
    {
        $userId = auth()->user()->getAuthIdentifier();
        return response()->json([
            'data' => CampaignTicketsResource::collection($this->userService->fetchActiveCampaignByUserOrders($userId))
        ]);
    }

    public function fetchAccountOrders()
    {
        $userId = auth()->user()->getAuthIdentifier();
        return response()->json([
            'data' => OrderResourceWithProducts::collection($this->userService->fetchOrders($userId))
        ]);
    }

    /**
     * update addresses
     */
    public function updateAddresses(UpdateAddressesRequest $request)
    {
        $data = $request->validated();

        $this->userService->updateAddresses($data);

        return response()->json([
            "message" => "Successfully updated address",
            "data" => $this->userService->fetchAuthUserAddress(auth()->id())
        ]);
    }
}
