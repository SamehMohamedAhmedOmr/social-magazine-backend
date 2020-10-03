<?php

namespace Modules\Loyality\Entities;

use Illuminate\Database\Eloquent\Model;

class LoyalityProgram extends Model
{
    protected $table = 'loyality_programs';
    protected $fillable = [
        'price_to_points', 'point_to_price', 'max_allowed_points',
        'min_allowed_points', 'points_option', 'days_until_expiration',
        'days_until_refund', 'is_levels'
    ];
    protected $hidden = ['id', 'created_at', 'updated_at'];

    public function levels()
    {
        return $this->hasMany(ProgramLevel::class, 'loyality_program_id');
    }
}
