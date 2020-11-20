<?php

namespace Modules\PreArticle\Helpers;


class StatusFilterKey
{
    public function NEW()
    {
        return 'NEW';
    }

    public function NOT_COMPLETED()
    {
        return 'NOT_COMPLETED';
    }

    public function SPECIALIZED_FOR_EDITOR()
    {
        return 'SPECIALIZED_FOR_EDITOR';
    }

    public function DONE_BY_EDITOR()
    {
        return 'DONE_BY_EDITOR';
    }

    public function SPECIALIZED_FOR_REFEREES()
    {
        return 'SPECIALIZED_FOR_REFEREES';
    }

    public function NOT_BEEN_JUDGED_AT_TIME()
    {
        return 'NOT_BEEN_JUDGED_AT_TIME';
    }

    public function BEEN_JUDGED_FROM_ALL()
    {
        return 'BEEN_JUDGED_FROM_ALL';
    }

    public function BEEN_JUDGED_FROM_SOME()
    {
        return 'BEEN_JUDGED_FROM_SOME';
    }

    public function NEED_REVIEW()
    {
        return 'NEED_REVIEW';
    }

    public function BEEN_REVIEWED()
    {
        return 'BEEN_REVIEWED';
    }

    public function NOT_REVIEWED_AT_TIME()
    {
        return 'NOT_REVIEWED_AT_TIME';
    }

    public function NOT_PUBLISHED()
    {
        return 'NOT_PUBLISHED';
    }

    public function FINALLY_ACCEPTED()
    {
        return 'FINALLY_ACCEPTED';
    }

    public function REJECTED()
    {
        return 'REJECTED';
    }

    public function SENT_FOR_PAYMENT()
    {
        return 'SENT_FOR_PAYMENT';
    }

}
