<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomAttribute extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'custom_attributes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'contact_id', 'key', 'value'
    ];


    /**
     * Get the contact that owns the custom_attribute.
     */
    public function contact()
    {
        return $this->belongsTo('App\Contact');
    }
}
