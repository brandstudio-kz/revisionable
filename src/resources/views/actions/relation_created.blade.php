@php
    $data = $revision->new;
    $related = $revision->revisionable->{$data['relation']}()->find($data['id']);
@endphp
к <a href="{{ $related->identifiableLink() }}">{{ $related->name }}</a>
