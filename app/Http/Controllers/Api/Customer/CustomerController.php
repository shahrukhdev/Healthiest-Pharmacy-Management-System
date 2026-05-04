<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicineCategoryRequest;
use App\Http\Requests\NearestMedicineRequest;
use App\Http\Requests\SearchMedicineRequest;
use App\Libraries\Customer\Medicine;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct(private Medicine $medicine)
    {
        //
    }

    public function defaultMedicines(Request $request)
    {
        try {
            $medicines = $this->medicine->getDefaultMedicines();

            return response()->json([
                'status' => true,
                'message' => count($medicines) ? 'List of medicines.' : 'No medicines available.',
                'medicines' => count($medicines) ? $medicines : []
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }

    public function searchMedicines(SearchMedicineRequest $request)
    {
        try {
            $medicines = $this->medicine->searchMedicines($request->all());

            return response()->json([
                'status' => true,
                'message' => count($medicines) ? 'Get searched medicines.' : 'No medicines found based on search result.',
                'medicines' => count($medicines) ? $medicines : []
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }

    public function categoryMedicines(MedicineCategoryRequest $request)
    {
        try {
            $medicines = $this->medicine->getCategoryMedicines($request->all());
            
            return response()->json([
                'status' => true,
                'message' => count($medicines) ? 'Get category medicines.' : 'No medicines found based on this category.',
                'medicines' => count($medicines) ? $medicines : []
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }

    public function nearestMedicines(NearestMedicineRequest $request)
    {
        try {
            $medicines = $this->medicine->getNearestMedicines($request->all());
            
            return response()->json([
                'status' => true,
                'message' => count($medicines) ? 'Get medicines of nearest pharmacies.' : 'No medicines found near your location.',
                'medicines' => count($medicines) ? $medicines : []
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }
}