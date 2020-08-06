<li>
    <span class="date" title="{{ $revision->created_at->format('Y.m.d, H:i:s') }}">{{ $revision->created_at->diffForHumans() }}</span>
    @if($revision->responsible)
        <a href="{{ $revision->responsible->identifiableLink() }}" target="_blank">{{ $revision->responsible_description }}</a>
    @else
        <span class="description">{{ $revision->responsible_description }}</span>
    @endif

    <span class="action {{ $revision->action_name }}">{{ trans_choice("revisionable::revision.{$revision->action_name}", $revision->responsible_id ? 1 : 2) }}</span>
    @if($revision->revisionable)
        <a href="{{ $revision->revisionable->identifiableLink() }}">{{ $revision->revisionable_description }}</a>
    @else
        <span class="description">{{ $revision->revisionable_description }}</span>
    @endif

    @include("brandstudio::actions.{$revision->action_name}", ['revision' => $revision])
</li>
