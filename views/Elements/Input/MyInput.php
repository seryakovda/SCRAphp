<?php
/**
 * Created by PhpStorm.
 * User: rezzalbob
 * Date: 06.04.2017
 * Time: 20:08
 */
class MyInput {
    private $caption;
    private $startFont;
    private $NameId;
    private $password;
    private $style;
    private $pattern;

    public function input($caption){
        $this->caption=$caption;
        $this->startFont="Large";
        $this->password=false;
        $this->style ="";
        $this->pattern="";
        return $this;
    }
    public function pattern($val){
        $this->pattern=$val;
        return $this;
    }
    public function style($val){
        $this->style=$this->style.$val.";";
        return $this;
    }
    public function top($val){
        $this->style=$this->style."top:".$val."px;";
        return $this;
    }
    public function left($val){
        $this->style=$this->style."left:".$val."px;";
        return $this;
    }
    public function height($val){
        $this->style=$this->style."height:".$val."px;";
        return $this;
    }
    public function width($val){
        $this->style=$this->style."width:".$val."px;";
        return $this;
    }
    public function position($val){
        $this->style=$this->style."position:".$val.";";
        return $this;
    }
    public function startFont($val){
        $this->startFont=$val;
        return $this;
    }
    public function NameId($val){
        $this->NameId=$val;
        return $this;
    }
    public function password(){
        $this->password=true;
        return $this;
    }
    public function get(){
        $element = new VElements;

        $startFont= $element::FontBig;
        $calssStartFont = "textFontBig";

        if ($this->startFont=="Large"){
            $startFont= $element::FontBig;
            $calssStartFont = "textFontBig";
        }
        if ($this->startFont=="Middle"){
            $startFont= $element::FontNormal;
            $calssStartFont = "textFontNormal";
        }
        $pass=$this->password?'type="password"':'type="text"';
        $func_onclick='onclick="$(\'#Title'.$this->NameId.'\').css(\'font-size\',\''.$element::FontSmall.'px\'); 
                                $(\'#Title'.$this->NameId.'\').css(\'margin-top\',\'0px\'); 
                                $(\'#'.$this->NameId.'\').focus();"';
        $func_onBlur='onBlur=" if (this.value==\'\'){
        $(\'#Title'.$this->NameId.'\').css(\'font-size\',\''.$startFont.'px\'); 
        $(\'#Title'.$this->NameId.'\').css(\'margin-top\',\''.$element::FontSmall.'px\');}"';
        $func_onfocus='onfocus="
        $(\'#Title'.$this->NameId.'\').css(\'font-size\',\''.$element::FontSmall.'px\') 
        $(\'#Title'.$this->NameId.'\').css(\'margin-top\',\'0px\'); "';

        $HTTPP=$element->tag("p")->setId("Title".$this->NameId )->setClass($calssStartFont)->setCaption($this->caption)->getHTTPTag();
        $HTTPInput=$element->tag("input")
            ->setId($this->NameId)
            ->setClass("MyInput")
            //->setFunction('pattern="'.$this->pattern.'"')
            ->setFunction('name="'.$this->NameId.'"')
            ->setFunction($pass)
            //->setStyle("width:".($this->width-20)."px;")
            ->setFunction('value=""')
            ->setFunction($func_onBlur)
            ->setFunction($func_onfocus)
            ->getHTTPTag();

        $HTMLCode=$element->tag("div")->setId("div".$this->NameId)->setClass("MyInputDiv")->setStyle($this->style.";margin:5px")->
        setFunction($func_onclick)->setCaption($HTTPP.$HTTPInput)->getHTTPTag();

        return $HTMLCode;


    }
}
