<?php

namespace App\Services;

use App\Http\Resources\Reviews\ReviewResource;
use App\Interfaces\ReviewServiceInterface;
use App\Models\Reviews;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewService implements ReviewServiceInterface
{
    public function create(array $data): ReviewResource
    {
        DB::beginTransaction();
        try {
            $userId = Auth::user()->id; 
            $eventId = $data['event_id'];

            $reviewExists = Reviews::where('user_id', $userId)
                    ->where('event_id', $eventId)
                    ->exists();

            if ($reviewExists ) {
                throw new \Exception('Você já avaliou este evento.');
            }

            $data['user_id'] = $userId;
            $review = Reviews::create($data);

            DB::commit();
            return new ReviewResource($review);

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Erro ao avaliar evento." . $e->getMessage(), 400);
        }
    }

    public function delete(int $id): bool
    {
        DB::beginTransaction();

        try {
            $userId = Auth::user()->id;
            $review = Reviews::where('id', $id)
                         ->where('user_id', $userId)
                         ->first();

            if (!$review) {
                throw new \Exception('Você não tem permissão para excluir esta avaliação.');
            }

            $review->delete();
            
            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Avaliação não excluída: " . $e->getMessage(), 400);
        }
    }
}