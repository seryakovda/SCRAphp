<?php

namespace views\Elements;

Class VElements
{
    const FontSmall = 11;
    const FontNormal = 14;
    const FontBig = 20;
    public $TagName = '';
    public $TextId = false;
    public $TextClass = '';
    public $TextStyle = '';
    public $TextFunction = '';
    public $TextCaption = '';

    public function tag($name)
    {
        $this->TagName = $name;
        $this->TextId = false;
        $this->TextClass = '';
        $this->TextStyle = '';
        $this->TextFunction = '';
        $this->TextCaption = '';
        return $this;
    }

    public function setId($id)
    {
        $this->TextId = $id;
        return $this;
    }

    public function setClass($Class)
    {
        $this->TextClass = $this->TextClass . $Class . " ";
        return $this;
    }

    public function setStyle($Style)
    {
        $this->TextStyle = $this->TextStyle . $Style . "; ";
        return $this;
    }

    public function setFunction($function)
    {
        $this->TextFunction = $this->TextFunction . " " . $function;
        return $this;
    }

    public function setCaption($caption)
    {
        $this->TextCaption = $this->TextCaption . $caption;
        return $this;
    }

    public function getHTTPTag()
    {
        $HTTPId = "";
        if ($this->TextId !== false)
            $HTTPId =" id=\"$this->TextId\" ";

        $HTTPStyle = strlen($this->TextStyle) > 1 ? ' style="' . $this->TextStyle . '" ' : "";
        $HTTPClass = strlen($this->TextClass) > 1 ? ' class="' . $this->TextClass . '" ' : "";

        return "<" . $this->TagName . " " . $HTTPId . $HTTPClass . $HTTPStyle . $this->TextFunction . " >" . $this->TextCaption . "</" . $this->TagName . ">";
    }


}