@php
    $data = $revision->old;
    $related = $revision->revisionable->{$data['relation']}()->find($data['id']);
@endphp
@if($related)
с <a href="{{ $related->identifiableLink() }}">{{ $related->name }}</a>
@endif
