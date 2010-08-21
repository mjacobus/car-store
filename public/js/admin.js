$(document).ready(function(){

    /**
     * Editor WYSIWYG
     */
    $('.editor').wysiwyg({
        controls:{
            html : {
                visible : true,
                exec    : function()
                {
                    //my modification
                    if(this.viewHTML){
                        this.editor.show();
                        $('div.wysiwyg ul.panel li:first').show();
                    } else {
                        this.editor.hide();
                        $('div.wysiwyg ul.panel li:first').hide();
                    }
                    //end of my modification

                    if ( this.viewHTML ){
                        this.setContent( $(this.original).val() );
                        $(this.original).hide();
                    } else {
                        this.saveContent();
                        $(this.original).show();
                    }

                    this.viewHTML = !( this.viewHTML );
                },
                tooltip : "Visualizar código fonte"
            }
        }
    });
    //Hide all not bold and not html source options
    $('div.wysiwyg ul.panel li:last').hide();
    $('div.wysiwyg ul.panel li:visible').not(':first,:last').hide();


    /**
     * Calendar
     */
    $.datepicker.regional['pt-BR'] = {
        closeText:"Ok",
        prevText:"Anterior",
        nextText:"Próximo",
        currentText:"Hoje",
        monthNames:["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"],
        monthNamesShort:["Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set","Out","Nov","Dez"],
        dayNames:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],
        dayNamesShort:["Dom","Seg","Ter","Qua","Qui","Sex","Sab"],
        dayNamesMin:["Do","Se","Te","Qu","Qu","Se","Sa"],
        weekHeader:"Sem",
        dateFormat:"dd/mm/yy",
        firstDay:0,
        isRTL:false,
        showMonthAfterYear:false,
        yearSuffix:""
    };
    $("input.date").datepicker($.datepicker.regional['pt-BR']);

    /**
     * Form Validation
     */
    $('form.validate').validate({
        errorPlacement: function(error, element) {
            //error.appendTo( element.parent("td").next("td") );
            var parent = element.parent('dd');
            console.log(parent.html());
            if (parent.find('ul.error').length == 0) {
                parent.append('<ul class="errors"></ul>');
            }
            error.appendTo(parent.find('ul'));
        },
        errorElement:"li"
    });

    /**
     * Masks
     */
    $('input.date').mask('99/99/9999');

    /**
     * Search Form Validation
     */
    $('#search').validate({
        errorPlacement: function(error, element) {
            element.addClass('error');
        }
    });


});