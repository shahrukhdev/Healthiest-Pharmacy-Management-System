<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicineRequest;
use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function list(Request $request, $id)
    {
        try {
            $medicines = Medicine::where('pharmacy_id', $id)->get();

            return response()->json([
                'status' => true,
                'message' => 'List of Medicines.',
                'medicines' => $medicines
            ]);

        } catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }

    public function add(MedicineRequest $request)
    {
        try {
            $medicine = Medicine::create([
                'name' => $request->name,
                'dosage' => $request->dosage,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'status' => $request->status == true ? 1 : 0,
                'formula' => $request->formula,
                'image' => $request->hasFile('image') ? '/storage/' . $request->file('image')->store('medicines', 'public') : '',
                'category_id' => $request->category_id,
                'pharmacy_id' => $request->pharmacy_id
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Medicine successfully added.',
                'medicine' => $medicine
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
            $medicine = Medicine::findOrFail($id);

            return response()->json([
                'status' => true,
                'message' => 'Get medicine record.',
                'medicine' => $medicine,
            ]);

        } catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }

    public function update(MedicineRequest $request, $id)
    {
        try {
            $medicine = Medicine::findOrFail($id);

            $medicine->update([
                'name' => $request->name,
                'dosage' => $request->dosage,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'status' => $request->status == true ? 1 : 0,
                'formula' => $request->formula,
                'image' => $request->hasFile('image') ? '/storage/' . $request->file('image')->store('medicines', 'public') : $medicine->image,
                'category_id' => $request->category_id,
                'pharmacy_id' => $request->pharmacy_id
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Medicine successfully updated.',
                'medicine' => $medicine
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }

    public function editStatus(Request $request)
    {
        try {
            $medicine = Medicine::findOrFail($request->medicine_id);

            $medicine->update([
                'status' => $request->status ? 1 : 0,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Status successfully updated.',
                'medicine' => $medicine
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
            $medicine = Medicine::findOrFail($id);

            $medicine->delete();

            return response()->json([
                'status' => true,
                'message' => 'Medicine successfully deleted.',
            ]);

        } catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }

    
}