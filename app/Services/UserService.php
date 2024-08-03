<?php

namespace App\Services;

use App\Models\User;
use App\Interfaces\UserServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\User\UserResource;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class UserService implements UserServiceInterface
{
    public function getAllUsers(): LengthAwarePaginator
    {
        $users = User::orderBy('id', 'desc')->paginate(5);

        $userResources = $users->getCollection()->transform(function ($user) {
            return new UserResource($user);
        });

        return new LengthAwarePaginator($userResources, $users->total(), $users->perPage(), $users->currentPage(), [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'pageName' => $users->getPageName(),
        ]);
    }
    
    public function getUserById(int $id): UserResource
    {
        $user = User::findOrFail($id);
        return new UserResource($user);
    }

    public function createUser(array $data): UserResource
    {
        DB::beginTransaction();
        
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'cpf' => $data['cpf'],
                'date_birthday' => $data['date_birthday'],
                'race' => $data['race'],
                'gender' => $data['gender'],
                'image_term' => $data['image_term'] ?? false,
                'data_term' => $data['data_term'] ?? false,
            ]);
            
            $user->permissions()->create([
                'type' => 'beneficiary'
            ]);

            $user->addresses()->create([
                'street' => $data['street'],
                'number' => $data['number'],
                'neighbourhood' => $data['neighbourhood'],
                'city' => $data['city'],
                'zip_code' => $data['zip_code']
            ]);

            DB::commit();

            return new UserResource($user);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Usuário não cadastrado: " . $e->getMessage(), 400);
        }
    }


    public function updateUser(int $id, array $data): UserResource
    {
        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);
            $user->update($data);

            $address = $user->addresses->first();
            $address = Address::findOrFail($address->id);
            $address->update($data);

            DB::commit();
            
            return new UserResource($user);

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Usuário não editado!", 400);
        }
    }

    public function deleteUser(int $id): void
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
        } catch (\Exception $e) {
            throw new \Exception("Usuário não deletado!", 400);
        }
    }
}