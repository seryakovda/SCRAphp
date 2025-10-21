<?php

namespace views\Elements\Input;

class Input
{
    const FONT_Large = 'Large';

    const floatLeftOff = 0;
    const floatLeftOn = 1;
    private $caption;
    private $startFont;
    private $NameId;
    private $password;
    private $style;
    private $setStyle;
    private $pattern;
    private $value;
    private $className;
    private $class_;
    private $floatLeft;
    private $position;
    private $functionName_for_setFocus ;
    private $functionName_for_setFocusOut ;
    private $typeFocus = 'focusin';
    private $onKeyUpFunction = false;
    private $onKeyDownFunction = false;
    //private $functionOnKeyUp;
    private $setFunctionInput = false;

    private $tag = 'input';

    private $cols=50;
    private $rows=1;

    public function set($caption)
    {
        $this->functionName_for_setFocus = false;
        $this->functionName_for_setFocusOut = false;
        $this->caption = $caption;
        $this->startFont = "Large";
        $this->password = false;
        $this->value = "";
        $this->class_ = '';
        $this->setStyle = "";
        $this->style = "height:40px;";
        //$this->functionOnKeyUp=false;
        $this->className = '';
        $this->position = 'relative';
        $this->pattern = false;
        return $this;
    }


    public function setTypeInput_textarea($cols,$rows)
    {
        $this->tag = 'textarea';
        $this->cols = $cols;
        $this->rows = $rows;
        return $this;
    }

    public function setFunctionInput($function)
    {
        $this->setFunctionInput = $function;
        return $this;
    }

    public function setFunctionNameFor_SetFocusOut($functionName_for_setFocusOut)
    {
        $this->functionName_for_setFocusOut[] = $functionName_for_setFocusOut;
        return $this;
    }

    public function setFunctionNameFor_SetFocus($functionName_for_setFocus)
    {
        $this->functionName_for_setFocus[] = $functionName_for_setFocus;
        return $this;
    }

    /**
     * @param string $onKeyUpFunction
     * @return $this
     */
    public function setOnKeyUpFunction(string $onKeyUpFunction)
    {
        $this->onKeyUpFunction = $onKeyUpFunction;
        return $this;
    }

    public function setOnKeyDownFunction(string $onKeyDownFunction)
    {
        $this->onKeyDownFunction = $onKeyDownFunction;
        return $this;
    }
    public function positionRelative()
    {
        $this->position = "relative";
        return $this;
    }

    public function positionAbsolute()
    {
        $this->position = "absolute";
        return $this;
    }

    public function position($val)
    {
        $this->position = $val;
        return $this;
    }

    public function value($val)
    {
        $this->value = $val;
        return $this;
    }

    public function className($className)
    {
        $this->className = $this->className . ' ' . $className;
        return $this;
    }

    public function pattern($val)
    {
        $this->pattern = $val;
        return $this;
    }

    public function style($val)
    {
        $this->setStyle = $val;
        return $this;
    }

    public function top($val)
    {
        $this->style = $this->style . "top:" . $val . "px;";
        return $this;
    }

    public function left($val)
    {
        $this->style = $this->style . "left:" . $val . "px;";
        return $this;
    }

    public function height($val)
    {
        $this->style = $this->style . "height:" . $val . "px;";
        return $this;
    }

    public function width($val)
    {
        $this->style = $this->style . "width:" . $val . "px;";
        return $this;
    }

    public function startFont($val)
    {
        $this->startFont = $val;
        return $this;
    }

    public function NameId($val)
    {
        $this->NameId = $val;
        return $this;
    }

    public function password()
    {
        $this->password = true;
        return $this;
    }

    public function floatLeft()
    {
        $this->floatLeft = $this::floatLeftOn;
        return $this;
    }

    public function class_($class_)
    {
        $this->class_ .= $class_ . ' ';
        return $this;
    }
    /*
    public function functionOnKeyUp($functionName)
    {
        $this->functionOnKeyUp=$functionName;
        return $this;

    }*/

    public function get()
    {
        $element = new \views\Elements\VElements;

        $this->style = $this->style . "position:" . $this->position . ";";

        $startFont = $element::FontBig;
        $calssStartFont = "textFontBig";

        if ($this->startFont == "Large") {
            $startFont = $element::FontBig;
            $calssStartFont = "textFontBig";
        }
        if ($this->startFont == "Middle") {
            $startFont = $element::FontNormal;
            $calssStartFont = "textFontNormal";
        }
        if ($this->floatLeft == $this::floatLeftOn) {
            $this->style = $this->style . "display: inline-block; float:left ; ";
        }

        $HTTPP = '';
        if ($this->tag != 'textarea'){
            $pass = $this->password ? 'type="password"' : 'type="text"';

            $HTTPP = $element->tag("p")
                ->setId("Title" . $this->NameId)
                ->setClass($calssStartFont)
                ->setCaption($this->caption)
                ->getHTTPTag();
        }




        //////////////////////////////////////////////////////////
        // начало блока инпут //////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////

        $element->tag($this->tag)
            ->setId($this->NameId)
            ->setFunction('autocomplete="off"')
            ->setClass("MyInput");

        if ($this->tag == 'textarea'){

            $element->setFunction('cols="'. $this->cols .'" rows="'. $this->rows .'"')
                ->setCaption($this->caption)
                ->setStyle("font-size:18px")
            ;
        }else{
            if (mb_strlen($this->value) <= 50) $element->setClass("textFontBig");
            elseif ((mb_strlen($this->value) > 50) and (mb_strlen($this->value) <= 80)) $element->setClass("textFontNormal");
            elseif (mb_strlen($this->value) > 80) $element->setClass("textFontSmall");
            $element->setFunction("value = '$this->value'");
        }



        if ($this->setFunctionInput !== false)
            $element->setFunction($this->setFunctionInput);

//        if ($this->functionOnKeyUp) $element->setFunction("onkeyup=\"$this->functionOnKeyUp\"");

        $element->setFunction('name="' . $this->NameId . '"')
            ->setClass($this->className)
            ->setFunction($pass)
        ;

        if ($this->onKeyUpFunction !== false)
            $element->setFunction("onkeyup='$this->onKeyUpFunction'");
        if ($this->onKeyDownFunction !== false)
            $element->setFunction("onkeyDown='$this->onKeyDownFunction'");



        $HTTPInput = $element->getHTTPTag();

        $HTMLCode = $element
            ->tag("div")
            ->setId("div" . $this->NameId)
            ->setClass("MyInputDiv")
            ->setClass($this->class_)
            ->setStyle($this->style . ";margin:5px")
            ->setStyle($this->setStyle)
            //->setFunction($func_onclick)
            ->setCaption($HTTPP . $HTTPInput)
            ->getHTTPTag();
        if ($this->pattern !== false) {
            $HTMLCode = $HTMLCode . "<runScript>";
            $HTMLCode = $HTMLCode . "$('#$this->NameId').mask('$this->pattern');";
            //$HTMLCode = $HTMLCode . "console.log('#$this->NameId','$this->pattern')";
            $HTMLCode = $HTMLCode . "</runScript>";
        }

        if (($this->functionName_for_setFocus !== false) || ($this->functionName_for_setFocusOut !== false)){
            $HTMLCode = $HTMLCode . "<runScript>";

            if ($this->functionName_for_setFocus !== false) {
                foreach ($this->functionName_for_setFocus as $k => $function){
                    $HTMLCode = $HTMLCode . "$('#$this->NameId').focusin(function() { $function; } ) ; ";
                }
            }

            if ($this->functionName_for_setFocusOut !== false) {
                foreach ($this->functionName_for_setFocusOut as $k => $function){
                    $HTMLCode = $HTMLCode . "$('#$this->NameId').focusout(function() { $function; } ) ;";
                }
            }

            $HTMLCode = $HTMLCode . "</runScript>";
        }
        ob_start();
        require "HTML_enterToTab.php";
        $output = ob_get_contents();
        ob_end_clean();
        $HTMLCode = $HTMLCode .$output;
        return $HTMLCode;


    }
}
