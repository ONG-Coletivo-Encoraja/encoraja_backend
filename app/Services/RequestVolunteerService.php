<?php

namespace App\Services;

use App\Http\Resources\RequestVolunteer\RequestVolunteerResource;
use App\Interfaces\RequestVolunteerServiceInterface;
use App\Models\RequestVolunteer;
use App\Models\User;
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

            $logged = User::find(Auth::user()->id);
            $logged->request_volunteer_id = $volunteerRequest->id;
            $logged->save();

            DB::commit();
            return new RequestVolunteerResource($volunteerRequest);

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Solicitação de voluntário não cadastrada: " . $e->getMessage(), 400);
        }
    }
}