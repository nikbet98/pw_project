document.addEventListener('DOMContentLoaded', (event) => {
    const toggleSwitch = document.querySelector('#theme-toggle');
    const currentTheme = localStorage.getItem('theme') || 'light';

    document.body.classList.toggle('dark-mode', currentTheme === 'dark');

    toggleSwitch.addEventListener('click', () => {
        if (document.body.classList.contains('dark-mode')) {
            document.body.classList.remove('dark-mode');
            localStorage.setItem('theme', 'light');
        } else {
            document.body.classList.add('dark-mode');
            localStorage.setItem('theme', 'dark');
        }
    });
});


function gino(){
    // Handle form submission for filtering
    $('#filterForm').on('submit', function(event) {
        // Prevent default form submission

        // Get filter values
        var brandId = $('#brand').val();
        var categoryId = $('#category').val();
        var subcategoryId = $('#subcategory').val();
        var search_value = $('#search').val();

        var filterUrl = '/products/filter';
        // Make an AJAX request to the server
        $.ajax({
            url: filterUrl,
            method: 'GET',
            data: {
                brand: brandId,
                category: categoryId,
                subcategory: subcategoryId,
                search: search_value,
            },
            success: function(response) {
                // Update the product grid with the filtered results
                $('#productGrid').html(response);
            },
            error: function(error) {
                console.error('Error filtering products:', error);
            }
        });
    });
}

function add_to_cart() {
        $('#addToTheCartBtn').on('click', function(e) {
            e.preventDefault();
            var productId = $('#product_id').val();
            var addUrl = '/cart/add/' + productId;
            $.ajax({
                type: "POST",
                url: addUrl,
                data: {
                    product_id: productId,
                },
                success: function(response) {
                    // Handle success (e.g., update cart counter, display message)
                    console.log(response);
                },
                error: function(response) {
                    // Handle errors
                    console.error(response);
                }
            });
        });
}

