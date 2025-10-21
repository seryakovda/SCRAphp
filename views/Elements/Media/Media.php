<?php

namespace views\Elements\Media;

class Media
{
    const verticalPosTop = "top";
    const verticalPosMiddle = "middle";
    const verticalPosBottom = "bottom";
    const horizontalPosLeft = "left";
    const horizontalPosCenter = "center";
    const horizontalPosRight = "right";
    const positionAbsolute = "absolute";
    const positionRelative = "relative";
    const positionInitial = "initial";
    const borderAll = "BorderAll";
    const borderOff = "BorderOff";
    const fontBig = "textFontBig";
    const fontNormal = "textFontNormal";
    const fontSmall = "font-size";
    const floatLeftOff = 0;
    const floatLeftOn = 1;
    private $id = false;
    private $verticalPos;
    private $horizontalPos;
    private $position;
    private $border;
    private $sizeFont;
    private $floatLeft;
    private $caption;
    private $style;
    private $func;
    private $width;
    private $height;
    private $class;
    private $topLeftInit;
    private $top;
    private $left;

    public function __construct()
    {
        $this->verticalPos = $this::verticalPosTop;
        $this->horizontalPos = $this::horizontalPosLeft;
        $this->border = $this::borderAll;
        $this->sizeFont = $this::fontNormal;
        $this->style = "";
        $this->class = "";
        $this->func = "";
        $this->topLeftInit = 0;
        $this->floatLeft = $this::floatLeftOff;
        $this->position = $this::positionRelative;
        $this->width = 50;
        $this->height = 50;
        return $this;
    }

    public function caption($val)
    {
        $this->caption = $val;
        return $this;
    }

    public function border($val)
    {
        $this->border = $val;
        return $this;
    }

    public function sizeFont($val)
    {
        $this->sizeFont = $val;
        return $this;
    }

    public function verticalPos($val)
    {
        $this->verticalPos = $val;
        return $this;
    }

    public function horizontalPos($val)
    {
        $this->horizontalPos = $val;
        return $this;
    }

    public function topLeft($top, $left)
    {
        $this->top = $top;
        $this->left = $left;
        $this->topLeftInit = 1;
        return $this;
    }

    public function floateLeft()
    {
        $this->floatLeft = $this::floatLeftOn;
        return $this;
    }

    public function style($val)
    {
        $this->style = $this->style . $val . ";";
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

    public function nameId($val)
    {
        $this->id = $val;
        return $this;
    }

    public function image($imageFile)
    {
        $element = new \views\Elements\VElements;
        /*        $image=$element->tag("img")
                    ->setFunction('src="'.$imageFile.'"')->getHTTPTag();*/
        $element->tag("div")
            ->setFunction('onclick="'. $this->func .'"')
            ->setStyle("background: url(" . $imageFile . ") no-repeat center")
            ->setStyle("background-size:contain")
            ->setStyle($this->style)
            ->setClass($this->class);
        if ($this->id !== false)
            $element->setId($this->id);

        if ($this->height !== false)
            $element->setStyle("height:{$this->height}px");

        if ($this->width !== false)
            $element->setStyle("width:{$this->width}px");

        if ($this->floatLeft == $this::floatLeftOn)
            $element->setStyle("float:left");



        return $element->getHTTPTag();
    }
}