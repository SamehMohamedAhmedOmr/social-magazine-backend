<?php

namespace Modules\Base\Services\Interfaces;

interface LaravelServiceInterface
{
    public function index();

    public function store();

    public function show($id);

    public function update($id);

    public function delete($id);
}
