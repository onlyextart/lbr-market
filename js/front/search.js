;function AjaxQuickSearch(type){
    var _self = this;
    if(type == 'full'){
        this.Option = {
            url: '/search/index/',
            container: '.full-quick-result',
            input: '#full-search'
        };

        $(_self.Option.input).keyup(function(){
            if($.trim($(this).val()).length > 0) {
                if($('.full-quick-result').hasClass('hide')) {
                    $('.full-quick-result').removeClass('hide');
                    $('.full-quick-result').fadeIn(200);
                }
                $('.full-quick-result').fadeIn(200);
                _self.AjaxRequest($(this).val());
            } else {
                $('.full-quick-result').fadeOut(200);
                $('.full-quick-result').addClass('hide');
            }
        });
    } else {
        this.Option = {
            url: '/search/index/',
            container: '.quick-result',
            input: '#search'
        };

        $(_self.Option.input).keyup(function(){
            if($.trim($(this).val()).length > 0) {
                if($('.quick-result').hasClass('hide')) {
                    $('.quick-result').removeClass('hide');
                    $('.quick-result').fadeIn(200);
                }
                $('.quick-result').fadeIn(200);
                _self.AjaxRequest($(this).val());
            } else {
                $('.quick-result').fadeOut(200);
                $('.quick-result').addClass('hide');
            }
        });
    }
    
    this.AjaxRequest = function(query){
        $.ajax({
            'url': _self.Option.url,
            'data': {q: query, ajax: true},
            'success': function(html){_self.AjaxSuccess(html);}
        });
    };

    this.AjaxSuccess = function(html){
         $(_self.Option.container).html(html);
    };
};

function QuickSearchEnter(type){
    var _self = this;
    if(type == 'full'){
        this.Option = {
            input: '#full-search',
            form: '#form_full_search'
        };
    }
    else{
        this.Option = {
            input: '#search',
            form: '#form_search'
        };
    }
    
       var search=document.querySelector(_self.Option.input);
       search.addEventListener("keypress",function(e){
        if(e.keyCode===13){
            var input = $.trim($(_self.Option.input).val());
            if(input.length > 0){
                var form_search=document.querySelector(_self.Option.form);
                var path="/search/show/input/" + input;
                form_search.setAttribute("action", path);
                form_search.submit();
                    
            }
        }
       }); 
    
};
