<?php

namespace Modules\Settings\Repositories;

use Modules\Base\Facade\LanguageFacade;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Settings\Entities\PriceListType;
use Modules\Settings\Entities\SystemNote;
use Modules\Settings\Entities\SystemSetting;

class SystemNoteRepository extends LaravelRepositoryClass
{
    public function __construct(SystemNote $system_note)
    {
        $this->model = $system_note;
        $this->cache_key = 'system_notes';
    }

    public function paginate($per_page = 15, $conditions = [], $search_keys = null, $sort_key = 'id', $sort_order = 'asc', $lang = null)
    {
        $query = $this->filtering($search_keys);

        return parent::paginate($query, $per_page, $conditions, $sort_key, $sort_order);
    }

    public function all($conditions = [], $search_keys = null)
    {
        $query = $this->filtering($search_keys);

        return $query->where($conditions)->get();
    }

    private function filtering($search_keys){

        $query = $this->model;


        if ($search_keys) {
            $query = $query->where(function ($q) use ($search_keys){

                $where_conditions = ($search_keys) ? [
                    ['system_notes_language.note_body', 'LIKE', '%'.$search_keys.'%']
                ] : [];

                $or_where_conditions = [];

                $q = LanguageFacade::loadLanguage($q, \Session::get('language_id'), 'language',
                    $search_keys, $where_conditions, $or_where_conditions);

                $q->orWhere('id', 'LIKE', '%'.$search_keys.'%')
                    ->orWhere('note_key', 'LIKE', '%'.$search_keys.'%');
            });
        }

        return $query;
    }

    public function updateLanguage($notes, $notes_languages)
    {
        $notes->language()->detach();
        $notes->language()->attach($notes_languages);
    }
}
