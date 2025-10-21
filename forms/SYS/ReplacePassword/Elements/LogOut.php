<?php

namespace forms\SYS\ReplacePassword\Elements;

class LogOut extends \forms\FormDirElements
{

    public function initClass()
    {
        $this->caption = "Вернуться в начало";
        $this->function = "exitAPP()";
        $this->jobInCloseMonthForEdit = true;
        $this->jobInCloseMonthForPayment = true;
        $this->sort = 99;

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