<?php

namespace Modules\Article\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class SelectedJudgeAttachment extends Pivot
{
    protected $table = 'selected_judge_attachment';
    protected $fillable = [
        'selected_judge_id', 'attachment_id'
    ];
}
