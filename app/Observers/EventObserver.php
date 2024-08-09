<?php

namespace App\Observers;

use App\Models\Event;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class EventObserver
{
    /**
     * Handle the Event "created" event.
     */
    public function created(Event $event): void
    {
        Log::create([
            'user_id' => Auth::id(),
            'table_name' => 'events',
            'column_name' => 'created',
            'old_value' => null,
            'new_value' => json_encode($event->getAttributes()),
        ]);
    }

    /**
     * Handle the Event "updated" event.
     */
    public function updated(Event $event): void
    {
        $changes = $event->getChanges();

        foreach ($changes as $column => $newValue) {
            Log::create([
                'user_id' => Auth::id(),
                'table_name' => 'events',
                'column_name' => $column,
                'old_value' => $event->getOriginal($column),
                'new_value' => $newValue,
            ]);
        }
    }

    /**
     * Handle the Event "deleted" event.
     */
    public function deleted(Event $event): void
    {
        Log::create([
            'user_id' => Auth::id(),
            'table_name' => 'events',
            'column_name' => 'deleted',
            'old_value' => json_encode($event->getOriginal()),
            'new_value' => null,
        ]);
    }

    /**
     * Handle the Event "restored" event.
     */
    public function restored(Event $event): void
    {
        Log::create([
            'user_id' => Auth::id(),
            'table_name' => 'events',
            'column_name' => 'restored',
            'old_value' => null,
            'new_value' => json_encode($event->getAttributes()),
        ]);
    }

    /**
     * Handle the Event "force deleted" event.
     */
    public function forceDeleted(Event $event): void
    {
        Log::create([
            'user_id' => Auth::id(),
            'table_name' => 'events',
            'column_name' => 'force_deleted',
            'old_value' => json_encode($event->getOriginal()),
            'new_value' => null,
        ]);
    }
}
