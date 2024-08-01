<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'cpf',
        'date_birthday',
        'race',
        'gender',
        'image_term',
        'data_term',
        'beneficiary',
        'availability',
        'course_experience',
        'how_know',
        'expectations',
        'address_id',
        'phone_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        // 'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function phoneNumbers()
    {
        return $this->hasMany(PhoneNumber::class);
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    public function relatesEvents()
    {
        return $this->hasMany(RelatesEvent::class);
    }

    public function reviews()
    {
        return $this->hasMany(Reviews::class);
    }
}
