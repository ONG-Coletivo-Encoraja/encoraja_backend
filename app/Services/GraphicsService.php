<?php

namespace App\Services;

use App\Interfaces\GraphicsServiceInterface;
use App\Models\Event;
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

    public function presentEvent(): JsonResponse
    {
        try {
            $events = Event::withCount(['inscriptions', 'inscriptions as presents_count' => function ($query) {
                $query->where('present', true);
            }])->get();

            $result = $events->map(function ($event) {
                return [
                    'event_name' => $event->name,
                    'total_inscriptions' => $event->inscriptions_count,
                    'total_presents' => $event->presents_count,
                ];
            });

            return response()->json($result);
            
        } catch (\Exception $e) {
            return response()->json(["error" => "Dados não enviados: " . $e->getMessage()], 400);
        }
    }
}