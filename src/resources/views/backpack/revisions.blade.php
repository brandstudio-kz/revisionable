@extends(backpack_view('blank'))

@php
  $defaultBreadcrumbs = [
    trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
    $crud->entity_name_plural => url($crud->route),
    trans('backpack::crud.revisions') => false,
  ];

  // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
  $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
  <div class="container-fluid">
    <h2>
        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
        <small>{!! $crud->getSubheading() ?? trans('backpack::crud.revisions') !!}.</small>

        @if ($crud->hasAccess('list'))
          <small><a href="{{ url($crud->route) }}" class="hidden-print font-sm"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
        @endif
    </h2>
  </div>
@endsection

@section('content')
<div class="">
  <div class="">
      @foreach($revisions as $revision)
        @include('brandstudio::revisions.revision', ['revision' => $revision])
      @endforeach
      {!! $revisions->links() !!}
  </div>
</div>
@endsection


@section('after_styles')
  <link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/crud.css') }}">
  <link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/revisions.css') }}">
@endsection

@section('after_scripts')
  <script src="{{ asset('packages/backpack/crud/js/crud.js') }}"></script>
  <script src="{{ asset('packages/backpack/crud/js/revisions.js') }}"></script>
@endsection
