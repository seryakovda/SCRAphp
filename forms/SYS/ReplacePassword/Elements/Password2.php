<?php

namespace forms\SYS\ReplacePassword\Elements;

class Password2 extends \forms\FormDirElements
{
    public function initClass()
    {
        $this->caption = "Повторить новый пароль";
        $this->function = "";
        $this->jobInCloseMonthForEdit = true;
        $this->jobInCloseMonthForPayment = true;
        $this->sort = 2;
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
            ->nameId("Password2")
            ->get();

        return $HTML;

    }
}