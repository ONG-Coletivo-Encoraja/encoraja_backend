<?php

namespace App\Services;

use App\Http\Resources\Reviews\ReviewResource;
use App\Interfaces\ReviewServiceInterface;
use App\Models\Event;
use App\Models\Inscription;
use App\Models\Reviews;
use Illuminate\Pagination\LengthAwarePaginator;
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

            $event = Event::find($eventId);

            if ($event->status != 'finished') throw new \Exception('Apenas eventos finalizados podem ser avaliados.', 404);
            
            $inscription = Inscription::where('user_id', $userId)
                    ->where('event_id', $eventId)
                    ->where('present', true)
                    ->exists();

            if (!$inscription) throw new \Exception('Você não estava presente no evento.', 404);

            $reviewExists = Reviews::where('user_id', $userId)
                    ->where('event_id', $eventId)
                    ->exists();

            if ($reviewExists) throw new \Exception('Você já avaliou este evento.', 404);

            $data['user_id'] = $userId;
            $review = Reviews::create($data);

            DB::commit();
            return new ReviewResource($review);

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Erro ao avaliar evento: " . $e->getMessage(), 400);
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

    public function getByEvent(int $id): LengthAwarePaginator
    {
        try {
            $reviews = Reviews::where('event_id', $id)->paginate(5);

            if ($reviews->isEmpty())
                throw new \Exception('Evento sem avaliações.');

            $reviews->transform(function ($review) {
                return new ReviewResource($review);
            });

            return $reviews;

        } catch (\Exception $e) {
            throw new \Exception("Erro ao encontrar avaliações: " . $e->getMessage(), 400);
        }
    }

    public function getById(int $id): ReviewResource {
        try {
            $review = Reviews::find($id);

            if($review == null)
                throw new \Exception('Avaliação não encontrada.');

            return new ReviewResource($review);

        } catch (\Exception $e) {
            throw new \Exception("Erro ao encontrar avaliação. " . $e->getMessage(), 400);
        }
    }
}