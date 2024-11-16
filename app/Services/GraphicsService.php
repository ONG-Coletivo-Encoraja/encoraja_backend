<?php

namespace App\Services;

use App\Interfaces\GraphicsServiceInterface;
use App\Models\Complaince;
use App\Models\Event;
use App\Models\Inscription;
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

            $ethnicityTranslation = [
                'white' => 'branco',
                'black' => 'preto',
                'yellow' => 'amarelo',
                'mixed' => 'pardo',
                'prefer not say' => 'prefere não dizer',
            ];

            $result = array_fill_keys(array_values($ethnicityTranslation), 0);

            foreach ($ethnicityCounts as $ethnicity => $count) {
                if (isset($ethnicityTranslation[$ethnicity])) {
                    $result[$ethnicityTranslation[$ethnicity]] = $count;
                }
            }

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json(["erro" => "Dados não enviados: " . $e->getMessage()], 400);
        }
    }

    public function presentEventChart(): JsonResponse
    {
        try {
            $events = Event::withCount(['inscriptions', 'inscriptions as presents_count' => function ($query) {
                $query->where('present', true);
            }])
            ->where('status', 'finished')
            ->latest()
            ->take(10)
            ->get();

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

    public function ratingsChart(): JsonResponse
    {
        try {
            $events = Event::withAvg('reviews', 'rating')
                    ->where('status', 'finished')
                    ->latest()
                    ->take(10)
                    ->get();

            $result = $events->map(function ($event) {
                return [
                    'event_name' => $event->name,
                    'average_rating' => $event->reviews_avg_rating ?? 0, 
                ];
            });

            return response()->json($result);
            
        } catch (\Exception $e) {
            return response()->json(["error" => "Dados não enviados: " . $e->getMessage()], 400);
        }
    }

    public function complianceChart(): JsonResponse
    {
        try {
            $data = Complaince::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total')
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy('month')
                ->orderBy('month')
                ->get();
    
            $result = [];
            for ($i = 0; $i < 12; $i++) {
                $month = now()->subMonths(11 - $i)->format('Y-m');
                $total = $data->firstWhere('month', $month)?->total ?? 0;
                $result[$month] = $total;
            }
    
            return response()->json($result);
            
        } catch (\Exception $e) {
            return response()->json(["error" => "Dados não enviados: " . $e->getMessage()], 400);
        }
    }

    public function ageGroupChart(): JsonResponse
    {
        try {
            $ageGroups = [
                '16-26' => 0,
                '27-36' => 0,
                '37-46' => 0,
                '47 ou mais' => 0,
            ];
    
            $users = User::all();
    
            foreach ($users as $user) {
                $age = \Carbon\Carbon::parse($user->date_birthday)->age;
    
                if ($age >= 16 && $age <= 26) {
                    $ageGroups['16-26']++;
                } elseif ($age >= 27 && $age <= 36) {
                    $ageGroups['27-36']++;
                } elseif ($age >= 37 && $age <= 46) {
                    $ageGroups['37-46']++;
                } elseif ($age >= 47) {
                    $ageGroups['47 ou mais']++;
                }
            }
    
            return response()->json($ageGroups);
            
        } catch (\Exception $e) {
            return response()->json(["error" => "Dados não enviados: " . $e->getMessage()], 400);
        }
    }

    public function participationChart(): JsonResponse
    {
        try {
            $data = Inscription::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total_present')
                ->where('present', true)
                ->groupBy('month')
                ->orderBy('month')
                ->get();
    
            $result = [];
            for ($i = 0; $i < 12; $i++) {
                $month = now()->subMonths(11 - $i)->format('Y-m');
                $total = $data->firstWhere('month', $month)?->total_present ?? 0;
                $result[$month] = $total;
            }
    
            return response()->json($result);
            
        } catch (\Exception $e) {
            return response()->json(["error" => "Dados não enviados: " . $e->getMessage()], 400);
        }
    }
}