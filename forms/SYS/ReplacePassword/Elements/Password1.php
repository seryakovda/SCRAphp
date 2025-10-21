<?php

namespace forms\SYS\ReplacePassword\Elements;

class Password1 extends \forms\FormDirElements
{
    public function initClass()
    {
        $this->caption = "Новый пароль";
        $this->function = "";
        $this->jobInCloseMonthForEdit = true;
        $this->jobInCloseMonthForPayment = true;
        $this->sort = 1;
    }

    public function get()
    {
        $HTML = "";
        if ($this->right()) {
            $HTML = $this->HTML();
        }
        return $HTML;
    }

    private function HTML()
    {
        $Input = new \views\Elements\Input\Input();

        $HTML = $Input
            ->set($this->caption)
            ->height(50)->width(320)
            ->floatLeft()
            ->startFont("Large")
            ->password()
            ->nameId("Password1")
            ->get();

        return $HTML;

    }
}