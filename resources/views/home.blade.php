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
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <div class="box-title">
                        A central access point for a definitive list of Bristol SU Clubs and Societies, and their committee.
                        <br/><br/>
                        @guest
                            Access to this site is limited to Bristol SU Staff. If you have an account, <a href="{{url('/login')}}">login</a> here. Otherwise, email <a href="mailto:tt15951@bristol.ac.uk">tt15951@bristol.ac.uk</a> to ask for access.

                            <br/><br/>
                            <a href="{{url('/login')}}"><button type="button" class="btn btn-info" style="width: 100%">Login</button></a>
                        @endguest
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection