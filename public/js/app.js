var app = {
    APP_PATH:null,
    navigate: function navigate(href, isAjax, animation, historyChange, target){
        var that = this,
            page = href,
            ajaxWrapper = $('#ajaxWrapper'),
            ajaxInner = $('.ajaxInner');
//        var page = href.substr(that.APP_PATH.toString().length, href.length).length == 0
//            ? 'index'
//            : href.substr(that.APP_PATH.toString().length, href.length);


        if(href.substr(0, 1) == '#') {
            isAjax = false;
        }
        if(!isAjax) {
            if(target){
                window.open(href,'_blank');
            }else{
                window.location = href;
            }
        }else{
            console.log('[AJAX Navigation]: '+page);

            if (ajaxWrapper.length == 0){
                throw new DOMException('ajaxWrapper element not found')
            }

            if(!historyChange) {
                var stateObj = {href: href}; // state object are used when trying to go forward or backward
                history.pushState(stateObj, "AJAX-PAGE", href);
            }

            try{
                $.ajax({
                    url:page

                }).done(function( data ){
                    ajaxInner.replaceWith(data);
                }).fail(function() {
                    alert( "error" );
                })
            }
            catch (err) {
                ajaxInner.replaceWith('<div id="ajaxInner">'+err+'</div>');
            }

        }

    },
    getAppPath: function getAppPath() {
//        var pathArray = location.pathname.split('/');
//        var appPath = "/";
//        for(var i=1; i<pathArray.length-1; i++) {
//            appPath += pathArray[i] + "/";
//        }
        this.APP_PATH = document.URL;
    },
    applyAjaxNavigation: function applyAjaxNavigation(){
        var that = this;
        $('body').on('click', 'a', function(e) {
            e.preventDefault();
            var href = $(this).attr('href'),
                ajax = $(this).attr('ajax'),
                animation = $(this).attr('animation'),
                target = $(this).attr('target');

            if(typeof ajax == 'undefined') ajax = true;
            else ajax = (ajax == 'yes') ? true : false;

            if(typeof animation == 'undefined') animation = true;
            else animation = (animation == 'yes') ? true : false;

            if(typeof target == 'undefined') target = false;
            else target = (target == '_blank') ? true : false;

            that.navigate(href, ajax, animation, false, target);
        });
    }
};

//in this variable we store all the methods
// these variables are used for configuration pourposes
 app.phone_number_class = '.phone_nr';

app.getAppPath();

app.only_numbers = function only_numbers(){
    //class for only numbers input
    $('.nr')
        .on('keypress', function(e) {
            var unicode=e.charCode? e.charCode : e.keyCode;

            if (unicode!=8) {  if (unicode<48||unicode>57){
                main.notification('Doar numere');
                return false;
            }  }
        })

        .on('keyup', function(e) {
            var limit = parseInt($(this).attr('limit'));
            if(typeof limit != 'undefined') {
                console.log("Limit: "+limit);
                if(parseInt($(this).val()) > limit) { $(this).val(limit); }
            }
        });
};

app.tel_numbers = function tel_numbers(){
    var inital_value = $(app.phone_number_class).val();

    $(app.phone_number_class).val(inital_value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " "));

    $(app.phone_number_class).on('blur',function(){
        var value =  $(this).val();
        $(this).val(value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " "));
    }).on('focus',function(){
        var value =  $(this).val();
        console.log(value.toString().trim());
        $(this).val(value.replace(/\s+/g, ''));
    });
};

$(document).ready(function(){
    app.applyAjaxNavigation();
});