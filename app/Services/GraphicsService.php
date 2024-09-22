<?php

namespace App\Services;

use App\Interfaces\GraphicsServiceInterface;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class GraphicsService implements GraphicsServiceInterface {

    public function ethnicityChart() : JsonResponse
    {
        try {
            $ethnicityCounts = User::select('ethnicity')
                ->where('status', 'active')
                ->groupBy('ethnicity')
                ->selectRaw('count(*) as count')
                ->get()
                ->pluck('count', 'ethnicity')
                ->toArray();
    
            $possibleEthnicities = ['white', 'black', 'yellow', 'mixed', 'prefer not say'];
            $result = array_fill_keys($possibleEthnicities, 0);
            
            foreach ($ethnicityCounts as $ethnicity => $count) {
                $result[$ethnicity] = $count;
            }
    
            return response()->json($result);
            
        } catch (\Exception $e) {
            throw new \Exception("Dados não enviados: " . $e->getMessage(), 400);
        }
    }
}