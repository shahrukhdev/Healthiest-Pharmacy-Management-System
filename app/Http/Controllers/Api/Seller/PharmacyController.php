<?php

namespace App\Http\Controllers\Api\Seller;

use App\Models\Order;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PharmacyRequest;

class PharmacyController extends Controller
{
    public function list(Request $request)
    {
        try {
            $pharmacies = Pharmacy::where('user_id', $request->user()->id)->get();

            return response()->json([
                'status' => true,
                'message' => 'List of Pharmacies.',
                'pharmacies' => $pharmacies,
            ]);

        } catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }

    public function add(PharmacyRequest $request)
    {
        try {
            $pharmacy = Pharmacy::create([
                'name' => $request->name,
                'contact_number' => $request->contact_number,
                'location' => $request->location,
                'user_id' => $request->user()->id,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Pharmacy successfully added.',
                'pharmacy' => $pharmacy
            ]);

        } catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }

    public function edit(Request $request, $id)
    {
        try {
            $pharmacy = Pharmacy::findOrFail($id);

            return response()->json([
                'status' => true,
                'message' => 'Get pharmacy record.',
                'pharmacy' => $pharmacy,
            ]);

        } catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }

    public function update(PharmacyRequest $request, $id)
    {
        try {
            $pharmacy = Pharmacy::findOrFail($id);

            $pharmacy->update([
                'name' => $request->name,
                'contact_number' => $request->contact_number,
                'location' => $request->location,
            ]);
            
            return response()->json([
                'status' => true,
                'message' => 'Pharmacy successfully updated.',
                'pharmacy' => $pharmacy
            ]);

        } catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $pharmacy = Pharmacy::findOrFail($id);

            $pharmacy?->medicines()->delete();
            $pharmacy->delete();

            return response()->json([
                'status' => true,
                'message' => 'Pharmacy successfully deleted.',
            ]);

        } catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }

    public function orders(Request $request)
    {
        try {
            $orders = Order::where('pharmacy_id', $request->pharmacy_id)->get();

            return response()->json([
                'status' => true,
                'message' => 'List of orders.',
                'orders' => $orders
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }
}