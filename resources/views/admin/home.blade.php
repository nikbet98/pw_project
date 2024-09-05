@extends('layout.adminMaster')

@section('active_home','active')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{__('messages.home_section')}}</li>
@endsection

@section('body')
    <h1>{{__('messages.admin_home_title')}}{{$user->firstname}}!</h1>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{__('messages.total_users')}}</h5>
                    <p class="card-text">{{ $totalUsers }}</p> 
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{__('messages.sales_performance')}}</h5>
                    <canvas id="salesChart"></canvas> </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Prima esegui le operazioni su $monthlySales nel controller o direttamente qui:
        @php
            $months = array_map(function($item) {
                return date('F', mktime(0, 0, 0, $item['month'], 1)); 
            }, array_column($monthlySales, null)); // 'month' come indice

            $totals = array_column($monthlySales, 'total_sales'); // Estrarre le vendite totali
        @endphp

    
        
        const salesData = {
            labels: @json($months), 
            datasets: [{
                label: 'Monthly Sales',
                data: @json($totals),
                // ... other chart options
            }]
        };
    
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: salesData,
        });
    </script>
    
@endsection
