<?php

namespace App\Http\Controllers;

use App\Services\AddressTypeService;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    private AddressTypeService $addressTypeService;

    public function __construct(
        AddressTypeService $addressTypeService
    ) {
        $this->addressTypeService = $addressTypeService;
    }

    public function fetchAddressTypeList()
    {
        return response()->json([
            'data' => $this->addressTypeService->fetchActive([])
        ]);
    }
}
