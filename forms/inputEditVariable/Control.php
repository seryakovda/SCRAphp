<?php

namespace forms\inputEditVariable;


class Control extends \forms\FormsControl
{

    private $route;
    private $className;
    private $methodName;
    private $varName;
    private $oldValue;
    private $callFunction;
    private $functionRefresh;
    private $Properties;
    private $massage;
    private $pattern;
    private $placeholder;
    private $AllInsertOff;
    private $_REQUEST_Array;
    private $password = false;
    /**
     * @var \forms\FormsControl
     */
    private $run;

    function __construct()
    {
        parent::__construct();
    }

    public function defineFilterParent($filterParent = false, $fieldName = false, $value = false)
    {
        $this->filterParent = $filterParent;
        $this->filterPatentFieldName = $fieldName;
        $this->filterParentValue = $value;
    }

    public function run()
    {
        $this->_REQUEST_Array = Array();
        $this->_REQUEST_Array = $_REQUEST;

        $this->route = empty($this->_REQUEST_Array["r1"]) ? "defaultMethod" : $this->_REQUEST_Array["r1"];
        $this->className = empty($this->_REQUEST_Array["className"]) ? "" : $this->_REQUEST_Array["className"];
        $this->methodName = empty($this->_REQUEST_Array["methodName"]) ? "" : $this->_REQUEST_Array["methodName"];
        $this->varName = empty($this->_REQUEST_Array["varName"]) ? "" : $this->_REQUEST_Array["varName"];
        $this->oldValue = empty($this->_REQUEST_Array["oldValue"]) ? "" : $this->_REQUEST_Array["oldValue"];
        $this->callFunction = empty($this->_REQUEST_Array["callFunction"]) ? "" : $this->_REQUEST_Array["callFunction"];
        $this->functionRefresh = empty($this->_REQUEST_Array["functionRefresh"]) ? "" : $this->_REQUEST_Array["functionRefresh"];
        $this->massage = empty($this->_REQUEST_Array["message"]) ? "" : $this->_REQUEST_Array["message"];
        $this->pattern = empty($this->_REQUEST_Array["pattern"]) ? false : $this->_REQUEST_Array["pattern"];
        $this->placeholder = empty($this->_REQUEST_Array["placeholder"]) ? false : $this->_REQUEST_Array["placeholder"];
        $this->AllInsertOff = empty($this->_REQUEST_Array["AllInsertOff"]) ? true : false;

        $this->password = array_key_exists('password',$this->_REQUEST_Array) ? $this->_REQUEST_Array['password'] : false;

        if (method_exists($this, $this->route)) {
            $runMethod = $this->route;
            $this->$runMethod();
        } else {
            $this->defaultMethod();
        };

    }


    public function defaultMethod()
    {
        print '<code>';
        print 'Косяк имени функции';
        print '</code>';
    }

    /**
     * message
     * callFunction
     */
    private function yesOrNotButtons()
    {
        $window = new \views\Elements\Window\Window();
        $elements = new \views\Elements\VElements();
        $text = new \views\Elements\MyText\MyText();
        $button = new \views\Elements\Button\Button();

        if ($_SESSION["mobile"]  == 'workstation'){
            $widthWindow = 600;
            $left1 = 90;
            $left2 = 300;
            $height1 = 150;
            $height2 = 150;
        }else{
            $widthWindow = 320;
            $left1 = 50;
            $left2 = 50;
            $height1 = 100;
            $height2 = $height1 + 45;
        }

        $mainMessage = $text// заголовок с названием изменяемого
        ->text($this->massage)
            ->class_("textColorBlack")
            ->topLeft(10, 20)
            ->position("absolute")->borderOff()->fontSizeBig()
            ->get();

        $closeBottom = $button//кнопка отмена
        ->set("Нет")
            ->topLeft($height1, $left1)->height(30)->width(200)->position("absolute")->func("closeBlockAPP()")
            ->get();

        $yesBottom = $button//кнопка Да
        ->set("Да")
            ->topLeft($height2, $left2)->height(30)->width(200)->position("absolute")
            ->func("HTML_yesOrNotButton()")
            ->get();



        $windows = $window
            ->set()->titleSmall("Внимание !!!")
            ->nameId($this->objectFullName)
            ->top(0)->left(0)->height(240)->width($widthWindow)->sizeHead($window::sizeHeadWinSmall)
            ->setBtnCloseWindowFunction('closeBlockAPP()')
            ->content($mainMessage . $closeBottom . $yesBottom)
            ->get();

        $HTML = $elements->tag("div")->setClass("MsgBlockAPP")
            ->setStyle("top:0px;left:0px;width:{$widthWindow}px;height:465px ")// для прорисовки сообщения по центру экрана
            ->setCaption($windows)
            ->getHTTPTag();
        include "HTML_yesOrNotButton.php";
    }


    /**
     * message
     * callFunction
     */
    private function Calendar()
    {
        $window = new \views\Elements\Window\Window();
        $elements = new \views\Elements\VElements();
        $calendar = new \views\Elements\calendar\Calendar();

        $HTML = "";
        $widthWindow = 275;
        $HTML .= $calendar
            ->setId("SCBA")
            ->setFunction("HTML_yesOrNotButton()")
            ->setCaption("Месяц")
            ->setYear($_SESSION["id_month0_year"])
            ->setCloseFunction('closeBlockAPP()')
            ->upendButton_Infinity()
            ->BodyOnly();

        $windows = $window
            ->set()->titleSmall("Календарь")
            ->nameId($this->objectFullName)
            ->top(0)->left(0)->height(260)->width($widthWindow)->sizeHead($window::sizeHeadWinSmall)
            ->setBtnCloseWindowFunction('closeBlockAPP()')
            ->content($HTML)
            ->get();

        $HTML = $elements->tag("div")->setClass("MsgBlockAPP")
            ->setStyle("top:0px;left:0px;width:280px;height:465px ")// для прорисовки сообщения по центру экрана
            ->setCaption($windows)
            ->getHTTPTag();
        include "HTML_yesOrNotButton.php";
    }
    /**
     * message
     *
     */
    private function messageReadeOnly()
    {
        $window = new \views\Elements\Window\Window();
        $elements = new \views\Elements\VElements();
        $text = new \views\Elements\MyText\MyText();
        $button = new \views\Elements\Button\Button();


        if ($_SESSION["mobile"]  == 'workstation'){
            $widthWindow = 600;
            $left1 = 195;
            $left2 = 300;
            $height1 = 150;
            $height2 = 150;
        }else{
            $widthWindow = 320;
            $left1 = 50;
            $left2 = 50;
            $height1 = 100;
            $height2 = $height1 + 45;
        }

        $mainMessage = $text
            ->text($this->massage)
            ->class_("textColorBlack")
            ->topLeft(10, 20)
            ->position("absolute")->borderOff()->fontSizeBig()
            ->get();
        if ($this->callFunction == "")
            $this->callFunction = "closeBlockAPP()";

        $closeBottom = $button//кнопка отмена
        ->set("ОК")
            ->topLeft(150, $left1)->height(30)->width(200)->position("absolute")->func($this->callFunction)
            ->get();


        $windows = $window
            ->set()->titleSmall("Внимание !!!")
            ->nameId($this->objectFullName)
            ->top(0)->left(0)->height(240)->width($widthWindow)->sizeHead($window::sizeHeadWinSmall)
            ->setBtnCloseWindowFunction($this->callFunction)
            ->content($mainMessage . $closeBottom)
            ->get();

        $HTML = $elements->tag("div")->setClass("MsgBlockAPP")
            ->setStyle("top:0px;left:0px;width:{$widthWindow}px;height:465px ")// для прорисовки сообщения по центру экрана
            ->setCaption($windows)->getHTTPTag();
        print "<code> $HTML </code>";
    }

    /**
     * callFunction вункция JS которая будет вызвана при нажатии [выбрать]
     * ===========calss============== НЕ!!!!! className!!!!!  содержит полный путь между forms  и control.php (SPR\Memu SPR\Street)
     * AllInsertOff по умолчанию если не присутствует то false если присутствует то true
     *
     * Допустим параметр filterArray
     * поле фильтрации [field]
     * filterArray['field']['value']='значение фильтрации'
     * filterArray['field']['znak']='Условие фильтрации (равно неравно)'

     * ПРИМЕР ИСПОЛЬЗОВАНИЯ фильтрации поля [typeObject]
       var filterArray={};
       var field = {}
       field['value'] = 123;
       field['znak'] = '=';
       filterArray['typeObject'] = field;

     */
    private function executeCatalog()
    {
        $className = empty($this->_REQUEST_Array["_class"]) ? "" : $this->_REQUEST_Array["_class"];
        $class = "\\forms\\$className\\Control";

        $this->run = new $class;

        if (array_key_exists('filterArray', $_REQUEST)) {
            $filterArray = $_REQUEST['filterArray'];
            $filterArray = json_decode($filterArray, true);
            foreach ($filterArray as $field => $value) {
                $this->run->setFilterGlobal($field, $value['value'], $value['znak']);
            }
        }
        $this->run->setAllInsertOff($this->AllInsertOff);
        //$run->setBtnCloseWindowFunction('closeBlockAPP()');

        $this->run->formForSelect($this->_REQUEST_Array['callFunction']);
    }


    /**
     * callFunction вункция JS которая будет вызвана при нажатии [выбрать]
     * ===========calss============== НЕ!!!!! className!!!!!  содержит полный путь между forms  и control.php (SPR\Memu SPR\Street)
     * AllInsertOff по умолчанию если не присутствует то false если присутствует то true
     *
     * Допустим параметр filterArray
     * поле фильтрации [field]
     * filterArray['field']['value']='значение фильтрации'
     * filterArray['field']['znak']='Условие фильтрации (равно неравно)'

     * ПРИМЕР ИСПОЛЬЗОВАНИЯ фильтрации поля [typeObject]
    var filterArray={};
    var field = {}
    field['value'] = 123;
    field['znak'] = '=';
    filterArray['typeObject'] = field;

     */
    private function executeCatalogColumnGreed()
    {
        $className = empty($this->_REQUEST_Array["_class"]) ? "" : $this->_REQUEST_Array["_class"];
        $class = "\\forms\\$className\\Control";

        $this->run = new $class;
        $this->run->defineObjectName('cell'.$_REQUEST['idRowInGreed']);

        if (array_key_exists('filterArray', $_REQUEST)) {
            $filterArray = $_REQUEST['filterArray'];
            $filterArray = json_decode($filterArray, true);
            foreach ($filterArray as $field => $value) {
                $this->run->setFilterGlobal($field, $value['value'], $value['znak']);
            }
        }
        $this->run->setAllInsertOff($this->AllInsertOff);

        $this->run->formForGreedColumn($this->_REQUEST_Array['callFunction']);
    }

    private function executeCatalogForFilterBTN()
    {
        $className = empty($this->_REQUEST_Array["_class"]) ? "" : $this->_REQUEST_Array["_class"];
        $class = "\\forms\\$className\\Control";

        $this->run = new $class;
        //$this->run->defineObjectName('cell'.$_REQUEST['idRowInGreed']);

        if (array_key_exists('filterArray', $_REQUEST)) {
            $filterArray = $_REQUEST['filterArray'];
            $filterArray = json_decode($filterArray, true);
            foreach ($filterArray as $field => $value) {
                $this->run->setFilterGlobal($field, $value['value'], $value['znak']);
            }
        }
        $this->run->setAllInsertOff(false);

        $this->run->formForFilterBTN();
    }
    /**
     *  message
     *  oldValue
     *  callFunction
     *  pattern (шаблон(маска) ввода реализованый на JQuery)
     *  placeholder (Объек,функия ля привязки к шаблону(маски) ввода)
     *                        -pattern-    ---placeholder------
     * jQuery('.money').mask('## ##0.00'   , { reverse: true, }     );
     *
     * calendarHelp при наличии данного параметра подставляется визуальный календарь
     */
    private function editVariable()
    {
        $window = new \views\Elements\Window\Window();
        $elements = new \views\Elements\VElements();
        $text = new \views\Elements\MyText\MyText();
        $button = new \views\Elements\Button\Button();
        $input = new \views\Elements\Input\Input();

        $calendarHelp = !empty($_REQUEST["calendarHelp"]);
        if ($this->pattern == '99.99.9999') $calendarHelp = true;

        $windowHeight = 240;
        if ($_SESSION["mobile"]  == 'workstation'){
            $widthWindow = 600;
            $left1 = 90;
            $left2 = 300;
            $left3 = 20;
            $height0 = 70;
            $height1 = 150;
            $height2 = 150;
            $widtt1 = 550;
            if ($calendarHelp){
                $windowHeight = 440;
                $widthWindow = 320;
                $left1 = 50;
                $left2 = 50;
                $left3 = 5;
                $height0 = 70;
                $height1 = $height0 + 225;
                $height2 = $height1 + 45;
                $widtt1 = 300;
            }

        }else{
            $widthWindow = 320;
            $left1 = 50;
            $left2 = 50;
            $left3 = 5;
            $height0 = 70;
            $height1 = $height0 + 45;
            $height2 = $height1 + 45;
            $widtt1 = 300;
        }

        $mainMessage = $text
            ->text($this->massage.$this->password)
            ->class_("textColorBlack")
            ->topLeft(10, 20)->position("absolute")->borderOff()->fontSizeBig()
            ->get();
        $input
            ->set("")
            ->top($height0)->left($left3)->position("absolute")
            ->width($widtt1)
            ->NameId("inputEditValue")
            ->value($this->oldValue);
            //->functionOnKeyUp("functionKeyUp(this)")
//            ->pattern()
        if ($this->password == "true")
            $input->password();

        $inputText = $input->get();
        $closeBottom = $button
            ->set("Отмена")
            ->topLeft($height1, $left1)->height(30)->width(200)->position("absolute")->backgroundColorClass("MyButtonRed")->func("closeBlockAPP()")
            ->get();
        $appleyBottom = $button
            ->set("OK")
            ->topLeft($height2, $left2)->height(30)->width(200)->position("absolute")->backgroundColorClass("MyButtonGreen")->func("saveDate()")// нужно указать функцию которая запустит метод сохранения
            ->get();
        $windows = $window
            ->set()->titleSmall("Редактирование")
            ->nameId($this->objectFullName)
            ->top(0)->left(0)->height($windowHeight)->width($widthWindow)->sizeHead($window::sizeHeadWinSmall)
            ->setBtnCloseWindowFunction('closeBlockAPP()')
            ->content($mainMessage . $inputText . $closeBottom . $appleyBottom)

            ->get();
        $HTML = $elements->tag("div")->setClass("MsgBlockAPP")
            ->setStyle("top:0px;left:0px;width:447px;height:465px ")// для прорисовки сообщения по центру экрана
            ->setCaption($windows)->getHTTPTag();

        include("HTML_editValue.php");
    }


    private function address_classifier()
    {
        $window = new \views\Elements\Window\Window();
        $elements = new \views\Elements\VElements();
        $text = new \views\Elements\MyText\MyText();
        $button = new \views\Elements\Button\Button();
        $input = new \views\Elements\Input\Input();

        $widthWindow = 600;
        $windowHeight = 650;
        $left1 = 90;
        $left2 = 300;
        $left3 = 20;
        $height0 = 440;
        $height1 = 700;
        $height2 = 700;
        $widtt1 = 540;
        $mainMessage = $text
            ->text("Классификатор адресов </br><b>".$this->oldValue."</b>")
            ->class_("textColorBlack")
           // ->fontSizeSmall()
            ->topLeft(10, 20)->position("absolute")->borderOff()
            ->get();
        $inp = new \forms\inputEditVariable\address_classifier_inputBlock();


        $inputText = '';
        $inputText .= $inp->setName("room")->setCaption("Помещение (Квартира)")->setTop($height0)->setLeft($left3)->setWidth($widtt1)
            ->getHtmlInput();
        $height0 -= 80;

        $inputText .= $inp->setName("house")->setCaption("Здание (дом)")->setTop($height0)->setLeft($left3)->setWidth($widtt1)
            ->getHtmlInput();
        $height0 -= 80;

        $inputText .= $inp->setName("street")->setCaption("Улица")->setTop($height0)->setLeft($left3)->setWidth($widtt1)
            ->getHtmlInput();
        $height0 -= 80;

        $inputText .= $inp->setName("town")->setCaption("Город")->setTop($height0)->setLeft($left3)->setWidth($widtt1)
            ->getHtmlInput();
        $height0 -= 80;

        $inputText .= $inp->setName("district")->setCaption("Район")->setTop($height0)->setLeft($left3)->setWidth($widtt1)
            ->getHtmlInput();
        $height0 -= 80;

        $inputText .= $inp->setName("area")->setCaption("Область")->setTop($height0)->setLeft($left3)->setWidth($widtt1)
            ->getHtmlInput();


        $height0 = 560;

        $closeBottom = $button
            ->set("Отмена")->nameId('CancelButton')
            ->topLeft($height0, $left1)->height(30)->width(200)->position("absolute")->func("closeBlockAPP()")
            ->get();
        $appleyBottom = $button
            ->set("OK")->nameId('OkButton')
            ->topLeft($height0, $left2)->height(30)->width(200)->position("absolute")->func("saveDate()")// нужно указать функцию которая запустит метод сохранения
            ->get();
        $windows = $window
            ->set()->titleSmall("Редактирование")
            ->nameId($this->objectFullName)
            ->top(0)->left(0)->height($windowHeight)->width($widthWindow)->sizeHead($window::sizeHeadWinSmall)
            ->content($mainMessage . $inputText . $closeBottom . $appleyBottom)
            ->setBtnCloseWindowFunction('closeBlockAPP()')
            ->get();
        $HTML = $elements->tag("div")->setClass("MsgBlockAPP")
            ->setStyle("top:0px;left:0px;width:{$widthWindow}px;height:{$windowHeight}px ")// для прорисовки сообщения по центру экрана
            ->setCaption($windows)->getHTTPTag();

        include("HTML_address_classifier.php");
    }

    public function filterGAR()
    {
        $MODEL = new \forms\inputEditVariable\filterGAR_MODEL();
        $VIEW = new \forms\inputEditVariable\filterGAR_VIEW();
        $dataArray = $MODEL->getData();
        $nameMethod = $MODEL->getIdInput();
        $VIEW->setDataArray($dataArray);
        $VIEW->setIdInput($nameMethod);
        $HTML = $VIEW->$nameMethod();
        $VIEW->printElement($HTML);
    }


    public function SelectFromListGAR()
    {
        $MODEL = new \forms\inputEditVariable\filterGAR_MODEL();
        $_G_value0 = $MODEL->getFullArray();
        $json = json_encode($_G_value0,1);
        print $json;
    }



    /**
     * message сообщение в заголовку окна с видео
     * callFunction путь до файла с видео с именем файла       GISJKH/Main/Video/start.mp4
     */
    private function playVideo()
    {
        $window = new \views\Elements\Window\Window();
        $elements = new \views\Elements\VElements();
        $text = new \views\Elements\MyText\MyText();
        $button = new \views\Elements\Button\Button();

//        '<video width="1024" height="720" controls="controls" poster="Video/duel.jpg">'.
        $HTML = '';

/*
        $HTML .= $button->set("Закрыть")
            ->height(25)->width(1180)
            ->func("closeBlockAPP()")
            ->floateLeft()
            ->get();
*/
        $HTML  .=
            '<video style="background: black;" width="1180" height="520" controls="controls" autoplay >'.
            '<source src="forms/'.$this->callFunction.'" type=\'video/mp4; codecs="avc1.42E01E, mp4a.40.2"\'>'.
            '</video>';
        $HTML = $window
            ->set()->titleSmall($this->massage)
            ->height(500)->width(1200)
            ->content($HTML)
            ->setBtnCloseWindowFunction('closeBlockAPP()')
            ->get();

        $HTML = $elements->tag("div")->setClass("MsgBlockAPP")
            ->setStyle("top:0px;left:0px;width:1200px;height:650px ")// для прорисовки сообщения по центру экрана
            ->setCaption($HTML)
            ->getHTTPTag();
        include "HTML_yesOrNotButton.php";
    }
}

?>