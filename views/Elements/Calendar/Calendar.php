<?php

namespace views\Elements\Calendar;

class Calendar {
    private $idCalendar,$func,$caption,$year;
    private $height = 25;
    private $replaceCaption = false;
    private $infinity = false;
    private $closeFunction = false;

    function __construct()
    {
        $this->year=1900;
    }

    public function setReplaceCaption()
    {
        $this->replaceCaption = true;
        return $this;
    }

    public function upendButton_Infinity()
    {
        $this->infinity = true;
        return $this;
    }


    public function setCloseFunction($closeFunction)
    {
        $this->closeFunction = $closeFunction;
        return $this;
    }


    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    public function setYear($year)
    {
        $this->year = $year;
        return $this;
    }
    public function setId($val)
    {
        $this->idCalendar=$val;
        return $this;
    }

    public function setFunction($val)
    {
        $this->func=$val;
        return $this;
    }
    public function setCaption($val)
    {
        $this->caption=$val;
        return $this;
    }

    public function calendar()
    {
        $idCalendar=$this->idCalendar;
        $func=$this->func;
        $caption=$this->caption;

        $height = 200;
        if ( $this->infinity===true)
            $height = 230;

        $element=new \views\Elements\VElements();
        $Button= new \views\Elements\Button\Button();


        $HTMLButton = $this->body();

        $bodyCalendar = $element->tag("div")
            ->setId("BodyCalendar")
            ->setStyle("z-index: 999")
            ->setClass("backgroundCalendar")
           // ->setStyle("position: fixed")
            ->setStyle("height:{$height}px")
            ->setCaption($HTMLButton)
            ->getHTTPTag();

        $CalendarButton = $Button->set($caption)
            ->height($this->height)->width(250)
            ->floateLeft()
//            ->position("absolute")
            ->Style("margin:5px")
            ->Style("height:{$this->height}px")
            ->Style("width:250px")
            ->Style("float:left")
            ->fontSmall()
            ->marginBottomOff()->marginLeftOff()->marginTopOff()
            ->func("clickYear('".$idCalendar."')")
            ->nameId("ButtonCalendar")
            ->get();

        $HTML =  $element->tag("div")
            ->setStyle('float: left;    position: relative;')
            ->setId($idCalendar)
            ->setCaption($CalendarButton.$bodyCalendar)
            ->getHTTPTag();

        ob_start();
        require "HTTPcalendar.php";
        $output=ob_get_contents();
        ob_end_clean();
        return $output;
    }

    public function BodyOnly()
    {
        $HTML = $this->body();
        $element=new \views\Elements\VElements();

        $HTML =  $element->tag("div")
            ->setStyle('float: left;    position: relative;')
            ->setId($idCalendar=$this->idCalendar)
            ->setCaption($HTML)
            ->getHTTPTag();
        ob_start();
        require "HTTPcalendar.php";
        $output=ob_get_contents();
        ob_end_clean();
        return $output;

    }

    private function body()
    {
        $idCalendar=$this->idCalendar;
        $func=$this->func;
        $caption=$this->caption;

        $element=new \views\Elements\VElements();
        $Button= new \views\Elements\Button\Button();


        $inpYear2=$element->tag("input")
            ->setId("secondaryDateYear_$idCalendar")
            ->setClass("inputYear  textFontNormal")
            ->setFunction("value='$this->year'")
            ->setFunction("disabled")
            ->getHTTPTag();

        $inpYear2div=$element->tag("div")
            ->setClass("textColorBlack")
            ->setStyle("position:absolute; top:10px;left:110px;height:20px;width:70px")
            ->setCaption($inpYear2)
            ->getHTTPTag();


        $mes=["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"];
        $HTMLButton="";
        $k=0;
        $HTMLButtonLeft=$Button->set("Назад")
            ->nameId("ButtonMinus")
            ->topLeft(10,10)
            ->height(20)->width(70)
            ->position("absolute")
            ->fontSmall()
            ->func("minusYear('".$idCalendar."')")
            ->get();
        $HTMLButtonRight=$Button->set("Вперёд")
            ->nameId("ButtonPlus")
            ->topLeft(10,174)
            ->height(20)->width(70)
            ->position("absolute")
            ->fontSmall()
            ->func("plusYear('".$idCalendar."')")
            ->get();

        $HTMLButton=$HTMLButton.$HTMLButtonLeft.$inpYear2div.$HTMLButtonRight;
        for ($i=0;$i<4;$i++){
            for ($j=0;$j<3;$j++)
            {
                $HTMLButton1=$Button->set($mes[$k])->
                topLeft(40+$i*30,10+$j*82)->
                height(20)->
                width(70)->
                position("absolute")->
                fontSmall()->
                func("clickMonth('".$idCalendar."',".$k.",'".$mes[$k]."','".$func."','".$caption."','$this->replaceCaption')")->nameId("Calendar")->get();
                $HTMLButton=$HTMLButton.$HTMLButton1;
                $k++;
            }
        }

        if ( $this->infinity===true){
            $HTMLButton = $HTMLButton . $Button->set('Бесконечно')->
                topLeft(40+($i)*30,10+0*82)->
                height(20)->
                width(234)->
                position("absolute")->
                fontSmall()->
                func("closeCalendar_infinity('$idCalendar','$func')")->
                nameId("Calendar")
                    ->get();
            $i = $i +1;
        }
        if ($this->closeFunction === false)
            $this->closeFunction = "closeCalendar('$idCalendar')";
        $HTMLButton = $HTMLButton . $Button->set('Закрыть')->
            topLeft(40+$i*30,10+0*82)->
            height(20)->
            width(234)->
            position("absolute")->
            fontSmall()->
            func($this->closeFunction)->
            nameId("Calendar")
                ->get();

        return $HTMLButton;
    }
}