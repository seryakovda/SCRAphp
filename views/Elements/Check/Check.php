<?php

namespace views\Elements\Check;

class Check
{
    const floatLeftOff = 0;
    const floatLeftOn = 1;
    private $caption;
    private $NameId;
    private $style;
    private $func;
    private $width;
    private $height;
    private $class;
    private $checked;
    private $borderOn;
    private $position;
    private $floatLeft;
    private $type;
    private $name;
    private $value;
    private $readOnly;

    public function set($caption)
    {
        $this->caption = $caption;
        $this->height = 19;
        $this->style = "";
        $this->class = "";
        $this->func = "";
        $this->NameId = "checkbox";
        $this->checked = 'checked="checked"';
        $this->borderOn = "BorderAll";
        $this->floatLeft = $this::floatLeftOff;
        $this->position = "relative";
        $this->type = 'checkbox';
        $this->name = false;
        $this->value = false;

        return $this;
    }


    public function setReadOnly($readOnly = true)
    {
        $this->readOnly = $readOnly;
        return $this;
    }
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setTypeRadio()
    {
        $this->type = 'radio';
        return $this;
    }
    public function floateLeft()
    {
        $this->floatLeft = $this::floatLeftOn;
        return $this;
    }

    public function borderOff()
    {
        $this->borderOn = "BorderOff";
        return $this;
    }

    public function style($val)
    {
        $this->style = $this->style . $val . ";";
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
        $this->height = $val;
        return $this;
    }

    public function width($val)
    {
        $this->width = $val;
        $this->style = $this->style . "width:" . $val . "px;";
        return $this;
    }

    public function position($val)
    {
        $this->position = $val;
        return $this;
    }

    public function class_($val)
    {
        $this->class = $this->class . " " . $val;
        return $this;
    }

    public function func($val)
    {
        $this->func = $val;
        return $this;
    }

    public function checkedOff()
    {
        $this->checked = '';
        return $this;
    }

    public function nameId($val)
    {
        $this->NameId = $val;
        return $this;
    }

    public function get()
    {
        $element = new \views\Elements\VElements();
        $this->style = $this->style . "height:" . ($this->height - 4) . "px;";

        $HTML = "";
        $element->tag("input")
                ->setFunction($this->checked)
                ->setFunction('type="'.$this->type.'"')
                ->setId($this->NameId)
                ->setClass("checkbox");

        if ($this->readOnly !== true)
            $element->setFunction('onclick="' . $this->func . '"');

        if ($this->value !== false){
            $element->setFunction('value="'.$this->value.'"');
        }
        if ($this->name !== false){
            $element->setFunction('name="'.$this->name.'"');
        }

        $HTML = $HTML . $element->getHTTPTag();
        $HTML = $HTML . $element->tag("label")->setFunction('for="' . $this->NameId . '"')->setCaption($this->caption)->getHTTPTag();
        if ($this->floatLeft == $this::floatLeftOn) {
            $this->style = $this->style . "display: inline-block; float:left ; ";
            $this->position = "static";
        }
        $this->style = $this->style . "position:" . $this->position . ";";

        return $element->tag("div")
            ->setStyle("margin:5px; padding-top: 4px;padding-left: 3px;" . $this->style)
            ->setClass($this->class)
            ->setClass($this->borderOn)
            ->setCaption($HTML)
            ->getHTTPTag(); //

    }
}
