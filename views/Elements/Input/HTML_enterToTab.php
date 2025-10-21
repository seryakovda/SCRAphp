<runScript>
    $('#<?php print "$this->NameId";?>').keypress( function(e) {
        if (event.keyCode==13) {
            par = $('#<?php print "$this->NameId";?>').parent().parent()
            inputs = $(par).find($(".MyInput"))

            for (i = 0; i<inputs.length; i++){
                 elsement = inputs[i]
                if ((this.id == elsement.id) && (i+1 < inputs.length)){
                    inputs[i+1].focus();
                }

            }
        }
    });

    $('#<?php print "$this->NameId";?>').on('click', function(e) {
        $('#Title<?php print "$this->NameId";?>').css('font-size','<?php print $element::FontSmall;?>px');
        $('#Title<?php print "$this->NameId";?>').css('margin-top','0px');
        $('#Title<?php print "$this->NameId";?>').focus();
    })

    $('#<?php print "$this->NameId";?>').on('blur', function(e) {
        if ($('#<?php print "$this->NameId";?>').val() == ''){
            $('#Title<?php print $this->NameId;?>').css('font-size','<?php print $startFont;?>px');
            $('#Title<?php print "$this->NameId";?>').css('margin-top','<?php print $element::FontSmall;?>px');
        }
    })

    $('#<?php print "$this->NameId";?>').on( "focus", function(e) {
        $('#Title<?php print "$this->NameId";?>').css('font-size','<?php print $element::FontSmall;?>px');
        $('#Title<?php print "$this->NameId";?>').css('margin-top','0px');
    })

    $('#<?php print "$this->NameId";?>').change(function() {
        if ($('#<?php print "$this->NameId";?>').val() == ''){
            $('#Title<?php print $this->NameId;?>').css('font-size','<?php print $startFont;?>px');
            $('#Title<?php print "$this->NameId";?>').css('margin-top','<?php print $element::FontSmall;?>px');
        }else{
            $('#Title<?php print "$this->NameId";?>').css('font-size','<?php print $element::FontSmall;?>px');
            $('#Title<?php print "$this->NameId";?>').css('margin-top','0px');
        }
    });
</runScript>
