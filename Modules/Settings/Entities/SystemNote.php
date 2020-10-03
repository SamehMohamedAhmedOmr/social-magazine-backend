<?php

namespace Modules\Settings\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Base\Scopes\CountryScope;

class SystemNote extends Model
{
    protected $table = 'system_notes';
    protected $fillable = ['note_key', 'country_id'];

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
            'system_notes_language',
            'system_note_id',
            'language_id'
        )
            ->using(SystemNoteLanguage::class)->withPivot('note_body');
    }

    public function currentLanguage()
    {
        return $this->hasOne(SystemNoteLanguage::class, 'system_note_id')
            ->where('language_id', \Session::get('language_id'));
    }
}
