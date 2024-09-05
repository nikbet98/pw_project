@extends('layout.master')

@section('active_products','home')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('messages.home_section')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('messages.profile_section')}}</li>
@endsection

@section('body')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">{{__('messages.profile_title')}}{{ Auth::user()->firstname }}!</h2>

            <div class="card">
                <div class="card-header">
                    {{__('messages.my_account')}}
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item"><a href="{{ route("profile.orders") }} ">{{__('messages.my_orders')}}</a></li>
                        <li class="list-group-item"><a href="{{ route("profile.wishlist") }}">{{__('messages.my_wishlist')}}</a></li>
                        <li class="list-group-item"><a href="{{ route("profile.reviews") }}">{{__('messages.my_review')}}</a></li> 
                        <li class="list-group-item"><a href="{{ route("profile.edit") }}">{{__('messages.account_settings')}}</a></li>
                        <li class="list-group-item">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-link">{{__('messages.logout')}}</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
