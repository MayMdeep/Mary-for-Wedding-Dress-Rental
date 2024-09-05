<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dress extends Model
{
    protected $fillable = [
        'name', 'image', 'description', 'quantity', 'rental_price'
    ];

    // Define the relationship to the Specification model
    public function specifications()
    {
        return $this->belongsToMany(Specification::class, 'dress_specification')
                    ->withPivot('option_id');
    }

    // Define the relationship to the SpecificationOption model
    public function options()
    {
        return $this->belongsToMany(SpecificationOption::class, 'dress_specification', 'dress_id', 'option_id')
                    ->withPivot('specification_id');
    }
}

