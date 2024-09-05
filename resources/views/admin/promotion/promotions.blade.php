@extends('layout.adminMaster')

@section('active_promotions','active')
<link rel="stylesheet" href="{{ asset('css/promotion_card.css') }}">

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('messages.home_section')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('messages.promotions_section')}}</li>
@endsection

@section('body')
    <div class="container-fluid">
        <div class="row">
            <div class="col-11 mx-auto">
                <div class="d-flex justify-content-between align-items-center mb-4"> 
                    <h2 class="mb-0">{{__('messages.admin_promotions_title')}}</h2> 
                    <a href="{{ route('admin.promotion.create') }}" class="btn btn-success">{{__('messages.create_new_promotion')}}</a> 
                </div>
                <div class="row">
                    @foreach ($promotions as $promotion)
                        <div class="col-12">
                            <div class="card promotion-card">
                                <img src="{{ $promotion->image }}" class="card-img-top" alt="{{ $promotion->title }}">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $promotion->name }}</h5>
                                        <p class="card-text">{{ $promotion->description }}</p>
                                        <p class="card-text"><strong>{{__('messages.start')}}:</strong> {{ $promotion->start }}</p>
                                        <p class="card-text"><strong>{{__('messages.end')}}:</strong> {{ $promotion->end }}</p>
                                        <div class="d-flex justify-content-center">
                                            <div class="col-md-4"> 
                                                <form>
                                                    <a href="{{ route('admin.promotion.edit', $promotion->id) }}" class="btn btn-primary w-100">{{__('messages.modify')}}</a>
                                                </form>
                                            </div>
                                            <div class="col-md-4">
                                                <form action="{{ route('admin.promotion.destroy', $promotion->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger w-100" onclick="return confirm(__('messages.sure_delete'))">{{__('messages.delete')}}</button>
                                                </form>
                                            </div>
                                        </div>
                                        
                                    </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection