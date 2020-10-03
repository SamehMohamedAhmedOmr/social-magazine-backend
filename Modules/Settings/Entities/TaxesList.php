<?php

namespace Modules\Settings\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Scopes\CountryScope;
use Modules\WareHouse\Entities\Country;

class TaxesList extends Model
{
    use SoftDeletes;
    protected $table = 'taxes_lists';
    protected $fillable = [
        'price', 'is_active',
        'tax_type_id','tax_amount_type_id',
        'key', 'country_id'
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


    public function language()
    {
        return $this->belongsToMany(
            Language::class,
            'taxes_list_languages',
            'tax_list_id',
            'language_id'
        )
            ->using(TaxesListLanguage::class)->withPivot('name');
    }

    public function currentLanguage()
    {
        return $this->hasOne(TaxesListLanguage::class, 'tax_list_id')
            ->where('language_id', \Session::get('language_id'));
    }

    public function taxType()
    {
        return $this->belongsTo(TaxesListType::class, 'tax_type_id');
    }

    public function amountType()
    {
        return $this->belongsTo(TaxesListAmountType::class, 'tax_amount_type_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
