<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;

class Accounts extends Model implements JWTSubject, AuthenticatableContract
{
    use HasFactory;
    use Authenticatable;
    use Notifiable;

    protected $fillable = ['user_id', 'username', 'password', 'remember_token'];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getEmailForVerification()
    {
        return $this->email;
    }
}
