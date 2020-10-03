<?php

namespace Modules\Settings\Services\CMS;

use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Settings\ExcelExports\SystemNoteExport;
use Modules\Settings\Repositories\SystemNoteRepository;
use Modules\Settings\Transformers\SystemNotesResource;

class SystemNoteService extends LaravelServiceClass
{
    private $system_note_repo;

    public function __construct(SystemNoteRepository $system_note_repo)
    {
        $this->system_note_repo = $system_note_repo;
    }
    public function index()
    {
        if (request('is_pagination')) {
            list($system_notes, $pagination) = parent::paginate($this->system_note_repo, null, false);
        } else {
            $system_notes = parent::list($this->system_note_repo, false);

            $pagination = null;
        }

        $system_notes->load('currentLanguage');

        $system_notes = SystemNotesResource::collection($system_notes);

        return ApiResponse::format(200, $system_notes, [], $pagination);
    }

    public function store()
    {
        $system_note = $this->system_note_repo->create(request()->all());

        $language_data = prepareObjectLanguages(request('data'));

        $this->system_note_repo->updateLanguage($system_note, $language_data);

        $system_note->load('language');

        $system_note =  SystemNotesResource::make($system_note);

        return ApiResponse::format(200, $system_note, 'System Note added successfully');
    }

    public function show($id)
    {
        $system_note = $this->system_note_repo->get($id);

        $system_note->load('language');

        $system_note = SystemNotesResource::make($system_note);

        return ApiResponse::format(200, $system_note);
    }

    public function update($id)
    {
        $system_note = $this->system_note_repo->update($id, request()->all());

        if (request()->has('data')) {
            $language_data = prepareObjectLanguages(request('data'));

            $this->system_note_repo->updateLanguage($system_note, $language_data);
        }

        $system_note->load('language');

        $system_note =  SystemNotesResource::make($system_note);

        return ApiResponse::format(200, $system_note, 'System Note updated successfully');
    }

    public function delete($id)
    {
        $system_note = $this->system_note_repo->delete($id);

        return ApiResponse::format(200, $system_note);
    }

    public function export(){
        $file_path = ExcelExportHelper::export('System-Notes', \App::make(SystemNoteExport::class));

        return ApiResponse::format(200, $file_path);
    }
}
