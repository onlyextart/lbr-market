var editOrder = {
    editDelivery : function() {
        $('#delivery').change(function() {
            var delivery = $(this).val();
            if(delivery === editOrder.data.deliveryPickup) {
                $('#Order_user_address').parent().addClass('hide');
            } else {
                $('#Order_user_address').parent().removeClass('hide');
            }
        });
    }
};



