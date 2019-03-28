<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Contact
 * @package App
 */
class Contact extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'profile_photo', 'email', 'favourite'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function phoneNumbers()
    {
        return $this->hasMany(PhoneNumber::class, 'contact_id');
    }
}
