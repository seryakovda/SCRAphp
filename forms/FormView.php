<?php
/**
 * Created by PhpStorm.
 * User: rezzalbob
 * Date: 28.11.2019
 * Time: 15:46
 */

namespace forms;


use forms\SearchPass\Elements\Search;
use views\Elements\Grid\Grid;
use models\Standards\TypeFilterInput;

abstract class FormView
{

    public $ParentObject;
    /**
     * @var \views\Elements\Grid\Grid
     */
    public $greed_Object;
    public $objectName;
    public $className;

    public $filterGlobal;

    public $objectFullName;
    public $objectParentName;
    public $callFunction_txt;

    public $nameGreed; //SPR_Users_greed  = forms\SPR\Users[_greed](ID основного грида)
    public $nameGreedDIV; //SPR_Users_greed_div = forms\SPR\Users[_div](ID хранения основного грида)
    public $nameEditDIV;  //SPR_Users_edit_div = forms\SPR\Users[_div] (ID хранения основного грида)
    public $allInsertOff;
    public $filterElementsON = true;

    /**
     * @var \views\Elements\VElements
     */
    public $ELM;
    public $WND;
    public $BTN;
    public $CHK;

    public $TXT_headSmallTitle;
    public $TXT_headBigTitle;

    public $formWidth;

    public $windowContent;
    public $dataGrid_Object;
    public $ColumnID = 'id';
    public $rowsInGreed = 20;
    public $DEV;
    public $readOnly = false; //поле для идентификации записи в Grid
    private $onClickFunctionForAllTable = false;
    private $onDblClickFunctionForAllTable = false;
    public $standard;
    public $standardForEdit;
    private $columnName; // В случае режима разработки вывести все доступные поля в форму редактирования
    public $rowDataForEdit;
    public $widthButtonReport = 320;
    public $DefaultTextForEmptyGreed;
    public $nameManageButton = 'manageButton';
    public $headNone = false;
    private $arrayChecked = false;
    public $blockNum = 0;
    private $clearEditDiv = true;
    private $visibleHeadTable = true;
    private $l = ""; // типа слой добисывает значение в  стиль грида для подсвечсивания строк
    public $blockWaitForFilter = 0;

    private $autoRefresh = true;
    /*
    пометка вызова из вормы а не напрямую. используется в лицевых счетах
    при вызове из вормы ненужно подменять рабочие переменные сессии и
    дополнительные талицы. толко передать информацию о выбранном элементе
    */
    public $formSelect = false;

    function __construct()
    {
        $this->className = $this->className();

        $this->standard = new \models\Standards\FieldsForView();
        $this->standardForEdit = new \models\Standards\FieldsForEdit();

        $this->BTN = new \views\Elements\Button\Button();
        $this->WND = new \views\Elements\Window\Window();

        $this->arrayColumns = Array();
        $this->initClass();
    }


    public function setLayer($layerStyle)
    {
        $this->l = $layerStyle;
        return $this;
    }


    /**
     * @return $this
     */
    public function setAutoRefresh_OFF()
    {
        $this->autoRefresh = false;
        return $this;
    }


    /**
     * @param bool $visibleHeadTable
     * @return $this
     */
    public function setVisibleHeadTable(bool $visibleHeadTable)
    {
        $this->visibleHeadTable = $visibleHeadTable;
        return $this;
    }


    /**
     * @return $this
     */
    public function set_NO_clearEditDiv($value = false)
    {
        $this->clearEditDiv = $value;
        return $this;
    }

    /**
     * @param mixed $blockNum
     */
    public function setBlockNum($blockNum): void
    {
        $this->blockNum = $blockNum;
    }


    /**
     * @param bool $readOnly
     * @return $this
     */
    public function readOnly()
    {
        $this->readOnly = true;
        return $this;
    }

    public function headNone()
    {
        $this->headNone = true;
        return $this;
    }


    public function setNameManageButton(string $nameManageButton)
    {
        $this->nameManageButton = $nameManageButton;
    }

    public function className()
    {
        $className = get_class($this);
        $className = str_replace("forms\\", "", $className);
        $className = str_replace("\\VIEW", "", $className);
        $className = str_replace("\\", "\\\\", $className);
        return $className;
    }

    public function initClass()
    {
        $this->TXT_headBigTitle = "";
        $this->TXT_headSmallTitle = "SmallHead";
        $this->DefaultTextForEmptyGreed = 'Нет данных в таблице';
    }


    /**
     * @param bool $onDblClickFunctionForAllTable
     * @return $this
     */
    public function setOnDblClickFunctionForAllTable(bool $onDblClickFunctionForAllTable)
    {
        $this->onDblClickFunctionForAllTable = $onDblClickFunctionForAllTable;
        return $this;
    }


    /**
     * @param $onClickFunctionForAllTable
     * @return $this
     */
    public function setOnClickFunctionForAllTable($onClickFunctionForAllTable)
    {
        $this->onClickFunctionForAllTable = $onClickFunctionForAllTable;
        return $this;
    }

    /**
     * @param array $arrayChecked
     * @return $this
     */
    public function setArrayChecked(array $arrayChecked)
    {
        $this->arrayChecked = $arrayChecked;
        return $this;
    }

    /**
     * @param $DefaultTextForEmptyGreed
     * @return $this
     */
    public function setDefaultTextForEmptyGreed($DefaultTextForEmptyGreed)
    {
        $this->DefaultTextForEmptyGreed = $DefaultTextForEmptyGreed;
        return $this;
    }


    /**
     * @param int $widthButtonReport
     */
    public function setWidthButtonReport(int $widthButtonReport)
    {
        $this->widthButtonReport = $widthButtonReport;
    }

    /**
     * @param mixed $rowDataForEdit
     */
    public function setRowDataForEdit($rowDataForEdit)
    {
        $this->rowDataForEdit = $rowDataForEdit;
    }

    /**
     * @param mixed $standardForEdit
     */
    public function setStandardForEdit(\models\Standards\FieldsForEdit $standardForEdit)
    {
        $this->standardForEdit = $standardForEdit;
    }

    public function createHeadWindow()
    {
        $windowContent = '';
        if ($this->filterElementsON)
            $windowContent = $windowContent . $this->filterBlock();
        $this->windowContent = $windowContent . $this->divGreed();
    }

    /**
     * @param string $ColumnID
     */
    public function setColumnID(string $ColumnID)
    {
        $this->ColumnID = $ColumnID;
    }

    public function filterBlock()
    {

        $INP = new \views\Elements\Input\Input();

        $HTML = "";
        $this->standard->resetFetchName();
        $CountField = 0;
        while ($name = $this->standard->fetchNameForFilter()) {
            $id_element = "{$name}_$this->objectFullName";

            if ($this->standard->getTypeFilterInput() == TypeFilterInput::INPUT){
                $INP->set($this->standard->getCaption())
                    ->height($this->standard->getHeightForFilter())->width($this->standard->getWithForFilter())
                    ->position("relative")
                    ->startFont("Large")
                    ->NameId($id_element)
                    ->floatLeft();


                if ($pattern = $this->standard->getInputPattern())
                    $INP->pattern($pattern);

                if ($OnKeyUpFunction = $this->standard->getOnKeyUpFunction())
                    $INP->setOnKeyUpFunction($OnKeyUpFunction);

                if ($this->autoRefresh) // отключает автовильтрацию в связи с настройкой
                    $INP->setFunctionNameFor_SetFocus("_G_focusin_element('$id_element', 'refresh_$this->objectFullName()')");


                if ($this->standard->getInputPattern() == '99.99.9999'){

                    $INP->setFunctionNameFor_SetFocus("_G_helpInputData('$id_element')");
                }

                if ($focus_in_func = $this->standard->getFocus_IN()){
                    $INP->setFunctionNameFor_SetFocus($focus_in_func);
                }

                $HTML = $HTML .
                    $INP->get();
            }
            if ($this->standard->getTypeFilterInput() == TypeFilterInput::BTN_SPR) {
                $ELM = new \views\Elements\VElements();
                $BTN = new \views\Elements\Button\Button();
                $width_BTN = $this->standard->getWithForFilter();


                $HTML_INP = $ELM->tag("input")
                    ->setId($id_element)
                    ->setStyle("display:none")
                    ->getHTTPTag();

                $_class = $this->standard->getClassTypeFilterInput();

                $HTML_BTN = $BTN->set("Фильтр по полю " . $this->standard->getCaption())
                    ->nameId("F_BTN_".$id_element)
                    ->setReadOnly(false)
                    ->height($this->standard->getHeightForFilter())->width($width_BTN)
                    ->position("relative")
                    ->func("_G_openGreedForFilter('$_class','$id_element',$width_BTN,'refresh_$this->objectFullName()')")
                    ->floateLeft()
                    ->get();

                $HTML = $HTML . $HTML_INP . $HTML_BTN;
            }
            $CountField ++;
        }
        $HTML = $HTML . $this->addElementsIntoFoterBlock();

        if ($CountField > 0) // если нет полей для фильтрации не делаем и кнопку
            $HTML = $HTML .
                $this->BTN->set("Отфильтровать")
                    ->nameId("refresh_{$this->objectFullName}_btn")
                    ->height(50)->width(150)
                    ->position('relative')
                    //->style('margin-bottom: 10px;')
                        ->style("top: 5px;left: 5px;")
                    ->func("refresh_$this->objectFullName()")
                    //->floateLeft()
                    ->get();

        return $HTML;
    }

    public function addElementsIntoFoterBlock()
    {
        return '';
    }

    public function filterBlockForFilterBTN()
    {

        $INP = new \views\Elements\Input\Input();

        $HTML = "";
        $this->standard->resetFetchName();
        $CountField = 0;
        while ($name = $this->standard->fetchNameForFilter()) {
            $id_element = "{$name}_$this->objectFullName";

            if ($this->standard->getTypeFilterInput() == TypeFilterInput::INPUT){
                $INP->set('')
                    ->height(25)->width($_SESSION['width_BTN']-60)
                    ->position("relative")
                    ->startFont("Large")
                    ->NameId($id_element)
                    ->floatLeft();


                if ($pattern = $this->standard->getInputPattern())
                    $INP->pattern($pattern);

                if ($OnKeyUpFunction = $this->standard->getOnKeyUpFunction())
                    $INP->setOnKeyUpFunction($OnKeyUpFunction);

                $INP->setFunctionNameFor_SetFocus("_G_focusin_element('$id_element', 'refreshFilterBTN_$this->objectFullName()')");
                $HTML = $HTML .
                    $INP->get();
            }

            $CountField ++;
        }
        if ($CountField > 0) // если нет полей для фильтрации не делаем и кнопку
            $HTML = $HTML .
                $this->BTN->set( '&#128269')
                    ->height(20)->width(20)
                    ->position('static')
                    //->style('margin-bottom: 10px;')
                    ->func("refreshFilterBTN_$this->objectFullName()")
                    ->floateLeft()
                    ->get();

        return $HTML;
    }

    /**
     * @return string возвращает HTML таблицы завернутую в окно
     */
    public function divGreed()
    {

        $HTML = $this->greed($this->dataGrid_Object);
        return $this->WND->set()->nameId($this->nameGreedDIV)
            ->headSizeNone()
            ->shadowNone()
            ->content($HTML)
            ->get();
    }

    public function greed($data_Object)
    {
        $width = 500;
        $this->greed_Object = new \views\Elements\Grid\Grid();
        $this->greed_Object->GNew(\models\ControlElements::get()->getNameMethod($this->objectFullName, __METHOD__))
            ->checked()
            ->width($this->formWidth)
            ->row($this->rowsInGreed)
            ->ColumnID($this->ColumnID)
            ->setDefaultTextForEmptyGreed($this->DefaultTextForEmptyGreed)
            ->setOnClickFunctionForAllTable("clearEditWindow(\"$this->nameEditDIV\")"); //clearEditWindow() функция встроенная в грид очищает блок с указанным именем
        $this->modify_greed_Object();

        if ($this->arrayChecked !== false){
            $this->greed_Object->setArrayChecked($this->arrayChecked);
        }

        $this->standard->resetFetchName();
        // перечисление всех полей таблицы и определение их свойств
        while ($name = $this->standard->fetchName()) {
            $this->greed_Object->Column($name)
                ->Column_Width($this->standard->getWithForGreed())
                ->Column_Caption($this->standard->getCaption());
            if ($typeColumn = $this->standard->getTypeColumn()) {
                $this->greed_Object->$typeColumn($this->standard->getPatternColumn());
            }
            if ($Function_For_BlurEvent = $this->standard->getFunction_For_BlurEvent()) {
                $this->greed_Object->setFunction_For_BlurEvent($Function_For_BlurEvent);
            }

            if ($Onkeyup_func = $this->standard->GetOnkeyup_functionForInputGreed())
                $this->greed_Object->SetOnkeyup_functionForInput($Onkeyup_func);

            $Column_Type = $this->standard->getTypeColumn_Input();
            if ($Column_Type !== false){
                if ($Column_Type == "Column_TypeButtonImg") {
                    $this->greed_Object->setTypeColumn_buttonImg(
                        $this->standard->getTypeColumn_buttonImg_path_name_img(),
                        $this->standard->getTypeColumn_buttonImg_name_callObject(),
                        $this->standard->getTypeColumn_buttonImg_callFunction(),
                        $this->standard->getTypeColumn_buttonImg_displayField()
                    );

                }else if($Column_Type == "Column_ListImage"){
                    $this->greed_Object->setTypeColumn_ListImage(
                        $this->standard->getTypeColumn_ListImage_request(),
                        $this->standard->getTypeColumn_ListImage_size(),
                        $this->standard->getTypeColumn_ListImage_square()

                    );
                }else{
                    $this->greed_Object->$Column_Type();
                }
            }


            $titleField = $this->standard->getTitleField();
            if ($titleField !== false){
                $this->greed_Object->Column_TitleField($titleField);
            }

            $horizontalPos = $this->standard->getHorizontalPos();
            $this->greed_Object->$horizontalPos();

            if ($this->standard->getDynamicBackgroundColor_if()){
                $colors = $this->standard->getDynamicBackgroundColor_color();
                foreach ($colors as $key => $item){
                    $color = $item['hexColor'];
                    $this->greed_Object->dynamicBackgroundColor($item['condition'] ,$color);
                }

            }
            if ($this->standard->getDynamicForeColor_if()){
                $colors = $this->standard->getDynamicForeColor_color();
                foreach ($colors as $key => $item){
                    $color = $item['hexColor'];
                    $this->greed_Object->dynamicForeColor($item['condition'] ,$color);
                }

            }

            if ($func = $this->standard->getHeadFunction()){
                $this->greed_Object->setHeadFunction($func);
            }
        }
        // Окончание перечисления всех полей таблицы и определение их свойств


        if ($this->allInsertOff) {
            $this->greed_Object->allInsertOff();
        }

        if ($this->onClickFunctionForAllTable !== false ) $this->greed_Object->setOnClickFunctionForAllTable($this->onClickFunctionForAllTable);
        if ($this->onClickFunctionForAllTable !== false ) $this->greed_Object->setonDblClickFunctionForAllTable($this->onClickFunctionForAllTable);




        if (is_array($data_Object) === true)
                $this->greed_Object->typeDataArray();

        $this->greed_Object->setVisibleHeadTable($this->visibleHeadTable);
        $this->greed_Object->setLayer($this->l);
        $HTML = $this->greed_Object->GetTable($data_Object);
        $HTML .= $this->greed_postFix();
        return $HTML;
    }

    public function modify_greed_Object()
    {

    }
    public function greed_postFix()
    {
        $HTML = '';
        return $HTML;
    }

    public function createBottomWindowEdit()
    {

        $windowContent = '';

        $this->createButtons();

        $windowContent = $windowContent .
            $this->WND->set()
                ->width($this->formWidth - 20)
                ->nameId($this->nameEditDIV)
                ->headSizeNone()->floatLeft()->shadowSmall()
                ->get();

        $this->windowContent = $this->windowContent . $windowContent;
    }

    public function createButtons()
    {
        $HTML = '';

        $HTML = $HTML .
            $this->WND->set()->nameId($this->nameManageButton)
                ->headSizeNone()->floatLeft()->shadowNone()
                ->content($this->blockManageButton())
                ->get();

        $HTML = $HTML .
            $this->WND->set()->nameId('reportButton')
                ->headSizeNone()->shadowSmall()->floatLeft()
                ->content($this->blockReportsHTML())
                ->get();
        $this->windowContent = $this->windowContent . $HTML;
    }

    public function blockManageButton()
    {
        $ControlElements = new \models\ControlElements();
        $ControlElements->setBlockNum($this->blockNum);
        $HTML =  $ControlElements->getElementsHTTP(get_class($this));
        return $HTML;
    }


    public function blockReportsHTML()
    {
        $HTML = "";

        $ControlElements = new \models\ControlElements();
        $dataClass = $ControlElements->getReports_returnDadaObject(get_class($this));

        while ($res = $dataClass->fetch()) {
            $report = str_replace("\\", "\\\\", trim($res['report']));
            $manageTable = $res['manageTable'];
            $HTML = $HTML . $this->BTN->set($res['name'])
                    ->height(30)->width($this->widthButtonReport)
                    ->floateLeft()
                    ->func("_G_getReport({parent:'$this->objectParentName',r0:'$this->objectName',report:'$report',wait:1,manageTable:'$manageTable',greed:'$this->nameGreed'})")
                    ->fontSmall()
                    ->get();
        }
        return $HTML;
    }

    public function createElementsEdit()
    {
        $HTML = '';
        $this->standardForEdit->resetFetchName();
        while ($name = $this->standardForEdit->fetchName()) {


            // Название переменной в БД в которе запишется значение
            $field = $this->standardForEdit->getFieldForDisplayText();

            $field_displayEdit = $this->standardForEdit->getFieldForDisplayText();
            if ($field_displayEdit === false)
                $field_displayEdit = $name;


            $value = $this->rowDataForEdit[$field_displayEdit];
            // значение обрабатывается согласно указанного типа
            // например дата с сервера приходи в виде текста 11-25-2020 00:00:00.000
            // такой бред нельзя показать пользователю, указав тип данных дата
            // на выходе мы получим 25.10.2020
            $value = $this->standardForEdit->processingAccordingToType($value);


            if ($this->standardForEdit->getFieldForDisplayText() !== false) {
                // Данные которые попадут в отображаеморе название кнопки

                $delimiter = ' - <b>'; // по умолчанию есть разделитель, и значение формируется жирным
                if ($this->standardForEdit->getCaption() == '') // если нет заголовка то разделоитьедль не нужен
                    $delimiter = '';

                $title = $this->standardForEdit->getCaption() . $delimiter . $value ;
            }
            else {
                $title = $this->standardForEdit->getCaption();
            }

            //Шаблон ввода по умолчанию ''
            $pattern = $this->standardForEdit->getPattern();

            // Сообщение пользователю в ворме редактирования
            $message = 'Введите ' . $this->standardForEdit->getCaption();

            // ОПодготвавливаем переменную для отправки в форму корректировки текущее значение.
            // сделано для удобства просмотра или корректировки
            $oldValue = htmlspecialchars(addslashes($value));


            $field_edit = $this->standardForEdit->getNameFieldForEdit();

            if ($catalog = $this->standardForEdit->getCatalog()) { // Если для модификации необходим справочник
                //Функция формирования формы для редактирования значения
                if ($catalog != "address_classifier"){
                    $function = "requestForReplaceValue_Catalog_$this->objectFullName('$catalog','$field_edit','$oldValue')";
                }else{
                    $function = "requestForReplaceValue_address_classifier_$this->objectFullName('$field_edit','$oldValue')";
                }
            } else {
                //Функция формирования формы для редактирования значения
                $function = "requestForReplaceValue_$this->objectFullName('$field_edit','$oldValue','$pattern','$message')";
            }


            // Кнопка :)
            $this->BTN->set($title)
                ->width($this->standardForEdit->getWidth())
                ->height($this->standardForEdit->getHeight())
                ->floateLeft()
                ->horizontalPosLeft()
                ->func($function);
            if ($this->readOnly)
                $this->BTN->setReadOnly();
            if ($this->standardForEdit->getReadOnly())
                $this->BTN->setReadOnly();

            $this->modifyEditElementsFromThisClass();
            $HTML = $HTML . $this->BTN->get();
        }

        // В случае режима разработки вывести все доступные поля
        if ($this->DEV === true) {
            $HTML = $HTML . '</br>' . '</br>' . '</br>' . '</br>' . '</br>' . '</br>' . '</br>';
            foreach ($this->rowDataForEdit as $variable => $Value) {
                $HTML = $HTML . $variable . ' = ' . $Value . '</br>';
            }
        }
        //$HTML = $HTML.$this->BTN->set('')

        $HTML .= $this->addEditElementsFromThisClass();
        return $HTML;
        //$this->rowDataForEdit
    }

    // для дополнительных изменений элемента управления в родительском классе
    // например добавить нетепичное свойство "только для чтения" каким-то конкретным элементам
    //  if ($this->rowDataForEdit['id_month'] != $_SESSION['id_month0'])
    //                $this->BTN->setReadOnly();
    public function modifyEditElementsFromThisClass()
    {

    }

    public function addEditElementsFromThisClass()
    {
        return "";
    }

    public function createBottomWindowSelect()
    {
        $windowContent = '';
        $windowContent = $windowContent . $this->selectBlock();
        $this->windowContent = $this->windowContent . $windowContent;
    }
    public function createBottomWindowSelectForFilterBTN($name_BTN,$refresh_func)
    {
        $this->windowContent = $this->windowContent . $this->selectBlockForFilterBTN($name_BTN,$refresh_func);
    }

    public function selectBlock()
    {
        /**
         * HTML = $this->greed_Object->GNew(\models\ControlElements::get()->getNameMethod($this->objectFullName,__METHOD__))
         * формирование id добавляем через подчер имя метода.
         */
        $id_greed = $this->objectFullName . "_greed";

        $HTML = "";


        $HTML = $HTML .
            $this->BTN->set("Отмена")
                ->height(40)->width(150)
                ->floateLeft()
                ->func("closeBlockAPP()")
                ->get();
        $catalog = $this->className;
        $HTML = $HTML .
            $this->BTN->set("Выбрать")
                ->height(40)->width(150)
                ->floateLeft()
                ->func("_G_buttonSelect('$this->nameGreed','$this->callFunction_txt','$catalog')")
                ->get();

        return $HTML;
    }

    public function selectBlockForFilterBTN($name_BTN,$refresh_func)
    {
        $HTML = "";

        $HTML = $HTML .
            $this->BTN->set("Отмена")
                ->height(40)->width(150)
                ->floateLeft()
                ->func("closeBlockFilterBTN('$name_BTN')")
                ->get();

        $HTML = $HTML .
            $this->BTN->set("Выбрать")
                ->height(40)->width(150)
                ->floateLeft()
                ->func("_G_buttonSelectForFilterBTN('$this->nameGreed','$name_BTN','$refresh_func',)")
                ->get();

        return $HTML;
    }
    public function setDataGridObject($dataGrid_Object)
    {

        $this->dataGrid_Object = $dataGrid_Object;
    }

    /**
     * @param \models\Standards\FieldsForView $standard
     * @return $this
     */
    public function setStandard(\models\Standards\FieldsForView $standard)
    {
        $this->standard = $standard;
        return $this;
    }

    public function setCallFunctionTxt($callFunction_txt)
    {
        $this->callFunction_txt = $callFunction_txt;
    }

    public function printElement($HTML)
    {
        print "<code>";
        print $HTML;
        print "</code>";
        print "<script> function loadscript(){} </script>";
    }

    public function mainWindow()
    {
        $window = new \views\Elements\Window\Window();
        $window->set()->nameId($this->objectFullName)
            ->titleSmall($this->TXT_headSmallTitle)
            ->width($this->formWidth)
            ->content($this->windowContent)
            ->floatLeft();

        if ($this->headNone)
            $window->headSizeNone();
        $ELM = new \views\Elements\VElements();

        $HTML_HideDiv = $ELM->tag("div")
            ->setId("hideBlockForFilter")
            ->setStyle('position: absolute')
            ->setStyle("width:10px ")
            ->setStyle("height:500px ")
            ->setStyle("z-index:999")
            ->setFunction("hidden")
            ->getHTTPTag();

        $this->windowContent = $HTML_HideDiv.$window->get();
    }


    public function includeHtmlFilter()
    {
        ob_start();
        include "HTML_filter.php";
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }


    public function printEmptyLoadScript()
    {
        print "<script> function loadscript(){} </script>";
    }

    public function autoCenterBlock()
    {
        $elements = new \views\Elements\VElements();
        $this->windowContent = $elements->tag("div")->setClass("MsgBlockAPP")
            ->setStyle("top:0px;left:0px;width:{$this->formWidth}px;")// для прорисовки сообщения по центру экрана
            ->setCaption($this->windowContent)->getHTTPTag();
    }

    public function printMainWindowForGreedColumn()
    {

    }
    public function printMainWindowForFilterBTN()
    {
        $HTML = $this->WND->set()->nameId($this->nameGreedDIV."forFilterBTN")
            ->headSizeSmall()
            //->setBtnCloseWindowFunction("alert(1)")
            ->content($this->windowContent)
            ->get();

        print "<code>";
        print $HTML;
        print "</code>";
        print $this->includeHtmlFilter();
    }
}