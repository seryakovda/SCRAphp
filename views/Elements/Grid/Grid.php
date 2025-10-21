<?php

namespace views\Elements\Grid;
class Grid
{

    const horizontalPosLeft = "left";
    const horizontalPosCenter = "center";
    const horizontalPosRight = "right";
    static $typeDataArrayObject = "Object";
    static $typeDataArrayArray = "Array";
    public $GridHead = array();
    public $GridId;
    public $columnId;
    private $row;
    private $allInsertOff;
    private $typeDataArray;
    private $fieldName;
    private $width;
    private $checked;
    private $onClickFunctionForAllTable;
    private $onDblClickFunctionForAllTable;
    private $doNotShowZeros;
    private $autoColumnName = false;
    private $arrayChecked = Array();
    private $readOnly;
    private $DefaultTextForEmptyGreed = 'Нет данных в таблице';
    private $whiteSpace = 'nowrap';
    private $min_heightTable = 0;
    private $visibleHeadTable = true;
    private $l = ""; // типа слой добисывает значение в  стиль грида
    public function GNew($GridId)
    {
        $this->whiteSpace = 'nowrap';
        $this->onClickFunctionForAllTable = false;
        $this->onDblClickFunctionForAllTable = false;
        $this->checked = false;
        $this->GridHead = array();
        $this->GridId = $GridId;
        $this->row = 8;
        $this->allInsertOff = true;
        $this->typeDataArray = $this::$typeDataArrayObject;
        $this->width = false;
        $this->autoColumnName = false;
        $this->DefaultTextForEmptyGreed = 'Нет данных в таблице';
        $this->arrayChecked = false;
        return $this;
    }

    public function setLayer($layerStyle)
    {
        $this->l = $layerStyle;
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
     * @param $DefaultTextForEmptyGreed
     * @return $this
     */
    public function setDefaultTextForEmptyGreed($DefaultTextForEmptyGreed)
    {
        $this->DefaultTextForEmptyGreed = $DefaultTextForEmptyGreed;
        return $this;
    }


    public function setMinHeightTable(int $min_heightTable)
    {
        $this->min_heightTable = $min_heightTable;
        return $this;
    }

    public function autoColumnName(bool $autoColumnName)
    {
        $this->autoColumnName = $autoColumnName;
        return $this;
    }

    public function whiteSpace_preLine()
    {
        $this->whiteSpace = 'pre-line';
        return $this;
    }

    public function setReadOnly($readOnly = true)
    {
        $this->readOnly = $readOnly;
        return $this;
    }


    public function setArrayChecked($arrayChecked)
    {
        $this->arrayChecked = $arrayChecked;
        return $this;
    }


    public function setonDblClickFunctionForAllTable($onDblClickFunctionForAllTable)
    {
        $this->onDblClickFunctionForAllTable = $onDblClickFunctionForAllTable;
        return $this;
    }

    public function setOnClickFunctionForAllTable($onClickFunctionForAllTable)
    {
        $this->onClickFunctionForAllTable = $onClickFunctionForAllTable;
        return $this;
    }

    public function typeDataArray()
    {
        $this->typeDataArray = $this::$typeDataArrayArray;
        return $this;
    }

    public function ColumnID($columnId)
    {
        $this->columnId = $columnId;
        return $this;
    }

    public function checked($checked = "checked")
    {
        $this->checked = $checked;
        return $this;
    }

    /**
     * width ширина колонки
     * input ячейка содержит элемент ввода (не просто текст)
     */
    public function Column($fieldName)
    {
        $this->fieldName = $fieldName;
        $this->GridHead[$this->fieldName]['classFontStyle'] = 'textFontNormal';
        return $this;
    }

    public function Column_set_ClassFontStyle($classFontStyle)
    {
        $this->GridHead[$this->fieldName]['classFontStyle'] = $classFontStyle;
        return $this;
    }


    public function Column_get_ClassFontStyle()
    {
        return $this->GridHead[$this->fieldName]['classFontStyle'];
    }


    public function Column_textFontSmall()
    {
        return $this->Column_set_ClassFontStyle('textFontSmall');
    }


    public function Column_textFontBig()
    {
        return $this->Column_set_ClassFontStyle('textFontBig');
    }

    public function Column_textFontMicro()
    {
        return $this->Column_set_ClassFontStyle('textFontMicro');
    }

    public function setHeadFunction($nameFunction)
    {
        $this->GridHead[$this->fieldName]['HeadFunction'] = $nameFunction;
        return $this;
    }
    /**
     * @param string $_path_name_img путь к изоброажению которое будет выведено в ячейку
     * @param string $_path_name_callObject путь и имя объекта который будет вызван для модификации поля (ApplicationSMTS\\\\SPR_ORG)
     * @param string $_callFunction имя функции при вызове которой произойдёт модивикация поля (должно принимать ID, и newValue)
     * @param string $_displayField имя поля которое будет отображаться
     * @return object
     */
    public function setTypeColumn_buttonImg(string $_path_name_img,string $_path_name_callObject,string $_callFunction, string $_displayField):object
    {
        $this->GridHead[$this->fieldName]["element"] = 'Column_TypeButtonImg';
        $this->GridHead[$this->fieldName]["BTN_path_name_img"] = $_path_name_img;
        $this->GridHead[$this->fieldName]["BTN_path_name_callObject"] = $_path_name_callObject;
        $this->GridHead[$this->fieldName]["BTN_callFunction"] = $_callFunction;
        $this->GridHead[$this->fieldName]["BTN_displayField"] = $_displayField;

        return $this;
    }

    /**
     * @param $request
     * @param $size
     * @param $square
     * @return object
     */
    public function setTypeColumn_ListImage($request ,$size,$square):object
    {
        $this->GridHead[$this->fieldName]["element"] = 'Column_ListImage';
        $this->GridHead[$this->fieldName]["Column_ListImage_request"] = $request;
        $this->GridHead[$this->fieldName]["Column_ListImage_size"] = $size;
        $this->GridHead[$this->fieldName]["Column_ListImage_square"] = $square;

        return $this;
    }

    public function Column_Width($value)
    {
        $this->GridHead[$this->fieldName]["width"] = $value;
        return $this;
    }

    public function Column_Caption($value)
    {
        $this->GridHead[$this->fieldName]["caption"] = $value;
        return $this;
    }

    public function Column_DoNotShowValues($val = '0.00')
    {
        $this->GridHead[$this->fieldName]["DoNotShowValues"] = $val;
        return $this;
    }

    public function Column_TypeInput()
    {
        $this->GridHead[$this->fieldName]["element"] = "input";
        return $this;
    }

    public function Column_TitleField($field)
    {
        $this->GridHead[$this->fieldName]["title"] = $field;
        return $this;
    }


    public function SetOnkeyup_functionForInput($function_and_parameters)
    {
        $this->GridHead[$this->fieldName]["OnkeyupGreed"] = $function_and_parameters;
        return $this;

    }

    public function Column_TypeCheck()
    {
        $this->GridHead[$this->fieldName]["element"] = "Check";
        return $this;
    }
    /*
    const horizontalPosLeft="left";
    const horizontalPosCenter="center";
    const horizontalPosRight="right";
    */
    public function Column_horizontalPosLeft()
    {
        $this->GridHead[$this->fieldName]["horizontalPos"] = self::horizontalPosLeft;
        return $this;
    }

    public function Column_horizontalPosCenter()
    {
        $this->GridHead[$this->fieldName]["horizontalPos"] = self::horizontalPosCenter;
        return $this;
    }

    public function Column_horizontalPosRight()
    {
        $this->GridHead[$this->fieldName]["horizontalPos"] = self::horizontalPosRight;
        return $this;
    }

    public function Column_number_format($number_format = 2)
    {
        $this->GridHead[$this->fieldName]["number_format"] = $number_format;
        return $this;
    }

    public function Column_date_format($date_format = 'd.m.Y')
    {
        $this->GridHead[$this->fieldName]["date_format"] = $date_format;
        return $this;
    }

    /**
     * количество отображаемых строк на экране, остальные прячутся за полосой прокрутки
     * @param $value
     * @return $this
     */
    public function row($value)
    {
        $this->row = $value;
        return $this;
    }

    public function allInsertOff()
    {
        $this->allInsertOff = false;
        return $this;
    }

    public function width($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @param $condition
     * @param $hexColor
     * @return $this
    ->dynamicBackgroundColor('$ROW["totalPayments"]==$ROW["fiscal"]','#2aa53370')
     * ->dynamicBackgroundColor('(($ROW["totalPayments"]!=$ROW["fiscal"]) and ($ROW["fiscal"]!=0))','#a52a4c70')
     *
     */
    public function dynamicBackgroundColor($condition, $hexColor)
    {
        $arr = array('condition' => $condition, 'hexColor' => $hexColor);
        $this->GridHead[$this->fieldName]["dynamicBackgroundColor"][] = $arr;
        return $this;
    }


    public function dynamicForeColor($condition, $hexColor)
    {
        $arr = array('condition' => $condition, 'hexColor' => $hexColor);
        $this->GridHead[$this->fieldName]["dynamicFourColor"][] = $arr;
        return $this;
    }

    public function setFunction_For_BlurEvent($function_For_BlurEvent)
    {
        $this->GridHead[$this->fieldName]["Function_For_BlurEvent"] = $function_For_BlurEvent;
    }
    public function GetTable($data)
    {
        $colRowInData = 0;
        $mainArray = array();
        $element = new \views\Elements\VElements();
        $element_i = new \views\Elements\VElements();

        if ($this->typeDataArray == $this::$typeDataArrayObject) {
            while ($row = $data->fetch()) {
                $colRowInData = $colRowInData + 1;
                if (($this->autoColumnName) && ($colRowInData == 1)){
                    foreach ($row as $key => $value){
                        $this->Column($key)->Column_Caption($key)->Column_Width(500);
                    }
                }
                $mainArray[] = $row;
            }
        } else {
            $colRowInData = count($data);
            $mainArray = $data;
        }
        if ($colRowInData == 0) {
            $allWidth = 0;
            foreach ($this->GridHead as $key => $value) { // перебираем все элементы шапки
                $width = $this->my_array_key_exists('width', $value); // ширина
                $allWidth = $allWidth + $width + 1; //увеличить ширину таблиы на ширину ячеки и пикселя
            }
            /*
            $headGrid = $element->tag("th")// блок с крыжиком который выделяет все строки либо пустая ячейка
            ->setStyle("width:15px")
                ->setStyle("padding-left:0px")
                ->setStyle("border-top-style: none;")
                ->setStyle("border-left-style: none;")
                ->setClass("BorderGrid_td")->getHTTPTag();

            $headGrid = $headGrid . $element->tag("th")// элемент за полосу прокрутки
                ->setStyle("width:17px")
                    ->setStyle("padding-left:0px")
                    //->setClass("BorderGrid_td")
                    ->getHTTPTag();
            */
            $headGrid = $element->tag('tr')// строка заголовка таблицы
            ->setStyle("display:table;width:" . ($this->width - 90) . "px;table-layout:fixed;")
                ->setStyle("height: 30px;")
                ->setCaption("<td>".$this->DefaultTextForEmptyGreed."</td>")
                ->getHTTPTag();
            $headGrid0 = $element->tag("thead")->setCaption($headGrid)->getHTTPTag();


            $returnHTML = $element->tag("table")
                ->setStyle("border-spacing:0px")
                ->setStyle("border-collapse: collapse")
                ->setStyle("margin:5px")
                ->setId($this->GridId)
                ->setClass("BorderGrid test")
                ->setCaption($headGrid0)
                ->getHTTPTag();
            return $returnHTML;
        }

        if ($this->width) {
            $this->transformWidthColumn();
        }
        $allWidth = 0;//Вся ширина таблицы
        $allTop = 0;
        $inp = "";
        if ($this->allInsertOff) { //кнопику которая выделяет все строки грида
            $func = "$('#" . $this->GridId . "').find('." . $this->GridId . "').prop('checked', this.checked);";
            $inp = $element->tag("input")->setFunction('type="checkbox"')->setFunction('onclick="' . $func . '"')
                ->getHTTPTag();
        }

        $headGrid = $element->tag("th")// блок с крыжиком который выделяет все строки либо пустая ячейка
        ->setStyle("width:15px")
            ->setStyle("padding-left:0px")
            ->setStyle("border-top-style: none;")
            ->setStyle("border-left-style: none;")
            ->setClass("BorderGrid_td")->setCaption($inp)->getHTTPTag();

        $allWidth = $allWidth + 48; //общую ширину увеличиываем на 2 блока (первый 20 пикселей который выделяет все строи и второй 21 пиксель полоса прокрутки)
        $previousColumn = false;
        $previousColumn_one = '';

        foreach ($this->GridHead as $key => $value) { // перебираем все элементы шапки

            if ($previousColumn === false){
                $previousColumn_one = $key;
            }else{
                $this->GridHead[$previousColumn]['nextColumn'] = $key;
            }
            $previousColumn = $key;


            $width = $this->my_array_key_exists('width', $value) - (5 + 5); //отнят padding-left: 5px  padding-rigth: 5px
            $element->tag("th")
                    ->setStyle("width:" . ($width) . "px")// ширина
                    ->setStyle("overflow:hidden;")// непоказывать выступающие элементы
                    ->setStyle("text-align: center;")
                    ->setStyle("border-top-style: none;")
                    ->setClass("BorderGrid_td")
                    ->setCaption($this->my_array_key_exists('caption', $value));
            if ($func = $this->my_array_key_exists('HeadFunction', $value,false)){
                $element
                    ->setClass("MyButton")
                    ->setFunction("onclick=\"$func\"");
            }

            $headGrid = $headGrid . $element->getHTTPTag();

            $allWidth = $allWidth + $width + 6 + 5; //увеличить ширину таблиы на ширину ячеки и пикселя
        }
        $this->GridHead[$previousColumn]['nextColumn'] = $previousColumn_one;

        $headGrid = $headGrid . $element->tag("th")// элемент за полосу прокрутки
            ->setStyle("width:17px")
                ->setStyle("padding-left:0px")
                //->setClass("BorderGrid_td")
                ->getHTTPTag();
        $headGrid = $element->tag('tr')// строка заголовка таблицы
        ->setStyle("display:table;width:" . ($allWidth + 2) . "px;table-layout:fixed;")
            ->setStyle("height: 30px;")
            ->setCaption($headGrid)
            ->getHTTPTag();

        $bodyGrid = "";
        $factRows = 0;
        $id_OLD = '';
        $num_color_row = 0;
        foreach ($mainArray as $mainKey => $ROW) {
            $factRows++;
            $id = $ROW[$this->columnId];
            $id = " " . $id . " ";
            $id = preg_replace("/\s+/", "", $id);
            $nameForidInput = $id;
            $id = str_replace("\\", "_", $id);

            //  $allWidth = 0;
            $srtoka = "";
            $tableGrid = "";

            $func = "";
            if (!$this->allInsertOff) {
                $func = "$('#" . $this->GridId . "').find('." . $this->GridId . "').prop('checked', false);";
                $checkFlag = "true";
            } else {
                $checkFlag = $func . "!$('.$this->GridId.$id').prop('checked')";
            }
            //$func = $func."$('.".$this->GridId.".".$id."').prop('checked',true)";
            $func = $func . "$('.$this->GridId.$id').prop('checked',$checkFlag)";

            $element_i->tag("input")// элемент управления строкой input который помечает выбраную строку
            ->setClass($this->GridId)
                ->setClass($id)
                ->setFunction('type="checkbox"')
                ->setFunction('readonly="readonly"')

                ->setFunction("name='" . $nameForidInput . "'");

            if ($this->readOnly !== true){
                $element_i->setFunction('onclick="' . $func . '"');
            }

            if ($colRowInData == 1){ // если одна запись в таблице то сразу помечаем её как выделенную
                $element_i->setFunction(" checked ");
            }

            //Обеспечиве построение крыжиков согласно указанному полю по умолчанию checked
            if ($this->checked) {
                if ($valCheck = $this->my_array_key_exists($this->checked, $ROW, false)) {
                    if ($valCheck != 0) $element_i->setFunction(" checked ");
                }
            }

            // обеспечивает построение крыжика согласно массива

            if ($this->arrayChecked != false){
                if (in_array($id,$this->arrayChecked)){
                    $element_i->setFunction(" checked ");
                }
            }

            //$allWidth = $allWidth + 50;
            $num_kolumn = 1;
            foreach ($this->GridHead as $headColName => $headCol) {
                $headColName = " " . $headColName . " ";
                $headColName = preg_replace("/\s+/", "", $headColName);

                $width = $this->my_array_key_exists('width', $headCol) - (5 + 5); //отнят padding-left: 5px  padding-rigth: 5px
                if (array_key_exists($headColName, $ROW)) {
                    $val = $ROW[$headColName];
                    if ($number_format = $this->my_array_key_exists('number_format', $headCol)) {
                        if ($number_format == -1)
                            $val = (float)$val;
                        else
                            $val = number_format($val, $number_format, '.', ' ');
                    }
                    if ($values = $this->my_array_key_exists('DoNotShowValues', $headCol)) {
                        $val = $values == $val ? '' : $val;
                    }
                    //
                    if ($date_format = $this->my_array_key_exists('date_format', $headCol)) {
                        if ($val != Null)
                            $val = date($date_format, strtotime($val));
                        else
                            $val = '';
                    }

                    $element_i->setFunction("data-$headColName = '$val'"); // помещаем Значение всех полей в крыжик.

                    $tElement = $this->my_array_key_exists('element', $headCol);

                    switch ($tElement){
                        case "input":
                            $element->tag("input")
                                ->setClass($this->GridId)
                                ->setClass($this->GridId . "_" . $headColName)
                                ->setStyle("text-align:right")
                                ->setStyle("width:" . $width . "px")
                                ->setClass($this->GridId . "_" . $num_kolumn . "_" . $factRows)
                                ->setClass("GridInput inpData")
                                ->setFunction("data-column='" . $this->GridId . "_'")
                                ->setFunction("data-numColumn='" . $num_kolumn . "'")
                                ->setFunction("data-row='" . $factRows . "'")
                                ->setFunction("data-field='" . $headColName . "'")
                                ->setFunction('onfocus="eventOnFocus(this,event)"')
                                ->setFunction('autocomplete="off"')
                                ->setFunction("name='" . $id . "'")
                                ->setFunction("value='" . $val . "'")
                                ->setStyle("color: black");

                            $Onkeyup_func = $this->my_array_key_exists('OnkeyupGreed', $headCol, 'eventGreed(this,event)');
                            $element->setFunction('onkeyup="' . $Onkeyup_func . '"');

                            if ($number_format !== false) {
                                $element->setFunction("pattern='[0­9]'");
                            }
                            if ($Function_For_BlurEvent = $this->my_array_key_exists('Function_For_BlurEvent', $headCol)) {
                                $element->setFunction("onBlur='$Function_For_BlurEvent'");
                            }
                            if ($textAlign = $this->my_array_key_exists('horizontalPos', $headCol)) {
                                $element->setStyle("text-align:$textAlign");
                            }

                            $val = $element->getHTTPTag();
                            break;
                        case "Check":
                            $element->tag("input")
                                // ->setClass($this->GridId)
                                ->setClass($this->GridId . "_" . $headColName)
                                ->setStyle("text-align:right")
                                ->setStyle("width:" . $width . "px")
                                //->setClass($this->GridId . "_" . $num_kolumn . "_" . $factRows)
                                //->setClass("GridInput inpData")
                                ->setFunction('type="checkbox"')
                                ->setFunction("data-column='" . $this->GridId . "_" . $num_kolumn . "'")
                                ->setFunction("data-row='" . $factRows . "'")
                                ->setFunction("data-field='" . $headColName . "'")
                                //->setFunction('onkeyup="eventGreed(this,event)"')
                                //->setFunction('onfocus="eventOnFocus(this,event)"')
                                ->setFunction('autocomplete="off"')
                                ->setFunction("name='" . $id . "'")
                                ->setFunction("value='" . $val . "'");

                            if ($val != '0'){
                                $element->  setFunction("checked");
                            }

                            /*
                            if ($number_format!==false){
                                $element->setFunction("pattern='[0­9]'");
                            }
                            if ($Function_For_BlurEvent = $this->my_array_key_exists('Function_For_BlurEvent', $headCol)){
                                $element->setFunction("onBlur='$Function_For_BlurEvent'");
                            }
                            if ($textAlign = $this->my_array_key_exists('horizontalPos', $headCol)) {
                                $element->setStyle("text-align:$textAlign");
                            }
    */
                            $val = $element->getHTTPTag();
                            break;
                        case "Column_TypeButtonImg":
                            $imageFile = $this->GridHead[$this->fieldName]["BTN_path_name_img"];
                            $nameCallSpr = $this->GridHead[$this->fieldName]["BTN_path_name_callObject"];// какой справочник вызывать
                            $BTN_callFunction = $this->GridHead[$this->fieldName]["BTN_callFunction"];
                            $widthColumn = $this->GridHead[$this->fieldName]['width'] - 40;
                            $nameIdCell = "{$headColName}_$id";
                            $old_captionSpr = $ROW[$this->GridHead[$this->fieldName]["BTN_displayField"]];
                            $img = $element->tag("div")
                                ->setId("{$nameIdCell}_img")
                                //                               _GREED_callSprInCell(nameIdCell,   idRowInGreed,     old_captionSpr,     nameCallSpr,    callBackFunction,    widthColumn)
                                ->setFunction("onclick=\"_GREED_callSprInCell('$nameIdCell','$id',            '$old_captionSpr',  '$nameCallSpr', '$BTN_callFunction', $widthColumn)\"")
                                ->setFunction("data-old_idSpr = $val")
                                ->setStyle("background: url(" . $imageFile . ") no-repeat center")
                                ->setStyle("background-size:contain")
                                ->setStyle("height:20px")
                                ->setStyle("width:20px")
                                ->setStyle("float:left")
                                ->setStyle("margin-right:5px")
                                ->getHTTPTag();
                            $divInfoList = $element->tag("div")
                                ->setId($nameIdCell)
                                ->setCaption($old_captionSpr)
                                ->setStyle("float:left")
                                ->getHTTPTag();
                            $val = $img . $divInfoList;
                            break;
                        case "Column_ListImage":
                            $size = $this->GridHead[$this->fieldName]["Column_ListImage_size"] ;
                            $square = $this->GridHead[$this->fieldName]["Column_ListImage_square"]  ;
                            $request = $this->GridHead[$this->fieldName]["Column_ListImage_request"] ;
                            $val = $ROW[$headColName];
                            $listImage = explode(';',$val);
                            $HTML = "";
                            $i = 1;
                            foreach ($listImage as $item){
                                $img = new \views\Elements\Media\Media();
                                $img->height($size);// высоту
                                if ($square)
                                    $img->width($size);
                                $img->floateLeft();
                                $HTML = $HTML . $img->image("$request$item"); ///


                                $i ++ ;
                                //if ($i > 3) break;
                            }
                            $val = $HTML;

                            break;

                    }

                }else {
                    $val = "NoField";
                }


                $element->tag("td")
                    ->setId($headColName)
                    ->setClass("BorderGrid_td")
                    ->setClass($headCol['classFontStyle'])
                    ->setStyle("width:" . $width . "px")
                    ->setStyle("overflow:hidden")
                    ->setStyle("white-space:$this->whiteSpace");
                if (array_key_exists("title",$this->GridHead[$headColName])){
                    $titleField = $this->GridHead[$headColName]["title"];
                    if (array_key_exists($titleField, $ROW)) {
                        $title =$ROW[$titleField];
                        $element->setFunction("title='$title'");
                    }
                }


                if (is_array($dynamicBackgroundColor = $this->my_array_key_exists('dynamicBackgroundColor', $headCol))) {
                    if ($hexColor = $this->dynamicBackgroundColor_processing($ROW, $dynamicBackgroundColor)) {
                        $element->setStyle("background-color:$hexColor");
                    }
                }
                if (is_array($dynamicFourColor = $this->my_array_key_exists('dynamicFourColor', $headCol))) {
                    if ($hexColor = $this->dynamicFourColor_processing($ROW, $dynamicFourColor)) {
                        $element->setStyle("color:$hexColor");
                    }
                }
                if ($textAlign = $this->my_array_key_exists('horizontalPos', $headCol)) {
                    $element->setStyle("text-align:$textAlign");
                }

                $tableGrid = $tableGrid . $element
                        ->setCaption($val)
                        ->getHTTPTag();
                // $allWidth = $allWidth + $width + 0;
                $num_kolumn = $num_kolumn + 1;
            }


            $inp0 = $element_i->getHTTPTag();
            $element_i->tag("td")// оборачиваем элемент управления в ячейку таицы
            ->setStyle("width:15px;")//ширина 20 пикселей
            ->setStyle("padding-left:0px")
                ->setStyle("border-left-style: none;")
                ->setClass("id")
                ->setClass("BorderGrid_td")
                ->setCaption($inp0);
            $tableGrid_i =  $element_i->getHTTPTag(); // крыжик управелния строкой в ячейке

            $tableGrid =  $tableGrid_i . $tableGrid  . $element->tag("td")->setStyle("width:14px")->setCaption(" ")->getHTTPTag();
//

            $element->tag('tr')->setId($id)
                    ->setStyle("display:table;width:" . ($allWidth) . "px;table-layout:fixed;height:20px;overflow:hidden;")
                    ->setFunction('onclick="' . $func . '"')
                    ->setCaption($tableGrid);
            if ($id != $id_OLD) {
                $num_color_row++;
                $id_OLD = $id;
            }
            if (bcmod($num_color_row,2) == 1){
                $element->setClass("GridLine_grey".$this->l);
            }else{
                $element->setClass("GridLine".$this->l);
            }

            $bodyGrid = $bodyGrid . $element->getHTTPTag();
        }

        if ($this->visibleHeadTable === true){
            $headGrid0 = $element->tag("thead")->setCaption($headGrid)->getHTTPTag();
        }else{
            $headGrid0="";
        }


        $factRows = $factRows < $this->row ? $factRows : $this->row;

        //$this->min_heightTable = $this->min_heightTable >= ((21 * $factRows)) ? $this->min_heightTable : ((21 * $factRows)+1);
        $this->min_heightTable = $this->row * 21;
        $bodyGrid0 = $element->tag('tbody')
            ->setStyle("display: block;max-height:" . $this->min_heightTable . "px;width: " . ($allWidth + 1) . "px;overflow-x:hidden; overflow-y:auto;")
            ->setCaption($bodyGrid)->getHTTPTag();

        $element->tag("table")
//            ->setStyle("width:".(100+(25))."px")
            ->setStyle("border-spacing:0px")
            ->setStyle("border-collapse: collapse")
            ->setStyle("margin:5px")
            ->setId($this->GridId)
            ->setClass("BorderGrid test")
            ->setCaption($headGrid0 . $bodyGrid0);

        if ($this->readOnly !== true) {
            if ($this->onClickFunctionForAllTable !== false) $element->setFunction("onclick='{$this->onClickFunctionForAllTable}'");
            if ($this->onDblClickFunctionForAllTable !== false) $element->setFunction("ondblclick='{$this->onDblClickFunctionForAllTable}'");
        }
        $returnHTML = $element->getHTTPTag();

//        print"<code>";
//        print "$returnHTML";
//        print"</code>";
        ob_start();
        require "HTML.php";
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    public function my_array_key_exists($key, $array, $ret = "")
    {
        if (array_key_exists($key, $array)) {
            return $array[$key];
        } else {
            return $ret;
        }
    }

    private function transformWidthColumn()
    {
        $widthAllColumn = 0;
        foreach ($this->GridHead as $key => $value) {
            $widthAllColumn = $widthAllColumn + $this->my_array_key_exists('width', $value);
        }
        if ($this->width - 95 < $widthAllColumn) {
            $coefficient = ($this->width - 95) / $widthAllColumn;
            foreach ($this->GridHead as $key => $value) {
                $this->GridHead[$key]['width'] = round($this->my_array_key_exists('width', $value) * $coefficient);
            }
        }
    }

    private function dynamicBackgroundColor_processing($ROW, $condition_array)
    {
        $hexColor = false;
        foreach ($condition_array as $key => $value) {
            $color = $value['hexColor'];

            if (substr($color,0,1) == "@")
                $color = $ROW[substr($color,1)];

            if (substr($color,0,1) !== "#")
                $color = false;

            $condition = $value['condition'];
            $res = false;
            $condition = '$res = ' . $condition . ';';
            eval($condition);

            if ($res && ($color !== false)) $hexColor = $color;
        }
        return $hexColor;
    }

    private function dynamicFourColor_processing($ROW, $condition_array)
    {
        $hexColor = false;
        foreach ($condition_array as $key => $value) {
            $condition = $value['condition'];
            $res = false;
            $condition = '$res = ' . $condition . ';';
            eval($condition);
            if ($res) $hexColor = $value['hexColor'];
        }
        return $hexColor;
    }
}

