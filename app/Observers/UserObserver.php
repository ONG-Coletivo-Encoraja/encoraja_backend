<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        Log::create([
            'user_id' => Auth::id(),
            'table_name' => 'users',
            'column_name' => 'created',
            'old_value' => null,
            'new_value' => json_encode($user->getAttributes()),
        ]);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        $changes = $user->getChanges();

        foreach ($changes as $column => $newValue) {
            Log::create([
                'user_id' => Auth::id(),
                'table_name' => 'users',
                'column_name' => $column,
                'old_value' => $user->getOriginal($column),
                'new_value' => $newValue,
            ]);
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        Log::create([
            'user_id' => Auth::id(),
            'table_name' => 'users',
            'column_name' => 'deleted',
            'old_value' => json_encode($user->getOriginal()),
            'new_value' => null,
        ]);
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        Log::create([
            'user_id' => Auth::id(),
            'table_name' => 'users',
            'column_name' => 'restored',
            'old_value' => null,
            'new_value' => json_encode($user->getAttributes()),
        ]);
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        Log::create([
            'user_id' => Auth::id(),
            'table_name' => 'users',
            'column_name' => 'force_deleted',
            'old_value' => json_encode($user->getOriginal()),
            'new_value' => null,
        ]);
    }
}
