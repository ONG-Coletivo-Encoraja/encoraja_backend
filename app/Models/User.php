<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, SoftDeletes;

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
        'phone',
        // 'beneficiary',
        // 'availability',
        // 'course_experience',
        // 'how_know',
        // 'expectations',
        'request_volunteer_id',
    ];

    

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'id'
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

    public function requestVolunteer()
    {
        return $this->belongsTo(RequestVolunteer::class);
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
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

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'permission' => $this->permissions->first()
        ];
    }
}
