{{-- <div>
    <div class="error-page">
        <h2 class="headline text-danger">401</h2>
        <div class="error-content">
            <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! Something went wrong.</h3>
            <p>
                We will work on fixing that right away.
                Meanwhile, you may <a href="{{url('')}}">return to dashboard</a> or try using the search form.
            </p>
            <form class="search-form">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search">
                    <div class="input-group-append">
                        <button type="submit" name="submit" class="btn btn-danger"><i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div> --}}
@extends('errors.minimal')

@section('title', __('Unauthorized'))
@section('code', '401')
@section('message', __('Unauthorized'))