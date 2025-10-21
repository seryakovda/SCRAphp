<?php

/**
 * Created by PhpStorm.
 * User: rezzalbob
 * Date: 07.04.2017
 * Time: 21:31
 */
class MyButton
{
    const horizontalPosLeft = "left";
    const horizontalPosCenter = "center";
    const horizontalPosRight = "right";
    const textFontBig = "textFontBig";
    const textFontNormal = "textFontNormal";
    const textFontSmall = "textFontSmall";
    const floatLeftOff = 0;
    const floatLeftOn = 1;
    const marginOn = 5;
    const marginOff = 0;
    private $horizontalPos;
    private $textFont;
    private $caption;
    private $NameId;
    private $style;
    private $func;
    private $height;
    private $width;
    private $class;
    private $topLeftInit;
    private $position;
    private $floatLeft;
    private $margitTop;
    private $marginLeft;
    private $marginBottom;
    private $title;

    public function button($caption)
    {
        $this->caption = $caption;
        $this->style = "";
        $this->class = "";
        $this->func = "";
        $this->topLeftInit = 0;
        $this->floatLeft = $this::floatLeftOff;
        $this->position = "relative";
        $this->margitTop = $this::marginOn;
        $this->marginLeft = $this::marginOn;
        $this->marginBottom = $this::marginOn;
        $this->horizontalPos = $this::horizontalPosCenter;
        $this->textFont = $this::textFontNormal;
        $this->title = "";
        return $this;
    }

    public function title($val)
    {
        $this->title = $val;
        return $this;
    }

    public function horizontalPos($val)
    {
        $this->horizontalPos = $val;
        return $this;
    }

    public function marginTopOff()
    {
        $this->margitTop = $this::marginOff;
        return $this;
    }

    public function marginLeftOff()
    {
        $this->marginLeft = $this::marginOff;
        return $this;
    }

    public function marginBottomOff()
    {
        $this->marginBottom = $this::marginOff;
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

    public function topLeft($top, $left)
    {
        $this->style = $this->style . "top:" . $top . "px;";
        $this->style = $this->style . "left:" . $left . "px;";
        $this->topLeftInit = 1;
        return $this;
    }

    public function height($val)
    {
        $this->height = $val;
        $this->style = $this->style . "height:" . $val . "px;";
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

    public function nameId($val)
    {
        $this->NameId = $val;
        return $this;
    }

    public function fontSmall()
    {
        $this->textFont = $this::textFontSmall;
        return $this;
    }

    public function fontBig()
    {
        $this->textFont = $this::textFontBig;
        return $this;
    }


    public function get()
    {
        $element = new VElements;
        if (($this->topLeftInit == 0) and ($this->floatLeft == 0)) {
            $this->position = "fixed";
        }
        if ($this->floatLeft == $this::floatLeftOn) {
            $this->style = $this->style . "display: inline-block; float:left ; ";
            $this->position = "static";
        }
        $this->style = $this->style . "position:" . $this->position . ";";
        $bottonLogin = $element->tag("button")->setStyle("background: none;border: 0px solid #000;    display: block;")
            ->setFunction("title='{$this->title}'")
            ->setClass($this->textFont)
            ->setStyle("height:" . ($this->height - 6) . "px")
            ->setStyle("width:" . ($this->width - 6) . "px")->setCaption($this->caption)->getHTTPTag();

        $td = $element->tag("td")->setStyle("width:" . $this->width . "px")
            ->setStyle("text-align:" . $this->horizontalPos)
            ->setCaption($bottonLogin)->getHTTPTag();
        $table = '<table style="height:100%"><tr>' . $td . '</tr></table>';
        return $element->tag("div")
            ->setStyle("margin-top:{$this->margitTop}px;")
            ->setStyle("margin-left:{$this->marginLeft}px;")
            ->setStyle("margin-bottom:{$this->marginBottom}px;")
            ->setStyle($this->style)
            ->setId($this->NameId)
            ->setFunction('onclick="' . $this->func . '"')
            ->setClass("MyButton" . $this->class)
            ->setCaption($table)
            ->getHTTPTag();
    }
}
