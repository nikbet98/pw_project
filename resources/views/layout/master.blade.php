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
                        <input class="form-control mr-2" type="text" name="search" placeholder="{{ __('messages.search')}}">
                        <button class="btn btn-outline-light" type="submit">{{ __('messages.search_btn') }}</button>
                    </form>
                    <div class="navbar-icons ml-3 d-flex align-items-center">
                        <a href="{{ route('cart') }}" class="text-white mx-2">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-counter" id="cart-counter">0</span> </a>
                        </a>
                        <a href="{{ route('profile') }}" class="text-white mx-2"><i class="fas fa-user"></i></a>
                        <a href="{{ route('profile.wishlist') }}" class="text-white mx-2"><i class="fas fa-heart"></i></a>
                        <!-- Toggle per Dark/Light Mode -->
                        <button id="theme-toggle" class="btn btn-outline-light ml-2">
                            <i class="fas fa-moon"></i>
                        </button>
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
                </div>
            </div>
        </nav>

        <!-- Navbar Inferiore -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="categoriesDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-bars"></i> {{__('messages.categories_dropdown')}}
                    </button>
                    <div class="dropdown-menu" aria-labelledby="categoriesDropdown">
                        @foreach ($categories as $category)
                                    <a class="dropdown-item" href="{{ route('product.filter', ['category' => $category->id]) }}"> {{$category->name}} </a>
                        @endforeach
                    </div>
                </div>
                <div class="collapse navbar-collapse" id="navbarBottom">
                    <ul class="navbar-nav ml-3">
                        <li class="nav-item @yield('active_home')">
                            <a class="nav-link" href="{{ route('home') }}">{{__('messages.home_section')}}</a>
                        </li>
                        <li class="nav-item  @yield('active_products')">
                            <a class="nav-link" href="{{ route('product.index') }}">{{__('messages.products_section')}}</a>
                        </li>
                        <li class="nav-item @yield('active_promotions')">
                            <a class="nav-link" href="{{ route('promotion.index') }}">{{__('messages.promotions_section')}}</a>
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
    <div id='pippo'>
        
    </div>
    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-links">
                <a>{{ __('messages.who_we_are')}}</a>
                <a>{{ __('messages.terms_and_conditions')}}</a>
                <a>{{ __('messages.privacy')}}</a>
            </div>
            <div class="contact-info">
                <p>{{ __('messages.email')}}</p>
                <p>{{ __('messages.phone_number')}}</p>
            </div>
            <div class="social-media">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
            <div class="footer-logo">
                <p>&copy; 2024 PC Components Store</p>
            </div>
        </div>
    </footer>

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
    
    <!-- Scripts JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/cart.js') }}"></script>
    
    
</body>
</html>
