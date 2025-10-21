<?php

namespace views\Elements\MyText;

class MyText
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
    const textFontBigSuper = "textFontBigSuper";
    const fontNormal = "textFontNormal";
    const fontSmall = "textFontSmall";
    const floatLeftOff = 0;
    const floatLeftOn = 1;
    private $id;
    private $verticalPos;
    private $horizontalPos;
    private $position;
    private $border;
    private $fontSize;
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

    public function text($caption)
    {
        $this->verticalPos = $this::verticalPosTop;
        $this->horizontalPos = $this::horizontalPosLeft;
        $this->border = $this::borderAll;
        $this->fontSize = $this::fontNormal;
        $this->caption = $caption;
        $this->style = "";
        $this->class = "";
        $this->func = "";
        $this->topLeftInit = 0;
        $this->floatLeft = $this::floatLeftOff;
        $this->position = $this::positionRelative;
        return $this;
    }

    public function borderOn()
    {
        $this->border = $this::borderAll;
        return $this;
    }

    public function borderOff()
    {
        $this->border = $this::borderOff;
        return $this;
    }

    public function fontSizeSmall()
    {
        $this->fontSize = $this::fontSmall;
        return $this;
    }

    public function fontSizeNormal()
    {
        $this->fontSize = $this::fontNormal;
        return $this;
    }

    public function fontSizeBig()
    {
        $this->fontSize = $this::fontBig;
        return $this;
    }

    public function fontBigSuper()
    {
        $this->fontSize = $this::textFontBigSuper;
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

    public function horizontalPosLeft()
    {
        $this->horizontalPos = $this::horizontalPosLeft;
        return $this;
    }

    public function horizontalPosCenter()
    {
        $this->horizontalPos = $this::horizontalPosCenter;
        return $this;
    }

    public function horizontalPosRight()
    {
        $this->horizontalPos = $this::horizontalPosRight;
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

    public function get()
    {
        $element = new \views\Elements\VElements;
        if (($this->topLeftInit == 0) and ($this->floatLeft == 0)) {
            $this->position = $this::positionRelative;
        }
        if ($this->floatLeft == $this::floatLeftOn) {
            $this->style = $this->style . "display: inline-block; float:left; ";

        }
        $styleTopLeft = "";
        if ($this->topLeftInit == 1) {
            $styleTopLeft = 'top:' . $this->top . 'px;' . 'left:' . $this->left . 'px;';
            $this->position = $this::positionAbsolute;
        }

        $this->style = $this->style . "position:" . $this->position . ";";

        /*
                $td=$element->tag("td")->setStyle("width:".$this->width."px")->setCaption($this->caption)->getHTTPTag();
                $table='<table style="height:100%"><tr>'.$td.'</tr></table>';
                $element->tag("div")->setStyle("margin-top:5px;margin-left:5px;".$this->style)->setId($this->NameId)->setFunction('onclick="'.$this->func.'"')->setClass("MyText".$this->class)->setCaption($table)->getHTTPTag();
        */

        $div1 = $element->tag("div")->setId($this->id)->setCaption($this->caption)
            ->setStyle('display: table-cell;vertical-align: ' . $this->verticalPos . ';')->getHTTPTag();
        $div1 = $element->tag("div")->setClass($this->class . " " . $this->fontSize)
            ->setStyle('padding-right: 5px;')
            ->setStyle('padding-left: 5px;')
            ->setStyle('display: table;')
            ->setStyle($styleTopLeft)
            ->setStyle('height:' . $this->height . 'px;')
            ->setStyle('width:' . ($this->width - 10) . 'px;')
            ->setStyle('text-align:' . $this->horizontalPos . ';')
            ->setCaption($div1)->getHTTPTag();
        return $element->tag("div")
            ->setId("text")
            ->setClass($this->border)
            ->setStyle('overflow:hidden;' . $styleTopLeft . ';height:' . $this->height . 'px;width:' . $this->width . 'px;text-align:' . $this->horizontalPos . ';')
            ->setStyle('margin-top: 5px;margin-left: 5px;')
            ->setStyle($this->style)
            ->setCaption($div1)
            ->getHTTPTag();

    }
}