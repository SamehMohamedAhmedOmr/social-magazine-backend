<?php


namespace Modules\Settings\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Base\Facade\LanguageFacade;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Settings\Entities\Days;
use Modules\Settings\Entities\TimeSection;

/**
 * @property string cache
 */
class TimeSectionRepository extends LaravelRepositoryClass
{
    public function __construct(TimeSection $timeSection)
    {
        $this->model = $timeSection;
        $this->cache = 'timeSection';
    }

    /* CMS Methods */

    // add languages or update languages from object
    public function syncObjectLanguages($data, $model)
    {
        $languagesObjects = prepareObjectLanguages($data);
        $model->language()->sync($languagesObjects);
    }

    // add or update days to time order
    public function syncDays($days, $timeSection)
    {
        $timeSection->days()->sync($days);
    }

    public function paginate($per_page = 15, $conditions = null, $search_keys = null, $sort_key = 'id', $sort_order = 'asc', $lang = null)
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

        $where_conditions = ($search_keys) ? [
            ['time_section_languages.name', 'LIKE', '%'.$search_keys.'%']
        ] : [];

        $or_where_conditions = [];

        $query = LanguageFacade::loadLanguage($query, \Session::get('language_id'), 'language',
            $search_keys, $where_conditions, $or_where_conditions);

        return $query;
    }

    public function deleteTimeSection($id)
    {
        $timeSection = DB::table('time_section_days')->where('time_section_id', $id)->get();
        foreach ($timeSection as $day) {
            DB::table('time_section_days')
                ->where('time_section_id', $day->time_section_id)
                ->where('day_id', $day->day_id)
                ->update(['deleted_at' => Carbon::now()]);
        }
        $this->delete($id);
    }
    /* Front End Methods */
    public function getTimeSectionFront($lang = null)
    {
        // get related data to model
        return Days::with(['languages' => function ($query) use ($lang) {
            $query->where('language_id', $lang);
        } , 'timeSections'])->get();
    }
}
