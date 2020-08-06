@if($revision->getFieldsCnt() > 5)
    ( updated {{ $revision->getFieldsCnt() }} fields )
@else
    ( updated fields: {{ implode(', ', $revision->getLabels()) }} )
@endif
