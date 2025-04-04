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
        return $this->hasMany(UserPlant::class, 'user_id');
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
        // Customize the reset URL to point to your frontend
        //TODO put web url in env
        $url = 'http://127.0.0.1:8000/reset-password?token=' . $token . '&email=' . $this->email;

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
            return true; // Admins can modify these models
        }
    
        if ($model instanceof User) {
            return $this->id === $model->id; // Users can modify their own profile
        }
    
        if ($model instanceof Article) {
            return $this->id === $model->author_id; // Users can modify their own articles
        }
    
        if ($model instanceof UserPlant) {
            return $this->id === $model->user_id; // Users can modify their own plants
        }
    
        if ($model instanceof Listing) {
            $userPlant = $model->userPlant; // This retrieves the related UserPlant model
            return $userPlant && $this->id == $model->userPlant->user->id; // Check if the user owns the UserPlant
        }
    
        return false; // Default deny
    }
}