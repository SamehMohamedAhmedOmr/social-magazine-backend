<?php

namespace Modules\PreArticle\Helpers;



class StatusFilterKey
{

    public function NOT_COMPLETED()
    {
        return 'NOT_COMPLETED';
    }

    public function NEW()
    {
        return 'NEW';
    }

    public function SPECIALIZED_FOR_EDITOR()
    {
        return 'SPECIALIZED_FOR_EDITOR';
    }

    public function SPECIALIZED_FOR_REFEREES()
    {
        return 'SPECIALIZED_FOR_REFEREES';
    }

    public function NEED_FOR_RESENT()
    {
        return 'NEED_FOR_RESENT';
    }

    public function NEED_FOR_BIG_REVIEW()
    {
        return 'NEED_FOR_BIG_REVIEW';
    }

    public function NEED_FOR_SMALL_REVIEW()
    {
        return 'NEED_FOR_SMALL_REVIEW';
    }

    public function ACCEPTED_WITH_NEED_FOR_SMALL_REVIEW()
    {
        return 'ACCEPTED_WITH_NEED_FOR_SMALL_REVIEW';
    }

    public function REJECTED_DUE_GOALS()
    {
        return 'REJECTED_DUE_GOALS';
    }

    public function REJECTED_DUE_MANY_RESENT()
    {
        return 'REJECTED_DUE_MANY_RESENT';
    }

    public function REJECTED_DUPLICATE()
    {
        return 'REJECTED_DUPLICATE';
    }

    public function REJECTED_DUE_NO_PRIORITY()
    {
        return 'REJECTED_DUE_NO_PRIORITY';
    }

    public function REJECTED_DUE_LITERARY_PROBLEMS()
    {
        return 'REJECTED_DUE_LITERARY_PROBLEMS';
    }

    public function REJECTED_DUE_ALL_ARBITRATORS_REFUSED_THE_ARBITRATION()
    {
        return 'REJECTED_DUE_ALL_ARBITRATORS_REFUSED_THE_ARBITRATION';
    }

    public function REJECTED_DUE_REFEREES_RECOMMENDATIONS_OR_EDITOR()
    {
        return 'REJECTED_DUE_REFEREES_RECOMMENDATIONS_OR_EDITOR';
    }

    public function REJECTED()
    {
        return 'REJECTED';
    }

    public function SENT_FOR_PAYMENT()
    {
        return 'SENT_FOR_PAYMENT';
    }

    public function ACCEPTED_SCIENTIFICALLY()
    {
        return 'ACCEPTED_SCIENTIFICALLY';
    }

    public function FINALLY_ACCEPTED()
    {
        return 'FINALLY_ACCEPTED';
    }

    public function WITHDRAWAL()
    {
        return 'WITHDRAWAL';
    }

}
