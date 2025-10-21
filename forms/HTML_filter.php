<script>
    /*
     //////////////////////////////////////////////////////////////////////////////
     Фильтрация
     //////////////////////////////////////////////////////////////////////////////
     */
    function _G_focusin_element(idElement, FunctionFilter)
    {
        $('#'+idElement).val('');
        eval(FunctionFilter);
    }

    <?php print "function  refresh_$this->objectFullName()";?>
    {
        var name_div_greed = '<?php print $this->nameGreedDIV; ?>';
        var objectParentName = '<?php print $this->objectParentName; ?>';
        var objectName = '<?php print $this->objectName; ?>';
        var filter = {};
        <?php
        if ($this->standard->resetFetchName()){
            while ($name = $this->standard->fetchNameForFilter()) {
                $condition = $this->standard->getCondition();
                $prefix = $condition['prefix'];
                $postfix = $condition['postfix'];
                $sign = $condition['sign'];
                print "var options={};" . chr(13);
                print "options['value'] = $('#{$name}_$this->objectFullName').val(); " . chr(13);
                print "options['prefix'] = '$prefix'; " . chr(13);
                print "options['postfix'] = '$postfix'; " . chr(13);
                print "options['sign'] = '$sign'; " . chr(13);
                print "filter['$name'] = options; " . chr(13);
                print chr(13);
            }
        }

        /*
         * Следующий раздел формируется, если для справочника задан внешний дополнительный фильтр
         */
        if (is_array($this->filterGlobal))
            if (count($this->filterGlobal) > 0) {
                foreach ($this->filterGlobal as $key => $condition) {
                    $field = $condition['field'];
                    $value = $condition['value'];
                    $znak = $condition['znak'];
                    print "var options={};" . chr(13);
                    print "options['value'] = $value;" . chr(13);
                    print "options['prefix'] = ''; " . chr(13);
                    print "options['postfix'] = ''; " . chr(13);
                    print "options['sign'] = '$znak'; " . chr(13);
                    print "filter['$field'] = options; " . chr(13);
                    print chr(13);

                }
            }
        ?>
        var filterTXT = JSON.stringify(filter);

        var blockWaitForFilter = <?php print $this->blockWaitForFilter; ?>;

        if (blockWaitForFilter === 1 ) {
            BlockAPPWait();
        }

        _G_Ajax({
            type: "POST",
            url: "index_ajax.php",
            data: {
                parent: objectParentName, r0: objectName, r1: "getListFilter",
                filter: filterTXT
            },
            dataType: 'text',
            success: function (data) {
                integrationsScriptCSS(name_div_greed, data)
                var nameEditDIV = '<?php print $this->nameEditDIV; ?>';

                <?php
                if ($this->clearEditDiv)
                    print 'integrationsScriptCSS(nameEditDIV, "<" + "code" + "><" + "/code>")';
                ?>

                if (blockWaitForFilter === 1 )
                    closeBlockAPP();
            }
        });
    }


    <?php print "function  refreshFilterBTN_$this->objectFullName()";?>
    {
        var name_div_greed = '<?php print $this->nameGreedDIV; ?>';
        var objectParentName = '<?php print $this->objectParentName; ?>';
        var objectName = '<?php print $this->objectName; ?>';
        var filter = {};
        <?php
        if ($this->standard->resetFetchName()){
            while ($name = $this->standard->fetchNameForFilter()) {
                $condition = $this->standard->getCondition();
                $prefix = $condition['prefix'];
                $postfix = $condition['postfix'];
                $sign = $condition['sign'];
                print "var options={};" . chr(13);
                print "options['value'] = $('#{$name}_$this->objectFullName').val(); " . chr(13);
                print "options['prefix'] = '$prefix'; " . chr(13);
                print "options['postfix'] = '$postfix'; " . chr(13);
                print "options['sign'] = '$sign'; " . chr(13);
                print "filter['$name'] = options; " . chr(13);
                print chr(13);
            }
        }

        /*
         * Следующий раздел формируется, если для справочника задан внешний дополнительный фильтр
         */
        if (is_array($this->filterGlobal))
            if (count($this->filterGlobal) > 0) {
                foreach ($this->filterGlobal as $key => $condition) {
                    $field = $condition['field'];
                    $value = $condition['value'];
                    $znak = $condition['znak'];
                    print "var options={};" . chr(13);
                    print "options['value'] = $value;" . chr(13);
                    print "options['prefix'] = ''; " . chr(13);
                    print "options['postfix'] = ''; " . chr(13);
                    print "options['sign'] = '$znak'; " . chr(13);
                    print "filter['$field'] = options; " . chr(13);
                    print chr(13);

                }
            }
        ?>
        var filterTXT = JSON.stringify(filter);

        _G_Ajax({
            type: "POST",
            url: "index_ajax.php",
            data: {
                parent: objectParentName, r0: objectName, r1: "getListFilter_filterBTN",
                filter: filterTXT
            },
            dataType: 'text',
            success: function (data) {
                integrationsScriptCSS(name_div_greed, data)
                var nameEditDIV = '<?php print $this->nameEditDIV; ?>';

                <?php
                if ($this->clearEditDiv)
                    print 'integrationsScriptCSS(nameEditDIV, "<" + "code" + "><" + "/code>")';
                ?>
            }
        });
    }

    /*
     //////////////////////////////////////////////////////////////////////////////
     Кнопка Удалить
     //////////////////////////////////////////////////////////////////////////////
     */
    <?php print "function  delete_$this->objectFullName()"; ?>
    {
        var name_greed = '<?php print $this->nameGreed; ?>' ;
        if (insertedRow = _G_foundCheckedRowInGrid_returnObject(name_greed)) {
            var className = '<?php print $this->className; ?>';
            var nameEditDIV = '<?php print $this->nameEditDIV; ?>';
            insertedRow = insertedRow[0];
            _G_Ajax({
                type: "GET",
                url: "index_ajax.php",
                data: {
                    r0: className,
                    r1: "deleteRow",
                    idRowForEditInToSPR: insertedRow
                },
                dataType: 'text',
                success: function () {
                    <?php print "refresh_$this->objectFullName(); ";?>

                    clearEditWindow(nameEditDIV);
                    closeBlockAPP();
                }
            });
        } else {
            _G_BlockAppMessage('Вы не выбрали позицию для редактирования');
        }
    }

    /*
     //////////////////////////////////////////////////////////////////////////////
     Кнопка Редактировать
     //////////////////////////////////////////////////////////////////////////////
     */
    <?php print "function  edit_$this->objectFullName()"; ?>
    {
        var name_greed = '<?php print $this->nameGreed; ?>';
        if (insertedRow = _G_foundCheckedRowInGrid_returnObject(name_greed)) {
            var className = '<?php print $this->className; ?>';
            var nameEditDIV = '<?php print $this->nameEditDIV; ?>';
            insertedRow = insertedRow[0];
            _G_Ajax({
                type: "GET",
                url: "index_ajax.php",
                data: {
                    r0: className,
                    r1: "editRow",
                    idRowForEditInToSPR: insertedRow
                },
                dataType: 'text',
                success: function (data) {
                    integrationsScriptCSS(nameEditDIV, data);
                    window.scrollTo(0,1000);
                }
            });
        } else {
            _G_BlockAppMessage('Вы не выбрали позицию для редактирования');
        }
    }
    /*
     //////////////////////////////////////////////////////////////////////////////
     Кнопка Добавить с использованием каталога (справочника)
     //////////////////////////////////////////////////////////////////////////////
     */
    <?php  print "function  addDataFromCatalog_$this->objectFullName(Field,catalog,olgValue)";?>
    {
        _G_varName = Field;
        _G_BlockApp_Input_catalog(catalog,'<?php print "operationData_$this->objectFullName(\"addData\")";?>',olgValue);
    }
    /*
     //////////////////////////////////////////////////////////////////////////////
     Кнопка Добавить с использованием ввода значения
     //////////////////////////////////////////////////////////////////////////////
     */
    <?php  print "function  addData_$this->objectFullName(Field,olgValue,pattern,message)";?>
    {
        _G_varName = Field;
        _G_BlockApp_Input(olgValue, pattern, message, '<?php print "operationData_$this->objectFullName(\"addData\")";?>');

    }


    /*
     -----------------------------------------------------------------------------------------------
     -----------------------------------------------------------------------------------------------
     Управление в редактировании
     -----------------------------------------------------------------------------------------------
     -----------------------------------------------------------------------------------------------
     */


    /*
     ;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
     Запрос на редактирование значения
     ;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
     */
    <?php  print "function  requestForReplaceValue_$this->objectFullName(Field,olgValue,pattern,message)";?>
    {
        _G_varName = Field;
        _G_BlockApp_Input( olgValue, pattern, message, '<?php print "operationData_$this->objectFullName(\"replaceValue\")";?>');
    }


    /*
     ;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
     Запрос к СПРАВОЧНИКУ на редактирование значения
     ;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
     */
    <?php print "function requestForReplaceValue_Catalog_$this->objectFullName(catalog,Field,oldValue)"; ?>
    {
        _G_varName = Field;
        _G_BlockApp_Input_catalog(catalog, "<?php print "operationData_$this->objectFullName(\\\\'replaceValue\\\\')";?>", oldValue);
    }


    /*
    ;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
    Запрос к классификатору адресов
    ;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
    */
    <?php print "function requestForReplaceValue_address_classifier_$this->objectFullName(Field,oldValue)"; ?>
    {
        _G_varName = Field;
        BlockAPP();
        //filretArray

        var data = new FormData();

        data.append("r0", 'inputEditVariable');
        data.append("r1", 'address_classifier');
        data.append("callFunction", "<?php print "operationData_$this->objectFullName(\\'replaceValue\\')";?>");
        data.append("oldValue", oldValue);
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
                integrationsScriptCSS("BlockAPP", data)
            }
        });
    }


    /*
     ;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
     Отправка на сервер данных после редактировани с указанием метода в контроллере который о обработает данные
     ;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
     */

    <?php print "function  operationData_$this->objectFullName(Operation)"; ?>
    {
        if (typeof _G_varVal === 'object') { /*данные после выбора из справочника приходят в виде массива*/
            _G_varVal = _G_varVal[0];// преобразовываем их в простую переменную
        }
        _G_arrayUpdateInsertValues = {}
        _G_arrayUpdateInsertValues[_G_varName] = _G_varVal;

        <?php print "operationData_0_$this->objectFullName(Operation)"; ?>
    }

    <?php print "function  operationData_0_$this->objectFullName(Operation)"; ?>
    {
        var className = '<?php print $this->className; ?>';
        var nameEditDIV = '<?php print $this->nameEditDIV; ?>';
        variables = JSON.stringify(_G_arrayUpdateInsertValues);
        _G_Ajax({
            type: "GET",
            url: "index_ajax.php",
            data: {
                r0: className,
                r1: Operation,
                variables: variables
            },
            dataType: 'text',
            success: function (data) {
                _G_arrayUpdateInsertValues = {}; // очистка массива для update или insert
                integrationsScriptCSS(nameEditDIV, data);
                <?php print "refresh_$this->objectFullName(); ";?>
                closeBlockAPP();
            }
        });
    }

    /*
     //////////////////////////////////////////////////////////////////////////////
     Функция вывода грида для фильтрации
     //////////////////////////////////////////////////////////////////////////////
     */
    function _G_openGreedForFilter(nameSPR_object,name_BTN,width_BTN,refresh_func)
    {
        var BTN = $("#F_BTN_"+name_BTN)
        var coordinate = BTN.offset();

        var name_HideBlock = "hideBlockForFilter"
        var hideBlock = $("#" + name_HideBlock)
        hideBlock.show()
        hideBlock.width(width_BTN)
        hideBlock.offset(coordinate)

        var inputBlock = $("#"+name_BTN)
        var valueInputBlock = inputBlock.val();

        var data = new FormData();
        data.append("r0", 'inputEditVariable');
        data.append("r1", 'executeCatalogForFilterBTN');
        data.append("_class", nameSPR_object);
        data.append("checked", valueInputBlock);
        data.append("width_BTN", width_BTN);
        data.append("name_BTN", name_BTN);
        data.append("refresh_func", refresh_func);


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
            success: function (data1) {
                integrationsScriptCSS(name_HideBlock, data1);

            }
        });
    }


    function _G_helpInputData(id_element)
    {
        var  nameObject = "#" + id_element
        $(nameObject).datepicker();
    }

    function _G_buttonSelectForFilterBTN(id_greed,name_BTN,refresh_func) {

        var nameObject = "#" + id_greed;
        var allCheckedLS = $(nameObject).find(".id").find("input");
        var text = '['
        var j = 0
        for (var i = 0; i < allCheckedLS.length; i++) {
            if (allCheckedLS[i].checked == true) {
                if (j != 0) {
                    text = text + ','
                }
                j++
                text = text + allCheckedLS[i].name
            }
        }
        text = text + "]"
        if (text == '[]')
            text = ""

        _G_varVal = text

        if ($('#'+name_BTN).length) {
            var inputBlock = $("#"+name_BTN)
            inputBlock.val(text);
        }

        eval(refresh_func);

        closeBlockFilterBTN();
    }


    function closeBlockFilterBTN()
    {
        var name_HideBlock = "hideBlockForFilter"
        integrationsScriptCSS(name_HideBlock, "<code"+"><"+"/code>");
        var hideBlock = $("#" + name_HideBlock)
        hideBlock.hide()
    }
</script>
