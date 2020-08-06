@php
    if (!isset($revisions)) {
        $revisions = \BrandStudio\Revisionable\Revision::latest()->where('highlight', true)->paginate(20)->appends(request()->input());
    }

@endphp

<div class="container-fluid">
    <ul class="rev-history">
        @foreach($revisions as $revision)
            @include('brandstudio::revisions.revision', ['revision' => $revision])
        @endforeach
    </ul>
    {!! $revisions->links() !!}
</div>

@push('before_styles')
<style>
    .rev-history {
        margin: 0;
        padding: 20px 0 0 0;
        list-style: none!important;
    }
    .rev-history .date {
        color: rgb(100, 100, 100);
        font-style: italic;
    }
    .rev-history .action {
        font-weight: bold;
    }
    .rev-history .action.created {
        color: rgb(75, 170, 75);
    }
    .rev-history .action.updated {
        color: rgb(75, 75, 250);
    }
    .rev-history .action.deleted {
        color: rgb(250, 75, 75);
    }
    .rev-history .action.relation_created {
        color: rgba(75, 170, 75, .8);
    }
    .rev-history .action.relation_updated {
        color: rgba(75, 75, 250, .8);
    }
    .rev-history .action.relation_deleted {
        color: rgba(250, 75, 75, .8);
    }
</style>
@endpush
