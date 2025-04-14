<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Business extends Model
{
    // enable use of factory module
    use HasFactory, HasApiTokens;

    // specify the table name
    protected $table = 'businesses';


    /* The `protected ` property in a Laravel Eloquent model specifies which attributes are
    mass assignable. This means that the attributes listed in the `` array can be mass
    assigned using methods like `create` or `update` on the model. */
    protected $fillable = [
        'name',
        'description',
        'address',
        'phone_number',
        'email',
        'website',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
