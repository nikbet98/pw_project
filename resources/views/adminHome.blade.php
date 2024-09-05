@extends('layout.master')

@section('active_products','active')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{__('messages.home_section')}}</li>
@endsection

@section('body')
<h1>{{__('messages.admin_home_title')}}{{$user->firstname}}</h1>
@endsection
