<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'profile_photo', 'email', 'favourite'
    ];

    public function phoneNumbers()
    {
        return $this->hasMany(PhoneNumber::class, 'contact_id');
    }
}
