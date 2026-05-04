<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private \App\Libraries\Customer\Order $order)
    {
        //
    }

    public function create(OrderRequest $request)
    {
        try {
            $order = Order::create([
                'user_id' => $request->user()->id,
                'pharmacy_id' => $request->pharmacy_id,
                'price' => $request->price,
                'address' => $request->address,
                'status' =>  'Placed',
                'lead_time' => 40,
                'description' => 'The pharmacy is preparing and packing your medicine.'
            ]);

            if (!$order->exists()) {
                return response()->json(['status' => true, 'message' => 'Oops something went wrong! Could not place order.'], 422);
            }

            $order->medicines()->attach($request->medicine_ids);

            return response()->json([
                'status' => true,
                'message' => 'Your order has been successfully placed.',
                'order' => $order
            ]);

        } catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);            

            $data = $this->order->updateOrderStatus($order);
        
            $order->update([
                'status' => $data['status'],
                'lead_time' => $data['lead_time'],
                'description' => $data['description'],
            ]);

            return response()->json([
                'status' => true,
                'message' => $order->status == 'Delivered' ? 'Your medicine has been successfully delivered.' : 'Updated order status.',
                'order' => $order
            ]);

        } catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
