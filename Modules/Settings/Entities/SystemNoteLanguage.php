<?php

namespace Modules\Settings\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class SystemNoteLanguage extends Pivot
{
    protected $table = 'system_notes_language';
    protected $fillable = ['note_body','language_id'];
}
