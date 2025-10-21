<?php
namespace views\Elements\SelectListFilter;

class SelectListFilter
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
     * @param $data
     * @return string
     */
    public function get($data)
    {
        $HTML = "";
        $element = new \views\Elements\VElements();
        $HTML = $HTML . $element->tag("input")
                ->setId('myInput')
                //->setId('myDropdown')
                ->setFunction('onkeyup=filterFunction()')
                ->getHTTPTag();

        while ($res = $data->fetch()) {
            $id = $res['id'];
            $name = rtrim($res['name']);
            $element = new \views\Elements\VElements();
            $HTML = $HTML . $element->tag("a")
                ->setFunction("onclick = \"SelectListFilter('$id','$name','$this->func')\"")
                ->setCaption($name)
                ->getHTTPTag();

        }
        $element = new \views\Elements\VElements();
        $HTML1 = $element->tag("button")
            ->setId("btnDropdown")

            ->setClass("dropbtn")
            ->setFunction('onclick="myFunction()"')
            ->setCaption($this->caption)
            ->getHTTPTag();
        $element = new \views\Elements\VElements();
        $HTML1 = $HTML1 .$element->tag("div")
                ->setId("myDropdown")
                ->setStyle("position: fixed;     height: 500px;    overflow-y: auto;")
                ->setClass("dropdown-content")
                ->setFunction('onkeyup=filterFunction()')
                ->setCaption($HTML)
                ->getHTTPTag();
        $element = new \views\Elements\VElements();
        $element->tag("div")
            ->setClass("dropdown1")
            ->setCaption($HTML1);
        if ($this->floatLeft == 1)
            $element->setStyle('float: left');

        $HTML = $element->getHTTPTag();
        ob_start();
        require "HTML.php";
        $output = ob_get_contents();
        ob_end_clean();
        return $output;

    }
}