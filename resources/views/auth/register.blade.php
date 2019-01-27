@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1 style="text-align: center;">
            Bristol SU Control Database
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
            <li class="active">Homepage</li>
        </ol>
    </section>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h2 style="text-align: center;">{{ __('Register') }}</h2></div>

                <div class="card-body">
                    Please contact <a href="mailto::tt15951@bristol.ac.uk">tt15951@bristol.ac.uk</a> to create an account.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
