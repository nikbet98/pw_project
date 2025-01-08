$(document).ready(function() {
        // Fetch and update cart count on page load
        $.ajax({
            url: "/cart/total-count",
            type: 'GET',
            success: function(response) {
                updateCartCounter(response.cart_count);
            },
            error: function(error) {
                console.error('Error fetching cart count:', error);
            }
        });


    $(document).on('click', '#addToTheCartBtn', function(e) {
        e.preventDefault(); // Prevent default form submission

        var productId = $(this).find('#product_id').val();
        var quantity = $(this).find('#quantity').val();

        $.ajax({
            url: $(this).attr('action'), // Use the form's action URL
            type: 'POST',
            data: {
                _token: $(this).find('input[name="_token"]').val(), // Include CSRF token
                product_id: productId,
                quantity: quantity
            },
            success: function(response) {
                
                // Show the custom popup with a success message
                $('#popup-title').text($('#custom-popup').data('success'));
                $('#popup-message').text($('#custom-popup').data('success-message'));
                $('#popup-close').removeClass('btn-danger btn-success').addClass('btn-success');
                $('#custom-popup').fadeIn(); 

                $.ajax({
                    url: "/cart/total-count", 
                    type: 'GET',
                    success: function(response) {
                        updateCartCounter(response.cart_count);
                    },
                    error: function(error) {
                        console.error('Error fetching cart count:', error);
                    }
                });
            },
            error: function(error) {
                // Show the custom popup with an error message
                $('#popup-title').text($('#custom-popup').data('error'));
                $('#popup-message').text($('#custom-popup').data('error-message'));;
                $('#popup-close').removeClass('btn-danger btn-success').addClass('btn-danger');
                $('#custom-popup').fadeIn(); 
            }
        });
    });

    $(document).on('click', '#addToWishlistBtn', function(e) {
        e.preventDefault(); // Prevent default button behavior

        var productId = $(this).data('product-id'); 
        var csrfToken = $('meta[name="csrf-token"]').attr('content'); // Get CSRF token

        $.ajax({
            url: "/profile/wishlist/add", // Your add to wishlist route
            type: 'POST',
            data: {
                _token: csrfToken,
                product_id: productId
            },
            success: function(response) {
                // Show the custom popup with a success message
                $('#popup-title').text($('#custom-popup').data('success'));
                $('#popup-message').text($('#custom-popup').data('success-message'));
                $('#popup-close').removeClass('btn-danger btn-success').addClass('btn-success');
                $('#custom-popup').fadeIn()
            },
            error: function(error) {
                // Show the custom popup with an error message
                $('#popup-title').text($('#custom-popup').data('error'));
                $('#popup-message').text($('#custom-popup').data('error-message'));
                $('#popup-close').removeClass('btn-danger btn-success').addClass('btn-danger');
                $('#custom-popup').fadeIn()
            },
        });
    });

    $('#popup-close').on('click', function() {
        $('#custom-popup').fadeOut();
    });

    function updateCartCounter(count) {
        $('#cart-counter').text(count);
    }
});

    