$(document).ready(function () {
    $('#payment_method_id').change(function () {
        var _token = $('meta[name="csrf-token"]').attr('content');
        var selectedPaymentMethod = $(this).find(':selected');
        var paymentCategoryId = selectedPaymentMethod.data('payment-category-id'); // Get the payment category id
        var route = $('#payment-category').data('route'); // Retrieve the route URL from the data attribute

        // Show the loading spinner
        $('#loading-spinner').show();

        // Log the paymentCategoryId to the browser's console
        //console.log('Payment Category ID:', paymentCategoryId);

        // Make an AJAX request to fetch payment category data
        $.ajax({
            url: route, // Use the generated route
            method: 'GET',
            data: {
                _token: _token,
                paymentCategoryId: paymentCategoryId
            },
            success: function (data) {
                // Log the received data to the browser's console
                // console.log('Received Data:', data);

                // Assuming the server responds with the payment category data
                var paymentCategory = data.paymentCategory; // Adjust as per your server response structure

                // Update the "Kategori Bayaran" input field with the selected payment category
                $('#payment_category_id').val(paymentCategory.payment_category); // Set the value to the payment category name

                // Hide the loading spinner when the request is complete
                $('#loading-spinner').hide();
            },
            error: function (error) {
                // Log any errors to the browser's console
                console.error('AJAX Error:', error);

                // Hide the loading spinner in case of an error
                $('#loading-spinner').hide();
            }
        });
    });
});
