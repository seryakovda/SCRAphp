<?php

namespace forms\SYS\ReplacePassword\Elements;

class Edit extends \forms\FormDirElements
{

    public function initClass()
    {
        $this->caption = "Сменить пароль";
        $this->function = " ReplacePassword()";
        $this->jobInCloseMonthForEdit = true;
        $this->jobInCloseMonthForPayment = true;
        $this->sort = 4;

    }

    public function get()
    {
        $HTML = "";
        if ($this->right()) {
            $HTML = $this->HTML();
        }
        return $HTML;
    }

    public function right()
    {
        return true;
    }

    private function HTML()
    {
        $BTN = new \views\Elements\Button\Button();

        return $BTN->set($this->caption)
            ->height(40)->width(320)
            ->floateLeft()
            ->func($this->function)
            ->nameId(\models\ControlElements::get()->getNameMethod($this->objectFullName, __METHOD__))
            ->get();

    }
}