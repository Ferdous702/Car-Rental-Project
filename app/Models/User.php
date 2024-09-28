<?php
namespace App\Models;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
class User extends Authenticatable implements AuthenticatableContract
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];


    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}
