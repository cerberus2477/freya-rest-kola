<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\CustomResetPassword;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = ['username', 'email', 'city', 'birthdate', 'password', 'role_id', 'picture', 'description'];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function userPlants()
    {
        return $this->hasMany(UserPlant::class);
    }



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'birtdate',
        'created_at',
        'updated_at',
        'email_verified_at',
        'deleted_at',
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

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $url = env('FRONTEND_URL') .'/reset-password?token=' . $token . '&email=' . $this->email;

        // Send the notification with the custom URL
        $this->notify(new CustomResetPassword($url));
    }
    /**
     * Check if the user has a specific token.
     *
     * @param  string  $token
     * @return bool
     */
    public function canModify($model): bool
    {
        if ($this->tokenCan('admin')) {
            return true;
        }
        else if ($model instanceof User) {
            return $this->id === $model->id;
        }
        else if ($model instanceof Article) {
            return $this->id === $model->author_id;
        }
        else if ($model instanceof UserPlant) {
            return $this->id === $model->user_id;
        }
        else if ($model instanceof Listing) {
            if (!$model->relationLoaded('userPlant')) {
                $model->load(['userPlant.user']);
            }

            $userPlant = $model->userPlant;
            return $userPlant && $this->canModify($userPlant);
        }
    
        return false; // Default deny
    }
}