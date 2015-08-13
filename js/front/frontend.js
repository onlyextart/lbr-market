;var lbrAnaliticsTimerStart = new Date().getTime();
var lbrAnaliticsSaved = false;
var lbrAnaliticsBlur = false;

$(window).on('beforeunload', function() {
    if ( !$.browser.mozilla ) {
        $(window).off('unload');
    }
    $(window).off('blur');
    saveAnalitics('bu');  
});

$(window).on('unload', function() {
    if ( !$.browser.mozilla ){
        $(window).off('unload');
    }
    $(window).off('blur');
    saveAnalitics('u');
});

$(window).on('blur', function() { // run to another tab
    saveAnalitics('blur');
});

$(window).on('focus', function() { // come back to tab
   lbrAnaliticsTimerStart = new Date().getTime();
   lbrAnaliticsBlur = false;
});

$(document).ready(function($){
    alertify.set({ delay: 5000 }); 
    
    /* choose filial */    
    $('#region').click(function() {
        showRegions();
    });
    
    $('.no-price-label').easyTooltip({content:'Цена будет указана в счет-фактуре'});
    
    $('#confirm-region').click(function() {
        var selector = $('#select-region').find(":selected");
        $.ajax({
            type: 'POST',
            url: '/site/setRegion',
            dataType: 'json',
            data:{
                id: selector.val(),
            },
            success: function() {
                location.reload();
                /*$("#region").text(selector.text());
                if($("#setRegion").dialog("isOpen")) {
                    $("#setRegion").dialog('close');
                }*/
        }});           
    });
    /* end choose filial */
    
    $(".l-menu-wrapper").mCustomScrollbar({
        scrollButtons:{
            enable:true
        }
    });
    
    $(".one_banner h3").dotdotdot({
        ellipsis : '... ',
        wrap	 : 'letter',
    });
    
    $(".one-banner-special h3").dotdotdot({
        ellipsis : '... ',
        wrap	 : 'letter',
    });
    
    $('#accordion-sparepart').dcAccordion({
        eventType: 'click',
        saveState: true,
        disableLink: true,
        speed: 'fast',
        classActive: 'test',
        showCount: false
    });
    
    $('.modelline').dcAccordion({
        eventType: 'click',
        autoClose: true,
        disableLink: true,
        speed: 'fast',
        showCount: false,
        menuClose : true
    });
    
    //Add product to wish list
    $( ".wish-small" ).on('click', function() {
        $.ajax({
            type: 'POST',
            url: '/wishlist/add/',
            dataType: 'json',
            data: {
                id: $(this).parent().attr('elem'),
            },
            success: function(response) {
                if(response.redirect) 
                    window.location = response.redirect;
                else {
                    alertify.success(response.message);
                }
            },
        });
    });
    
    if($('#sale-block ul').length) {
        $('#sale-block ul').carouFredSel({
            prev: '#prev-logo-sale',
            next: '#next-logo-sale',
            items: 3,
            direction: 'down',
            auto: false,
        });
    }
    
    $('.prod-wrapper .dcjq-parent-li').each(function( index ) {
        $( this ).find('a').removeClass("active");
        $( this ).find('ul').hide();
    });
    
    $('.product-info .dcjq-parent-li').each(function( index ) {
        $( this ).find('a').removeClass("active");
        $( this ).find('ul').hide();
    });
    
    $('.model-wrapper .dcjq-parent-li').each(function( index ) {
        $( this ).find('a').removeClass("active");
        $( this ).find('ul').hide();
    });
    
    $('.modellines-wrapper .dcjq-parent-li').each(function( index ) {
        $( this ).find('a').removeClass("active");
        $( this ).find('ul').hide();
    });
    
    // product page
    if($('.price_link').length) {
       $('.price_link').easyTooltip({content:'Авторизуйтесь, чтобы узнать цену'}); 
    }
    // end product page
    // model page
    if($('.price-link').length) {
       $('.price-link').easyTooltip({content:'Авторизуйтесь, чтобы узнать цену'});
    }
    
    $( ".small-cart-button" ).on('click', function() {
        var parent = $(this).parent();
        var cart = parent.find('.cart-quantity');
        var count = parseInt(cart.val());
        if(count > 0) {
            $.ajax({
                type: 'POST',
                url: '/cart/add',
                dataType: 'json',
                data: {
                    id: parent.attr('elem'),
                    count: count,
                },
                success: function(response) { 
                    $('.cart-quantity').val('1');
                    if(response.count){
                        var label = ' товаров';
                        if(response.count == 1) {
                            label = ' товар';
                        } else if(response.count == 2 || response.count == 3 || response.count == 4){
                            label = ' товарa';
                        }
                        $('#cart-count').text(response.count+label);
                    }
                    alertify.success(response.message);
                },
            });
        } else {
            alertify.success('<div class="mes-notify"><span></span><div>Введено неправильное количество</div></div>');
        }
    });
    
    // end model page
    // main page
    if($('#carousel ul').length) {
        $('#carousel ul').carouFredSel({
            pagination: "#pager",
            items: 1,
            scroll: 2000,
        });
    }
    if($('#carousel-logo ul').length) {
        $('#carousel-logo ul').carouFredSel({
            next: '#next-logo',
            prev: '#prev-logo',
            auto: {
                items           : 5,
                fx              :"scroll",
                easing          : "linear",
                duration        : 1000,
                pauseOnHover    : true,
            },
           pagination: "#pager-logo",
           items: 5,
        });
    }
    // end main page
    // seo-text in bottom
    $('.text div *').hide();
    $('.text div *:first-child').show();
    $('.text div *:nth-child(2)').show();
    $('.bottom-more').click(function(){
        if ($(this).hasClass('show-text')){
            $(this).parent().find('div *').hide();
            $(this).parent().find('div *:first-child').show();
            $(this).parent().find('div *:nth-child(2)').show();
            $(this).removeClass('show-text').text('Подробнее...');
        }else{
            $(this).parent().find('div *').show();
            $(this).addClass('show-text').text('Скрыть'); 
        }
    });
    // end seo-text in bottom
});
    
function showRegions(){
    $.ajax({
        url : '/site/getRegions/',
        type : 'POST',
        dataType : "json",
        success:function(data) {
           var temp = [];

           $.each(data['filials'], function(key, value) {
                temp.push({v:value, k: key});
           });

           temp.sort(function(a,b){
               if(a.v > b.v){ return 1}
                if(a.v < b.v){ return -1}
                  return 0;
           });

           $.each(temp, function(key, obj) {
                $('#select-region')
                    .append($("<option></option>")
                    .attr("value", obj.k)
                    .text(obj.v))
                ;
           });

           if(data['active']) {
               $('#select-region').val(data['active']).prop('selected', true);
           }

           $("#setRegion").dialog("open");
        },
        error:function() {
            alert('Ошибка запроса к серверу.');
        }
    });
}

function setCookie(name, value, expires, path, domain, secure) {
    if (!name || !value) return false;
    var str = name + '=' + encodeURIComponent(value);
    var today = new Date();
    today.setTime( today.getTime() );
    if ( expires ) {
            expires = expires * 1000 * 60 * 60 * 24;
    }
    var expires_date = new Date( today.getTime() + (expires) );
    if (expires) str += '; expires=' + expires_date.toGMTString();
    if (path) str += '; path=' + path;
    if (domain) str += '; domain=' + domain;
    if (secure) str += '; secure';

    document.cookie = str;
    return true;
} 

function getCookie(name) {
    var pattern = "(?:; )?" + name + "=([^;]*);?";
    var regexp = new RegExp(pattern);
    if (regexp.test(document.cookie)){
        return decodeURIComponent(RegExp["$1"]);
    }
    return false;
}

function saveAnalitics(p)
{   
    if(!lbrAnaliticsSaved) {
        var url = window.location.pathname;
        var time = (new Date().getTime() - lbrAnaliticsTimerStart)/1000; // in seconds

        $.ajax({
            url: '/analitics/save/',
            type: 'POST',
            dataType: "json",
            data: {
                time: time,
                url: url,
                url_mark: lbrAnaliticsMark
            },
            success: function() {
                if(p == 'blur') lbrAnaliticsBlur = true;
                else lbrAnaliticsSaved = true;
            }
        });
    }
};
