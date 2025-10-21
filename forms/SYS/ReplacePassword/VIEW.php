<?php

namespace forms\SYS\ReplacePassword;

class VIEW extends \forms\FormView
{

    public $windowContent;

    public function initClass()
    {
        $this->TXT_headSmallTitle = "Смена пароля пользователя";

        $this->BTN = new \views\Elements\Button\Button();
        $this->WND = new \views\Elements\Window\Window();
    }

    public function printMainWindow()
    {
        print "<code>";
        print $this->windowContent;
        print "</code>";
        print $this->includeHtmlFilter();
        include "HTML.php";
    }

}