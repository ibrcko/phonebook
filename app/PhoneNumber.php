<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhoneNumber extends Model
{
    protected $fillable = [
        'number', 'name', 'label', 'contact_id',
    ];

    protected $table = 'numbers';

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
