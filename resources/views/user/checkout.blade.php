@extends('layout.master')

<link rel="stylesheet" href="{{ asset('css/input_error.css') }}">

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('messages.home_section')}}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('profile') }}">{{__('messages.profile_section')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('messages.checkout_section')}}</li>
@endsection

@section('body')
<div class="container">
    <h1>{{__('messages.checkout_title')}}</h1>

    <form action="{{ route('checkout.process') }}" method="POST" id='checkout_form'>
        @csrf

        {{-- Shipping Address Form --}}
        <h2>{{__('messages.shipping_address')}}</h2>
        <div class="form-group">
            <label for="shipping_address">{{__('messages.address')}}:</label>
            <span class="error" id="shipping_address_error"></span>
            @if($contactInfo->address)
                <input type="text" name="shipping_address" id="shipping_address" class="form-control" value="{{ old('shipping_address', $contactInfo->address) }}" required> 
            @else
                <input type="text" name="shipping_address" id="shipping_address" class="form-control" value="{{ old('shipping_address') }}" required>
            @endif
        </div>


        {{-- Payment Information Form (Demo - Not for real use) --}}
        <h2>{{__('messages.payment_information')}}</h2>
        <div class="form-group">
            <label for="credit_card_number">{{__('messages.card_number')}}:</label>
            <input type="number" name="credit_card_number" id="credit_card_number" class="form-control" value="{{ old('credit_card_number') }}" required>
            <span class="error" id="card_number_error"></span>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="expiry_month">{{__('messages.expiration_month')}}:</label>
                <input type="number" name="expiry_month" id="expiry_month" class="form-control" value="{{ old('expiry_month') }}" required>
                <span class="error" id="expiry_month_error"></span>
            </div>
            <div class="form-group col-md-6">
                <label for="expiry_year">{{__('messages.expiration_year')}}:</label>
                <input type="number" name="expiry_year" id="expiry_year" class="form-control" value="{{ old('expiry_year') }}" min="{{ date('Y') }}" required>
                <span class="error" id="expiry_year_error"></span>
            </div>
        </div>
        <div class="form-group">
            <label for="cvv">{{__('messages.ccv')}}:</label>
            <input type="text" name="cvv" id="cvv" class="form-control" value="{{ old('cvv') }}" required>
            <span class="error" id="cvv_error"></span>
        </div>

        {{-- Order Summary --}}
        <h2>{{__('messages.order_summary')}}</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>{{__('messages.product')}}</th>
                    <th>{{__('messages.quantity')}}</th>
                    <th>{{__('messages.price')}}</th>
                    <th>{{__('messages.discount')}}</th>
                    <th>{{__('messages.subtotal')}}</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach ($products as $product) 
                    @php 
                        $quantity = $cart->products()->wherePivot('product_id', $product->id)->first()->pivot->quantity;
                        $discount = $product->promotion()->first() ? $product->promotion()->first()->pivot->discount : 0;
                        $subtotal = ($product->price - ($product->price * $discount)) * $quantity;
                        $total += $subtotal; 
                    @endphp
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $quantity }}</td> 
                        <td>{{ $product->price }}</td> 
                        <td>
                            @if ($discount)
                                {{ $discount * 100 }}%
                            @else
                                {{__('messages.no_discount')}}
                            @endif
                        </td>
                        <td>{{ number_format($subtotal,2) }}</td> 
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p><strong>{{__('messages.total')}}: ${{ number_format($total, 2) }}</strong></p> 
        <input type="hidden" name="total" value="{{ $total }}"> 

        <button type="submit" class="btn btn-primary">{{__('messages.order_confirm')}}</button>
    </form>
</div>

<script>
    // Function to show your custom popup
    function showCustomPopup(type, message) {
        var popup = document.getElementById('custom-popup');
        var popupTitle = popup.querySelector('#popup-title');
        var popupMessage = popup.querySelector('#popup-message');

        if (type === 'success') {
            popupTitle.textContent = popup.dataset.success;
            popupMessage.textContent = popup.dataset.successMessage;
        } else {
            popupTitle.textContent = popup.dataset.error;
            popupMessage.textContent = popup.dataset.errorMessage;
        }

    popup.style.display = 'block';
    }

    // Function to close the popup (if needed)
    function closeCustomPopup() {
        document.getElementById('custom-popup').style.display = 'none';
    }

    // Handle form submission
    document.querySelector('form').addEventListener('submit', function(event) {
        event.preventDefault(); 

        fetch(this.action, {
            method: this.method,
            body: new FormData(this),
            headers: {
                'X-Requested-With': 'XMLHttpRequest' 
            }
        })
        .then(response => {
            if (response.ok) {
                return response.json(); 
            } else {
                throw new Error('An error occurred during checkout.');
            }
        })
        .then(data => {
            if (data.success) {
                // Show your custom popup
                showCustomPopup('success');

                // Redirect after a delay (e.g., 3 seconds)
                setTimeout(function() {
                    window.location.href = '{{ route('profile') }}'; 
                }, 3000); 
            } else {
                showCustomPopup('error', data.message);
            }
        })
        .catch(error => {

        });
    });
</script>

<script src="{{asset('js/checkout_validation.js')}}"></script>

@endsection
