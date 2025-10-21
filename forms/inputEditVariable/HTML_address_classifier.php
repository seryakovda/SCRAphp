<code>
    <?php
    print $HTML;
    ?>
</code>
<script>
    function loadscript() {
        _G_mouseDown_for_moveWindow('<?php print $this->objectFullName;?>')

        set_Z_index();
        _G_value0 = {};

        initValues("area",      1000);
        initValues("district",  2000);
        initValues("town",      3000);
        initValues("street",    4000);
        initValues("house",     5000);
        initValues("room",      6000);

        for (var key in _G_value0) {
            delayFilter(key);
            $("#inputAS_"+key).focus(function (){
                onFocusForClear_greed_classifier();
            })

        }
    }


    function initValues(idInput,LEVEL)
    {
        var arr = {};
        arr['OBJECTID'] = 'false';
        arr['LEVEL'] = LEVEL;
        arr['val'] = '';
        _G_value0[idInput] = arr;
    }

    function onFocusForClear_greed_classifier()
    {
        for (let idInput of Object.keys(_G_value0)) {
            document.getElementById("greed_classifier_"+idInput).innerHTML = '';
        }

    }

    function saveDate() {
        // веременное решение по квартире и доу без поиска в справочнике ГАР

        //addValueFromInputElement('house');
        //addValueFromInputElement('room');

        _G_varVal = '';
        for (let idInput of Object.keys(_G_value0)) {

            if (_G_value0[idInput]['OBJECTID'] != 'false'){
                _G_varVal = _G_value0[idInput]['OBJECTID'];
                /*
                _G_varVal = _G_varVal +
                    _G_value0[idInput]['TYPENAME'] + ' '  +
                    _G_value0[idInput]['val']   + ', ';
                */
            }

        }
        _G_value1 ="";
        BlockAPPWait();
        <?php
        print "$this->callFunction";
        ?>

    }

    function set_Z_index()
    {
        $("#inputAS_area").attr('tabindex', 1000);
        $("#inputAS_district").attr('tabindex',2000);
        $("#inputAS_town").attr('tabindex', 3000);
        $("#inputAS_street").attr('tabindex', 4000);
        $("#inputAS_house").attr('tabindex', 5000);
        $("#inputAS_room").attr('tabindex', 6000);
        $("#OkButton").attr('tabindex', 7000);
        $("#CancelButton").attr('tabindex', 8000);
    }


    function delayFilter(idInput)
    {
        $("#inputAS_"+idInput).on('keyup', function(){
            var $this = $(this);

            var $delay = 800;
            var val = $this.val();

//            console.log(val.length)
/*
            if (val.length < 2) {
                $delay = 4000;
            }

            if (val.length < 3) {
                $delay = 2000;
            }
*/
            clearTimeout($this.data('timer'));
            if (val.length != 0) {
                $this.data('timer', setTimeout(function(){
                    $this.removeData('timer');
                    filterGAR(val,idInput);
                }, $delay));
            }
        });
    }

    function filterGAR(val,idInput)
    {

        _G_Ajax({
            type: "POST",
            url: "index_ajax.php",
            data: {
                r0: "inputEditVariable",
                r1: "filterGAR",
                val: val,
                idInput:idInput,
                data:_G_value0
            },
            dataType: 'text',
            success: function (data) {
                integrationsScriptCSS("greed_classifier_"+idInput, data)
                listBtn = $('.btnList');
                $level = _G_value0[idInput]['LEVEL'] // получаем уровень табиндекса

                for (let id of Object.keys(listBtn)) {
                    idBlock = listBtn[id].id ;
                    if (idBlock !== undefined){
                        idBlock = Number(idBlock.replace(/[^0-9]/g,""));

                        $("#btnList_"+idBlock).attr('tabindex', $level+idBlock);

                    }
                }

            }
        });
    }

    function SelectFromListGAR(PATH,idInput,val)
    {
        document.getElementById("greed_classifier_"+idInput).innerHTML = '';
        _G_Ajax({
            type: "POST",
            url: "index_ajax.php",
            data: {
                r0: "inputEditVariable",
                r1: "SelectFromListGAR",
                val: PATH,
                idInput:idInput,
                data:_G_value0
            },
            dataType: 'text',
            success: function (data) {
                _G_value0 = JSON.parse(data)
                refreshInputs(idInput);
                document.getElementById("greed_classifier_"+idInput).innerHTML = '';

                //integrationsScriptCSS("greed_classifier_"+idInput, data)
            }
        });
    }



    function SelectHouseRoom(OBJECTID,idInput,val)
    {

        $("#inputAS_"+idInput).val(val);
        _G_value0[idInput]['val'] = val
        _G_value0[idInput]['OBJECTID'] = OBJECTID
        document.getElementById("greed_classifier_"+idInput).innerHTML = '';

    }



    function refreshInputs(base_idInput)
    {
        for (let idInput of Object.keys(_G_value0)) {
            $("#inputAS_"+idInput).val(_G_value0[idInput]['val']);
            $("#inputAS_"+idInput).focus();

        }
        $("#inputAS_"+base_idInput).focus();
    }

    function addValueFromInputElement(idInput)
    {
        str = $("#inputAS_"+idInput).val();
        len  = str.length
        console.log(str,'-=',len)
        if (len != 0){
            _G_value0[idInput]['val'] =  $("#inputAS_"+idInput).val();
            _G_value0[idInput]['OBJECTID'] = 1;
            if (idInput == 'house')
                _G_value0[idInput]['TYPENAME'] = 'д.';
            if (idInput == 'room')
                _G_value0[idInput]['TYPENAME'] = 'кв.';
        }else{
            _G_value0[idInput]['val'] =  '';
            _G_value0[idInput]['OBJECTID'] = 'false';
            _G_value0[idInput]['TYPENAME'] = '';
        }
    }
</script>