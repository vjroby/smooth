var app = {}; //in this variable we store all the methods
// these variables are used for configuration pourposes
 app.phone_number_class = '.phone_nr';

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