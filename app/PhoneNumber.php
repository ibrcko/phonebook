<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PhoneNumber
 * @package App
 */
class PhoneNumber extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'number', 'name', 'label', 'contact_id',
    ];

    /**
     * @var string
     */
    protected $table = 'numbers';


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
