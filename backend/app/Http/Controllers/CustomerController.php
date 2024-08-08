<?php

namespace App\Http\Controllers;

use App\Models\Customer;

class CustomerController extends Controller
{
    public function getAll()
    {
        return array_map(function (array $customer) {
            return [
                'uuid' => $customer['uuid'],
                'customer_name' => $customer['name'],
                'customer_email' => $customer['email'],
                'orders_count' => count($customer['orders']),
            ];
        }, Customer::with(['orders'])->get()->toArray());
    }
}
