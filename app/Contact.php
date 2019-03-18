<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'profile_photo', 'email', 'favourite'
    ];

    //protected $table = 'contacts';
}
