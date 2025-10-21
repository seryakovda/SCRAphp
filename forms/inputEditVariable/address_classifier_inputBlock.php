<?php


namespace forms\inputEditVariable;


class address_classifier_inputBlock
{
    private $name,$heiht,$left,$width,$top,$value,$caption;
    private $zIndex;



    public function setZIndex($zIndex)
    {
        $this->zIndex = $zIndex;
        return $this;
    }

    public function setCaption($caption)
    {
        $this->caption = $caption;
        return $this;
    }


    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function setTop($top)
    {
        $this->top = $top;
        return $this;
    }

    public function setHeiht($heiht)
    {
        $this->heiht = $heiht;
        return $this;
    }

    public function setLeft($left)
    {
        $this->left = $left;
        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    public function getHtmlInput()
    {
        $window = new \views\Elements\Window\Window();
        $window2 = new \views\Elements\Window\Window();
        $elements = new \views\Elements\VElements();
        $text = new \views\Elements\MyText\MyText();
        $button = new \views\Elements\Button\Button();
        $input = new \views\Elements\Input\Input();

        $HTML = '';
        $HTML .= $input->NameId("inputAS_".$this->name)->set($this->caption)->width($this->width)
            //->style('autocomplete="NO"')
            ->floatLeft()
            ->get();
        $HTML .= $window->nameId("greed_classifier_".$this->name)
            ->width($this->width)
            ->class_("backgroundNormal")
            ->floatLeft()
            ->headSizeNone()
            ->shadowSmall()
            ->get();
        $HTML = $window2->nameId("inputBlock_classifier_".$this->name)
            ->top($this->top)->left($this->left)
            ->position("absolute")
            ->width($this->width)
            ->headSizeNone()
            ->content($HTML)
            //->style("z-index:$this->zIndex")
            ->get();

        return $HTML;
    }

}