<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;
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


    public function getProfileImageAttribute($value): ?string
    {
        $value = $this->normalizeProfileImagePath($value);

        if ($value === null) {
            return null;
        }

        return url('storage/'.$value);
    }

    public function setProfileImageAttribute($value): void
    {
        $this->attributes['profile_image'] = $this->normalizeProfileImagePath($value);
    }

    private function normalizeProfileImagePath($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        if (Str::startsWith($value, ['http://', 'https://'])) {
            $path = parse_url($value, PHP_URL_PATH) ?: '';

            if (! Str::contains($path, '/storage/')) {
                return $value;
            }

            $value = Str::after($path, '/storage/');
        }

        $value = ltrim($value, '/');

        if (Str::startsWith($value, 'storage/')) {
            $value = Str::after($value, 'storage/');
        }

        return $value;
    }

    public function clinic_center()
    {
        return $this->hasOne(ClinicCenter::class, 'user_id');
    }

    public function secretaryCenters()
    {
        return $this->belongsToMany(
            ClinicCenter::class,
            'clinic_center_secretaries',
            'user_id',
            'clinic_center_id'
        )->withTimestamps();
    }

    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    public function nutritionPlans()
    {
        return $this->hasMany(NutritionPlan::class);
    }

    public function dashboardCenter(): ?ClinicCenter
    {
        if ($this->hasRole('admin')) {
            return $this->clinic_center;
        }

        if ($this->hasRole('secretary')) {
            return $this->secretaryCenters()->first();
        }

        return $this->clinic_center;
    }

}
