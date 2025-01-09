@extends('layout.master')
@section('active_products','home')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('messages.home_section')}}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('profile') }}">{{__('messages.profile_section')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('messages.settings_section')}}</li>
@endsection

@section('body')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h2>{{__('messages.account_settings_title')}}</h2>

            <div class="card">
                <div class="card-header">
                    {{__('messages.personal_info')}}
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')
                
                        <div class="form-group">
                            <label for="firstname">{{__('messages.firstname')}}</label>
                            <input type="text" name="firstname" id="firstname" class="form-control" value="{{ old('firstname', $user->firstname) }}">
                            @error('firstname')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                
                        <div class="form-group">
                            <label for="lastname">{{__('messages.lastname')}}</label>
                            <input type="text" name="lastname" id="lastname" class="form-control" value="{{ old('lastname', $user->lastname) }}">
                            @error('lastname')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="date_of_birth">{{__('messages.date_of_birth')}}</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="{{ old('date_of_birth', $contactInfo->date_of_birth) }}">
                            @error('date_of_birth')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                
                        <div class="form-group">
                            <label for="email">{{__('messages.email')}}</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                
                        <div class="form-group">
                            <label for="password">{{__('messages.new_password')}}</label>
                            <input type="password" name="password" id="password" class="form-control">
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                
                        <div class="form-group">
                            <label for="password_confirmation">{{__('messages.confirm_password')}}</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                        </div>
                
                        <hr> 
                        <h3>Informazioni di Contatto</h3>
                
                        <div class="form-group">
                            <label for="phone_number">{{__('messages.phone')}}</label>
                            <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ old('phone_number', $contactInfo->phone_number) }}"> 
                            @error('phone_number')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                
                        <div class="form-group">
                            <label for="address">{{__('messages.address')}}</label>
                            <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $contactInfo->address ) }}">
                            @error('address')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                
                        <div class="form-group">
                            <label for="zipcode">{{__('messages.cap')}}</label>
                            <input type="text" name="zipcode" id="zipcode" class="form-control" value="{{ old('zipcode', $contactInfo->zipcode) }}">
                            @error('zipcode')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                
                        <button type="submit" class="btn btn-primary">{{__('messages.save')}}</button>
                    </form>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection
