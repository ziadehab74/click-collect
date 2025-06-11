@extends('errors.minimal')

@section('code', '419')
@section('message', __('Global.authenticate_error'))

@section('buttons')
    <div class="d-flex justify-content-center gap-3">
        <a href="{{ route('login') }}" class="btn btn-primary px-4 py-2">{{ __('Global.login') }}</a>
    </div>
@endsection
