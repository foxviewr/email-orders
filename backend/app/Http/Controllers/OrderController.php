<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;

class OrderController extends Controller
{
    public function getAll()
    {
        return array_map(function (array $order) {
            return [
                'uuid' => $order['uuid'],
                'number' => $order['number'],
                'customer_name' => $order['customer']['name'],
                'customer_email' => $order['customer']['email'],
                'emails_count' => count($order['emails']),
            ];
        }, Order
            ::with(['customer', 'emails'])
            ->get()
            ->toArray()
        );
    }

    public function getByCustomerUuid(Request $request)
    {
        $validated = $request->validate([
            'customerUuid' => 'required|string',
        ]);

        if (empty($validated['uuid'])) {
            return response()->json(['message' => 'customerUuid is missing or invalid'], 400);
        }

        $customer = Customer
            ::where('uuid', $validated['customerUuid'])
            ->first()
        ;

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        return array_map(function (array $order) {
            return [
                'uuid' => $order['uuid'],
                'number' => $order['number'],
                'customer_name' => $order['customer']['name'],
                'customer_email' => $order['customer']['email'],
                'emails_count' => count($order['emails']),
            ];
        }, Order::with(['customer', 'emails'])->where('customer_uuid', $customer->uuid)->get()->toArray());
    }

    public function getByUuid(string $uuid)
    {
        $order = Order
            ::with(['customer', 'emails'])
            ->where('uuid', $uuid)
            ->first()
            ->toArray()
        ;

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return [
            'uuid' => $order['uuid'],
            'number' => $order['number'],
            'customer_name' => $order['customer']['name'],
            'customer_email' => $order['customer']['email'],
            'emails' => $order['emails'],
            'emails_count' => count($order['emails']),
        ];
    }
}
