var cart = {
    init : function(){
        $('.cart-quantity').focus(function() {
            var list = $(this).parent().find('.shop-cart-quantity');
            list.addClass('active-list');
            list.show();
        });        

        $('.cart-quantity').bind('input', function() {
            var input = $(this).val();
            if(!isNaN(input) && input.length > 0) {
                $('.shop-cart-quantity li:not(.del-num)').hide();
            } else $('.shop-cart-quantity li:not(.del-num)').show();
        });
        
        $('.shop-cart-quantity').mouseleave(function() {
            cart.closeList();
            var element = $(this).parent().find('.cart-quantity');
            element.blur();
        });
        
        $('.small-cart-button').click(function() {
            var quantity = $(this).parent().find('.cart-quantity');
            var val = parseInt(quantity.text());
            if(!isNaN(val)) {  
                $.ajax({
                    type: 'POST',
                    url:  '#',
                    dataType: 'json',
                    data:{
                        productId: quantity.attr('product'),
                        status: status,
                        reason: reason,
                        date: date,
                    },
                    success: function(response) {
                        if(response.message != 'date'){
                            var element = $('img[userid=' + $('#u-block-id').val() + ']');
                            element.parent().parent().parent().find('.u-status').text(response.message);
                            element.attr('status', status);
                            $('.u-block').addClass('hide');
                        } else {
                            $('#u-block-to').css('border-color', 'red');
                            flag = false;
                        }
                }});
            }
        });
        
        $('.shop-cart-quantity div').click(function(event) {
            
            var val = parseInt($(this).text());
            if(isNaN(val)) {
                // удалить из корзины
                $('.active-list').parent().find('.cart-quantity').val('');
            } else {
                $('.active-list').parent().find('.cart-quantity').val(val);
            }
            cart.closeList();
            event.stopPropagation();
            //console.log('click');
        });
        
        /*
        $('.cart-quantity').focusout(function() {
            console.log('focusout');
            setTimeout(function () {cart.closeList()}, 15000);
        });
        */
    },
    closeList : function() {
        var list = $('.active-list');
        list.hide();
        list.removeClass('active-list');
    }
};
