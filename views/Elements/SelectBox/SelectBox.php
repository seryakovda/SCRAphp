<?php

namespace views\Elements\SelectBox;

class SelectBox
{
    private $caption;
    private $NameId;
    private $style;
    private $func;
    private $width;
    private $class;
    private $topLeftInit;
    private $position;
    private $floatLeft;

    public function set()
    {
        $this->style = "";
        $this->class = "";
        $this->func = "";
        $this->topLeftInit = 0;
        $this->floatLeft = 0;
        $this->position = "initial";
        return $this;
    }

    public function caption($caption)
    {
        $this->caption = $caption;
        return $this;
    }

    public function floateLeft()
    {
        $this->floatLeft = 1;
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

    public function my_array_key_exists($key, $array)
    {
        if (array_key_exists($key, $array)) {
            return $array[$key];
        } else {
            return "";
        }
    }

    /**
     * @param $data \DB\Connect
     * @return string
     */
    public function get($data)
    {
        $element = new \views\Elements\VElements();
        if (($this->topLeftInit == 0) and ($this->floatLeft == 0)) {
            $this->position = "absolute";
        }
        if ($this->floatLeft == 1) {
            $this->style = $this->style . "float:left;";

        }
        $this->style = $this->style . "position:" . $this->position . ";";

        $sel1 = "";
        $chekked = "";
        while ($res = $data->fetch()) {
            $id = $res['id'];
            $name = rtrim($res['name']);
            $sel1 = $sel1 . '<option ' . $chekked . ' value="' . $id . '">' . $name;

        }
        $sel = $element->tag("select")->setId($this->NameId)->setCaption($sel1)->getHTTPTag();

        return $element->tag("div")->setStyle("margin:5px; " . $this->style)->setClass("SelectBoxDiv " . $this->class)->setCaption($sel)->getHTTPTag();

    }
}
