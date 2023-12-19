<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRoles;

    protected $guard_name = "api";

    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts() {
        return $this->hasMany(Post::class);
    }

    public function upVotes() {
        return $this->hasMany(UpVote::class);
    }

    public function media() {
        return $this->morphToMany(Media::class, 'model', 'model_has_media');
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function findForPassport(string $username): User
    {
        return $this
        ->where('phone', $username)
        ->orWhere('email', $username)
        ->first();
    }

    public function validateForPassportPasswordGrant(string $password): bool
    {
        return (
            Hash::check($password, $this->verify_code)
            ||
            Hash::check($password, $this->password)
        );
    }
}
