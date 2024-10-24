<?php

namespace App\Services;

use App\Http\Resources\RequestVolunteer\RequestVolunteerResource;
use App\Interfaces\RequestVolunteerServiceInterface;
use App\Models\RequestVolunteer;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequestVolunteerService implements RequestVolunteerServiceInterface
{
    public function create(array $data): RequestVolunteerResource
    {
        DB::beginTransaction();

        try {
            $user = User::find(Auth::user()->id);
            
            if ($user->request_volunteer_id) {
                $existingRequest = RequestVolunteer::find($user->request_volunteer_id);

                if ($existingRequest && $existingRequest->status === 'rejected') {
                    $existingRequest->delete();
                } else {
                    throw new \Exception("Você já tem uma solicitação de voluntário pendente ou aceita.");
                }
            }

            $data['status'] = 'pending';
            $volunteerRequest = RequestVolunteer::create($data);

            $user->request_volunteer_id = $volunteerRequest->id;
            $user->save();

            DB::commit();
            return new RequestVolunteerResource($volunteerRequest);

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Solicitação de voluntário não cadastrada: " . $e->getMessage(), 400);
        }
    }

    public function listAllRequest($status = null): LengthAwarePaginator
    {
        try {
            $query = RequestVolunteer::query();

            if ($status) {
                $query->where('status', $status);
            }

            $requestsWithVolunteerRequest = $query->paginate(6);

            $requestsWithVolunteerRequest->transform(function ($request) {
                return new RequestVolunteerResource($request);
            });

            return $requestsWithVolunteerRequest;

        } catch (\Exception $e) {
            throw new \Exception("Erro ao encontrar todas as solicitações de voluntário." . $e->getMessage(), 400);
        }
    }


    public function update(array $data): RequestVolunteerResource
    {
        DB::beginTransaction();

        try {
            $user = User::find(Auth::user()->id);

            if (!$user->request_volunteer_id) {
                throw new \Exception("Nenhuma solicitação de voluntário encontrada para o usuário.");
            }

            $existingRequest = RequestVolunteer::find($user->request_volunteer_id);

            if (!$existingRequest) {
                throw new \Exception("Solicitação de voluntário não encontrada.");
            }

            if ($existingRequest->status !== 'accepted') {
                throw new \Exception("Sua solicitação ainda não foi aceita, não é possível atualizá-la.");
            }

            
            $existingRequest->update($data);

            DB::commit();
            return new RequestVolunteerResource($existingRequest);

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Solicitação de voluntário não atualizada: " . $e->getMessage(), 400);
        }

    }

    public function updateStatus(int $id, array $data): RequestVolunteerResource
    {
        DB::beginTransaction();

        try {
            $existingRequest = RequestVolunteer::find($id);

            if (!$existingRequest) {
                throw new \Exception("Solicitação de voluntário não encontrada.");
            }

            $existingRequest->update($data);

            DB::commit();
            return new RequestVolunteerResource($existingRequest);

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Solicitação de voluntário não atualizada: " . $e->getMessage(), 400);
        }
    }

    public function getById(int $id): RequestVolunteerResource
    {
        try {
            $request = RequestVolunteer::find($id);

            if(!$request) throw new \Exception("Solicitação não encontrada.", 400);

            return new RequestVolunteerResource($request);

        } catch (\Exception $e) {
            throw new \Exception("Erro ao encontrar todas as solicitações de voluntário." . $e->getMessage(), 400);
        }
    }
}