<?php

namespace App\Models;

use App\Models\SystemConfiguration\AccessManagement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class CredentialModel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'credentials';
    protected $fillable = [
        'email',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'email', 'email');
    }
}
