<?php

namespace BrandStudio\Revisionable\Http\Controllers\Operations;

use Illuminate\Support\Facades\Route;

trait RevisionsOperation
{

    protected function setupRevisionsRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/{id}/revisions', [
            'as' => $routeName.'.listRevisions',
            'uses' => $controller.'@listRevisions',
            'operation' => 'revisions',
        ]);
    }


    protected function setupRevisionsDefaults()
    {
        $this->crud->allowAccess('revisions');
        $this->crud->setRevisionsView('brandstudio::backpack.revisions');

        $this->crud->operation('revisions', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation(['list', 'show'], function () {
            $this->crud->addButton('line', 'revisions', 'view', 'brandstudio::backpack.button', 'end');
        });
    }

    public function listRevisions($id)
    {
        $this->crud->hasAccessOrFail('revisions');

        $crud = $this->crud;
        $entry = $this->crud->getEntry($id);

        $per_page = request('per_page') ?? 20;
        $revisions = $entry->revisions()->latest()->paginate($per_page);

        $title = trans_choice('revisionable::revision.revisions', 2);

        return view($this->crud->getRevisionsView(), compact('entry', 'revisions', 'crud', 'title'));
    }


}
