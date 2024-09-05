<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PC Components Store</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Scripts JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>
<body>
        <!-- Navbar Superiore -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid" style="max-height: 40px;">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTop" aria-controls="navbarTop" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarTop">
                    <form action="{{ route('product.filter') }}" method="GET" class="form-inline ml-auto">
                        <input class="form-control mr-2" type="text" name="search" placeholder={{__('messages.search')}}>
                        <button class="btn btn-outline-light" type="submit">{{__('messages.search_btn')}}</button>
                    </form>
                </div>
                <!-- Dropdown per Impostazioni -->
                <div class="dropdown ml-2">
                    <button class="btn btn-outline-light dropdown-toggle" type="button" id="settingsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-cog"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="settingsDropdown">
                        <h6 class="dropdown-header">{{ __('messages.language') }}</h6> 
                        @foreach(config('app.available_locales') as $locale)
                            <a class="dropdown-item" href="{{ route('locale.switch', $locale) }}">
                                <img src="{{ asset('images/flags/' . $locale . '.png') }}" alt="{{ strtoupper($locale) }} Flag" class="flag-icon"> {{-- Assuming flags are in public/images/flags --}}
                                {{ strtoupper($locale) }} 
                            </a>
                        @endforeach
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">{{__('messages.close_btn')}}</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Navbar Inferiore -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <div class="collapse navbar-collapse" id="navbarBottom">
                    <ul class="navbar-nav ml-3">
                        <li class="nav-item @yield('active_home')">
                            <a class="nav-link" href="{{ route('admin.home') }}">{{__('messages.home_section')}}</a>
                        </li>
                        <li class="nav-item  @yield('active_products')">
                            <a class="nav-link" href="{{ route('admin.products.index') }}">{{__('messages.products_section')}}</a>
                        </li>
                        <li class="nav-item @yield('active_promotions')">
                            <a class="nav-link" href="{{ route('admin.promotions.index') }}">{{__('messages.promotions_section')}}</a>
                        </li>
                        <li class="nav-item @yield('active_orders')">
                            <a class="nav-link" href="{{ route('admin.orders.index') }}">{{__('messages.orders_section')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                {{__('messages.logout')}}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="ml-auto">
                <ol class="breadcrumb">
                    @yield('breadcrumb')
                </ol>
            </nav>
        </nav>  

    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-11 mx-auto">
                @yield('body')
            </div>
        </div>
    </div>


   <div id="custom-popup" style="display: none" 
         data-success="{{ __('messages.success') }}!" 
         data-error="{{ __('messages.error') }}!"
         data-success-message="{{ __('messages.success_message') }}"
         data-error-message="{{__('messages.error_message')}}">
        <div class="popup-content">
            <h3 id="popup-title"></h3>
            <p id="popup-message"></p>
            <button id="popup-close" class="btn btn-danger">{{__('messages.close_btn')}}</button>
        </div>
    </div>  
    
</body>
</html>
