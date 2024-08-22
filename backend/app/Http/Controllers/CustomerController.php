<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CustomerController extends Controller
{
    public function getAll(): AnonymousResourceCollection
    {
        $customers = Customer::with(['orders'])
            ->withCount(['orders'])
            ->get();

        return CustomerResource::collection($customers);
    }
}
