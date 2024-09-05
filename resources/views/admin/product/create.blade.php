@extends('layout.adminMaster')

<!-- Additional styles for this page -->
<link rel="stylesheet" href="{{ asset('css/product.css') }}">

@section('active_products','active')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('messages.home_section')}}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">{{__('messages.products_section')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('messages.new_product')}}</li>
@endsection

@section('body')
    <form action="{{ route('admin.product.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="form-group">
                            <label for="image" class="font-weight-bold">{{__('messages.url_image')}}:</label>
                            <input type="text" id="image" name="image" class="form-control" placeholder="{{__('messages.insert_image')}}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name" class="font-weight-bold">{{__('messages.name')}}:</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="{{__('messages.insert_name')}}">
                        </div>
                        <div class="form-group">
                            <label for="price" class="font-weight-bold">{{__('messages.price')}}:</label>
                            <input type="number" id="price" name="price" class="form-control" placeholder="{{__('messages.insert_price')}}">
                        </div>
                        <div class="form-group">
                            <label for="brand" class="font-weight-bold">{{__('messages.brand')}}:</label>
                            <select id="brand" name="brand" class="form-control">
                                <option value="" selected disabled>{{__('messages.select_brand')}}</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="subcategory" class="font-weight-bold">{{__('messages.subcategory')}}:</label>
                            <select id="subcategory" name="subcategory" class="form-control">
                                <option value="" selected disabled>{{__('messages.select_subcategory')}}</option>
                                @foreach($subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="description" class="font-weight-bold">{{__('messages.description')}}:</label>
                            <textarea id="description" name="description" class="form-control" rows="4" placeholder="{{__('messages.insert_description')}}" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="release_date">{{__('messages.release_date')}}:</label>
                            <input type="date" name="release_date" id="release_date" class="form-control" required>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <button type="submit" class="btn btn-success">{{__('messages.create_product')}}</button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">{{__('messages.dismiss')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
