<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
//use App\Interfaces\MustVerifyMobile as IMustVerifyMobile;

class User extends Authenticatable 
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens , HasFactory, Notifiable , HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        "last_name" , 
        'email',
        'password',
        "phone" , 
        "profile_image" ,
        "fcm_token"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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


/*     public function getProfileImageAttribute() 
    {
        if($this->profile_image)
        {
            return url("storage/" . $this->profile_image);
        }
    } */

    public function clinic_center()
    {
        return $this->hasOne(ClinicCenter::class);
    }

    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }



}
