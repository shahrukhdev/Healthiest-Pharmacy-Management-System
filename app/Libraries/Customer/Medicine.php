<?php

namespace App\Libraries\Customer;

use App\Models\Pharmacy;
use Illuminate\Database\Eloquent\Collection;


class Medicine
{
    public function getDefaultMedicines(): Collection
    {
        return \App\Models\Medicine::where('quantity', '>=', 1)->where('status', 1)->limit(20)->orderBy('name')->get();
    }


    // Filters

    public function searchMedicines($data): Collection
    {
        $medicines = \App\Models\Medicine::where("name", "LIKE", "%{$data['text']}%")
            ->where('status', 1)
            ->where('quantity', '>=', 1)
            ->limit(20)->orderBy('name')->get();

        if ($medicines->isEmpty()) {
            $medicine = \App\Models\Medicine::where("name", $data['text'])->first();
            
            if ($medicine) {
                $medicines = \App\Models\Medicine::where("formula", $medicine->formula)
                    ->where('status', 1)
                    ->where('quantity', '>=', 1)
                    ->limit(20)
                    ->orderBy('name')
                    ->get();
            }
        }
                    
        return $medicines;
    }

    public function getCategoryMedicines($data): Collection
    {
        return \App\Models\Medicine::where('category_id', $data['category_id'])
            ->where('status', 1)
            ->where('quantity', '>=', 1)
            ->limit(20)->orderBy('name')->get();                
    }
    
    public function getNearestMedicines($data): array
    {
        return self::searchPharmaciesWithCoordinates($data);   
    }


    
    public static function searchPharmaciesWithCoordinates($data): array
    {
        $userLatitude = $data['latitude'];
        $userLongitude = $data['longitude'];
        $radius = 3000;

        // Calculate the bounding box coordinates
        $latDelta = $radius / 111.2;                                        // 1 degree of latitude is approximately 111.2 kilometers
        $lonDelta = $radius / (111.2 * cos(deg2rad($userLatitude)));

        $minLat = $userLatitude - $latDelta;
        $maxLat = $userLatitude + $latDelta;
        $minLon = $userLongitude - $lonDelta;
        $maxLon = $userLongitude + $lonDelta;

        $pharmacies = Pharmacy::whereBetween('latitude', [$minLat, $maxLat])
            ->whereBetween('longitude', [$minLon, $maxLon])
            ->get();

        $medicines = [];

        foreach ($pharmacies as $pharmacy) {
            $medicines = array_merge($medicines, $pharmacy->medicines()->where('status', 1)->where('quantity', '>=', 1)->get());
        }

        return $medicines;
    }



}