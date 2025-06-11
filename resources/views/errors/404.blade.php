@extends('errors.minimal')

@section('code', '404')
@section('message', __('Global.not_found_page'))

@section('buttons')
    <a href="{{ URL(app()->getLocale()) }}/?component=Home&id=&module=APP&view=index" class="btn btn-primary px-4 py-2">{{ __('Global.back_to_home') }}</a>
@endsection
