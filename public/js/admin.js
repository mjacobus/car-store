$(document).ready(function(){

    /**
     * Form Validation
     */
    $('form.validate').validate({
        errorPlacement: function(error, element) {
            //error.appendTo( element.parent("td").next("td") );
            var parent = element.parent('dd');
            if (parent.find('ul.error').length == 0) {
                parent.append('<ul class="errors"></ul>');
            }
            error.appendTo(parent.find('ul'));
        },
        errorElement:"li",
        rules: {
            password_confirmation: {
                required: function () {
                    return $('#password').val() !== '';
                },
                equalTo: '#password'
            }
        }
    });

    /**
     * Masks
     */
    $.mask.definitions['w']='[a-zA-Z]';
    $('#licensePlate').mask('www-9999').blur(function(){
        $(this).val($(this).val().toUpperCase());
    });
    $('input.money').maskMoney({
        symbol:'R$',
        decimal:',',
        precision:2,
        thousands:'.',
        allowZero:true,
        showSymbol:false

    });



});