@if ($crud->hasAccess('revisions') && method_exists($entry, 'revisions'))
    <a href="{{ url($crud->route.'/'.$entry->getKey().'/revisions') }}" class="btn btn-sm btn-link"><i class="fa fa-history"></i> {{ trans_choice('revisionable::revision.revisions', 2) }}</a>
@endif
