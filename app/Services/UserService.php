<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\User;
use App\Models\UserAddress;
use Carbon\Carbon;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserService extends BaseService
{
    private User $model;
    private UserAddressService $userAddressService;
    private TicketService $ticketService;
    private OrderService $orderService;

    public function __construct(
        User $model,
        UserAddressService $userAddressService,
        TicketService $ticketService,
        OrderService $orderService
    ) {
        parent::__construct($model);
        $this->model = $model;
        $this->userAddressService = $userAddressService;
        $this->ticketService = $ticketService;
        $this->orderService = $orderService;
    }

    public function findByEmail(string $email)
    {
        $data = $this->model->where('email', $email)->first();

        if ($data) return $data;

        throw new HttpResponseException(response()->json([
            'message' => 'Resource not found'
        ], 404));
    }

    public function fetchAuthUser()
    {
        $userId = auth()->user()->getAuthIdentifier();
        return $this->findActive($userId);
    }

    public function updateAccountDetails(array $data)
    {
        $userId = auth()->user()->getAuthIdentifier();
        return $this->update($userId, $data);
    }

    public function addOrUpdateAccountAddress(array $data)
    {
        $data['user_id'] = auth()->user()->getAuthIdentifier();
        $address = $this->userAddressService->findByUserAndAddressType($data['user_id'], $data['address_type_id']);

        if ($address) {
            return $this->userAddressService->update($address->id, $data);
        }

        return $this->userAddressService->create($data);
    }

    public function fetchAuthUserAddress(int $userId)
    {
        return $this->userAddressService->fetchActiveByUser($userId);
    }

    public function fetchOrders(int $userId)
    {
        return $this->orderService->fetchOrdersByUser($userId);
    }

    //Override
    public function fetchActivePaginated(array $query)
    {
        $pageConfig = $this->setPageConfig($query);
        $data = $this->model->where('status', '!=', -1);
        if (array_key_exists('search', $query)) {
            $data->where(function($db) use ($query){
                $db->where('fname', 'LIKE', $query['search']."%")->orWhere('fname', 'LIKE', "% ".$query['search']."%")
                    ->orWhere('lname', 'LIKE', $query['search']."%")->orWhere('lname', 'LIKE', "% ".$query['search']."%")
                    ->orWhere('email', 'LIKE', $query['search']."%");
            });
        }

        return $data->paginate($pageConfig['perPage'], ['*'], 'page', $pageConfig['page']);
    }

    public function fetchActiveCampaignByUserOrders(int $userId)
    {
        return Campaign::with('orderProducts')
            ->whereHas('orderProducts', function($query) use ($userId) {
                $query->with('order')->wherehas('order', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                });
            })->where('start_date', '<=', Carbon::now()->toDateTime())
            ->where('end_date', '>=', Carbon::now()->toDateTime())
            ->where('status', 1)->get();
    }

    public function updateAddresses(array $data)
    {
        $userId = auth()->id();

        $billingAddress = array_merge(['address_type_id' => 3, 'user_id' => $userId, 'address2' => ''], $data['billing_address']);
        $shippingAddress = array_merge(['address_type_id' => 4, 'user_id' => $userId, 'address2' => ''], $data['shipping_address']);

        UserAddress::updateOrCreate(['address_type_id' => 3, 'user_id' => $userId],$billingAddress);
        UserAddress::updateOrCreate(['address_type_id' => 4, 'user_id' => $userId], $shippingAddress);
    }
}
