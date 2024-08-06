<?php

namespace App\Services;

use App\Http\Resources\User\ProfileResouce;
use App\Models\User;
use App\Interfaces\UserServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\User\UserResource;
use App\Models\Address;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;

class UserService implements UserServiceInterface
{
    public function getAllUsers(): LengthAwarePaginator
    {
        try {
            $users = User::orderBy('id', 'desc')->paginate(5);

            $userResources = $users->getCollection()->transform(function ($user) {
                return new UserResource($user);
            });

            return new LengthAwarePaginator($userResources, $users->total(), $users->perPage(), $users->currentPage(), [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'pageName' => $users->getPageName(),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Erro ao encontrar usuários." . $e->getMessage(), 400);
        }
        
    }
    
    public function getUserById(int $id): ProfileResouce
    {
        try {
            $user = User::findOrFail($id);
            return new ProfileResouce($user);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Erro ao encontrar usuário." . $e->getMessage(), 400);
        }
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
                'phone' => $data['phone'],
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

    public function updateLoggedUser(array $data): ProfileResouce
    {
        DB::beginTransaction();

        $logged = Auth::user()->id;

        try {
            $user = User::findOrFail($logged);
            $user->update($data);

            $address = $user->addresses->first();
            $address = Address::findOrFail($address->id);
            $address->update($data);

            // só o adm altera autorização
            if(Auth::user()->permissions->first()->type == 'administrator') {
                $permission = $user->permissions->first();
                $permission = Permission::findOrFail($permission->id);
                $permission->update($data);
            }
            
            DB::commit();
            
            return new ProfileResouce($user);

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Usuário não editado!", 400);
        }
    }

    public function updateUser(int $id, array $data) {
        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);

            $permission = $user->permissions->first();
            $permission = Permission::findOrFail($permission->id);
            $permission->update($data);
            
            DB::commit();
            
            return new ProfileResouce($user);

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

    public function me(): ProfileResouce
    {
        try {
            $user = Auth::user();
        
            return new ProfileResouce($user);
        } catch (\Exception $e) {
            throw new \Exception("Usuário não encontrado!", 400);
        }

    }
}