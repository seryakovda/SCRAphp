<?php
print "$returnHTML";
?>
<script>

    function get()
    {
        _G_id = 1
        _G_varVal = 0
    }

    function _G_foundAllRowInGrid_returnObject(idGreed) { //возвращает массив выбранных элементов
        var nameObject = "#" + idGreed;
        var allCheckedLS = $(nameObject).find(".id").find("input");
        var retData = {};
        var j = 0;
        for (var i = 0; i < allCheckedLS.length; i++) {
            var valElement = {};
            valElement['id'] = allCheckedLS[i].name;
            valElement['value'] = '0';
            if (allCheckedLS[i].checked == true) {
                valElement['value'] = '1';
            }
            retData[j] = valElement;
            j++;
        }

        if ($.isEmptyObject(retData)) {
            return false;
        } else {
            return retData;//возвращает массив выбранных элементов
        }
    }

    function _G_getAllDataInputFromGrid_returnObject(idGreed) {
        var allInputs = $("#" + idGreed).find($(".inpData"));
        var outArray = {};
        for (var i = 0; i < allInputs.length; i++) {
            var elementArray = allInputs[i];
            var field = $(elementArray).attr('data-field');
            var id = elementArray.name;
            var val = elementArray.value;
            if (typeof outArray[field] == "undefined") {
                outArray[field] = {};
            }
            outArray[field][id] = val;
        }
        return outArray;
    }


    function clearEditWindow(idBlock) {
        if ($('div').is('#'+idBlock)) {
            document.getElementById(idBlock).innerHTML = ''
        }
    }

    function eventOnFocus(t,e)
    {
        var allClass = $(t).attr('class');
        var arrayallClass =  allClass.split(" ");
        var selected = "#" + arrayallClass[0];
        $(selected + " .GridLine").removeClass("GridLine_hoverForInput");
        $(selected + " .GridLine_grey").removeClass("GridLine_hoverForInput");
        var id = "#" + $(t).attr('name');
        $("tr " + id).addClass("GridLine_hoverForInput");
    }


    function eventGreed(t, e)
    {
        row = $(t).attr('data-row');
        row = row - 0;
        if (e.code == "ArrowUp") {
            row = row - 1;
            column = $(t).attr('data-column')+$(t).attr('data-numcolumn');
            classInput = "." + column + "_" + row;
            $(classInput).select();
        }

        if ((e.code == "ArrowDown")) {
            row = row + 1;
            column = $(t).attr('data-column')+$(t).attr('data-numcolumn');
            classInput = "." + column + "_" + row;
            $(classInput).select();
        }

        if ((e.code == "Enter") || (e.code == "NumpadEnter")) {

            column = $(t).attr('data-column');
            numColumn = $(t).attr('data-numcolumn');
            numColumn = numColumn * 1;
            numColumn =  numColumn + 1;

            classInput = "." + column + numColumn + "_" + row;
            $(classInput).select();
        }
    }

    function _GREED_callSprInCell(nameIdCell,idRowInGreed,old_captionSpr,nameCallSpr,callBackFunction,widthColumn)
    {
        var data = new FormData();
        var nameObject = "#" + nameIdCell + "_img";
        var obj = $(nameObject)
        var old_idSpr = obj.attr('data-old_idspr')



        data.append("r0", 'inputEditVariable');
        data.append("r1", 'executeCatalogColumnGreed');
        data.append("nameIdCell", nameIdCell);
        data.append("widthColumn", widthColumn);
        data.append("old_idSpr", old_idSpr);
        data.append("old_captionSpr", old_captionSpr);

        data.append("idRowInGreed", idRowInGreed);
        data.append("callBackFunction", callBackFunction);

        data.append("_class", nameCallSpr);


        // if (oldValue !== false)
        //     data.append("oldValue", oldValue);
        // if (filterArray !== false) {
        //     filterArray = JSON.stringify(filterArray);
        //     data.append("filterArray", filterArray);
        // }
        _G_Ajax({
            type: "POST",
            url: "index_ajax.php",
            data: data,
            cache: false,
            dataType: 'text',
            // отключаем обработку передаваемых данных, пусть передаются как есть
            processData: false,
            // отключаем установку заголовка типа запроса. Так jQuery скажет серверу что это строковой запрос
            contentType: false,
            success: function (data) {
                integrationsScriptCSS(nameIdCell, data)
            }
        });
    }

    function _GREED_callSprInCell_answer_1(nameIdCell,idRowInGreed,callBackFunction,nameIdGreedTable)
    {
        var nameObject = "#" + nameIdGreedTable;
        var allCheckedLS = $(nameObject).find(".id").find("input");
        for (var i = 0; i < allCheckedLS.length; i++) {
            if (allCheckedLS[i].checked === true) {
                _G_value1 = allCheckedLS[i].name
                var obj = $(allCheckedLS[i])
                _G_value2 = obj.attr('data-name')
                break
            }
        }

        _GREED_callSprInCell_answer_0(nameIdCell,idRowInGreed,_G_value1,_G_value2,callBackFunction)
    }

    function _GREED_callSprInCell_answer_0(nameIdCell,idRowInGreed,idSpr,captionSpr,callBackFunction)
    {
        _G_id = idRowInGreed
        _G_value0 = nameIdCell
        _G_value1 = idSpr
        _G_value2 = captionSpr

        eval(callBackFunction)
    }
</script>
