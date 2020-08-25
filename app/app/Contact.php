<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'team_id', 'name', 'phone','email','sticky_phone_number_id'
    ];

    /**
     * Get the custom_attributes for the contact.
     */
    public function custom_attributes()
    {
        return $this->hasMany('App\CustomAttribute');
    }
}
