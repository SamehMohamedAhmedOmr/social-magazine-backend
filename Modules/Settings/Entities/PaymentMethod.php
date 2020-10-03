<?php

namespace Modules\Settings\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Scopes\CountryScope;

class PaymentMethod extends Model
{
    use SoftDeletes;
    protected $table = 'payment_methods';

    protected $fillable = [
        'key',
        'is_active',
        'country_id'
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CountryScope);
    }

    public function languages()
    {
        return $this->belongsToMany(
            Language::class,
            'payment_methods_languages',
            'payment_method_id',
            'language_id'
        )
            ->using(PaymentMethodLanguages::class)->withPivot('name');
    }

    public function currentLanguage()
    {
        return $this->hasOne(PaymentMethodLanguages::class, 'payment_method_id')
            ->where('language_id', \Session::get('language_id'));
    }

}
