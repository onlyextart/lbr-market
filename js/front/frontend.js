$(document).ready(function($){
    alertify.set({ delay: 5000 }); 
    
    /* choose filial */    
    $('#region').click(function() {
        $("#setRegion").dialog("open");
    });
    
    $('.btn-request').click(function() {
        document.location.href = "/site/quickform/";
    });
    
    $('.guestcart').click(function() {
        document.location.href = "/site/login/";
    });
    
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
                //setCookie('filial', $(this).attr('contact'), '3', '/', '.lbr.ru')
                $("#region").text(selector.text());
                if ($("#setRegion").dialog("isOpen")) {
                    $("#setRegion").dialog('close');
                }
        }});           
    });
    /* end choose filial */
    
    $(".l-menu-wrapper").mCustomScrollbar({
        scrollButtons:{
            enable:true
        }
    });
    
    $('#sale-block ul').carouFredSel({
        prev: '#prev-logo-sale',
        next: '#next-logo-sale',
        items: 3,
        direction: 'down',
        auto: false,
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
});

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

