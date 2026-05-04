<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Rider;
use Illuminate\Http\Request;

class RiderController extends Controller
{
    public function getRiders(Request $request)
    {
        try {
            $riders = Rider::all();

            return response()->json([
                'status' => true,
                'message' => 'List of Riders.',
                'riders' => $riders
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }
    
}
