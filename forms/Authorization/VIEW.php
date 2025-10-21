<?php

namespace forms\Authorization;

class VIEW extends \forms\FormView
{
    private $levelAuthorization;
    public function initClass()
    {
        $this->TXT_headBigTitle = "";
        $this->TXT_headSmallTitle = "Для входа, введите свои имя пользователя и пароль";
    }

    /**
     * @param mixed $levelAuthorization
     */
    public function setLevelAuthorization($levelAuthorization)
    {
        $this->levelAuthorization = $levelAuthorization;
    }

    public function AuthorizationForm()
    {

        $elements = new \views\Elements\VElements();
        $Input = new \views\Elements\Input\Input();
        $Button = new \views\Elements\Button\Button();
        $window = new \views\Elements\Window\Window();

        if ($_SESSION["mobile"]  == 'workstation'){
            $widthWindow = 400;
            $width = 300;
        }else{
            $widthWindow = 320;
            $width = 220;
        }

        //$this->levelAuthorization = "0";
        $HTML = '';
        if ($this->levelAuthorization == "0"){
            $HTML .= $Input
                ->set("Имя пользователя")
                ->top(25)->left(40)
                ->height(50)->width($width)
                ->position("absolute")
                ->startFont("Large")
                ->nameId("Login")
                ->get();

            $HTML .= $Input
                ->set("Пароль")
                ->top(85)->left(40)
                ->height(50)->width($width)
                ->position("absolute")
                ->startFont("Large")
                ->password()
                ->nameId("Password")
                ->get();

            $HTML .= $Button
                ->set("Вход")
                ->topLeft(185, 40)
                ->height(50)
                ->width($width)
                ->position("absolute")
                ->func('startAutorization()')
                ->class_("textFontBig")
                ->nameId("regButton")
                ->get();
        }
        if ($this->levelAuthorization == "1"){
            $this->TXT_headSmallTitle = "Введите код подтверждения пришедший на телефон";
            $HTML .= $Input
                ->set("Код")
                ->top(25)->left(40)
                ->height(50)->width($width)
                ->position("absolute")
                ->startFont("Large")
                ->nameId("Code")
                ->get();

            $HTML .= $Button
                ->set("Вход")
                ->topLeft(85, 40)
                ->height(50)
                ->width($width)
                ->position("absolute")
                ->func('startAutorization_level2()')
                ->class_("textFontBig")
                ->nameId("regButton")
                ->get();

            $HTML .= $Button
                ->set("Отмена")
                ->topLeft(155, 40)
                ->height(25)
                ->width($width)
                ->position("absolute")
                ->func('startAutorization_level2_off()')
                ->class_("textFontBig")
                ->nameId("regButton")
                ->get();
        }

        $HTMLAutor = $window
            ->set()
            ->top(0)->left(0)
            ->height(400)->width($widthWindow)
            ->style("margin:auto")
            ->titleBig("Авторизация")
            ->sizeHead($window::sizeHeadWinBig)
            ->titleSmall($this->TXT_headSmallTitle)
            ->content($HTML )
            ->get();

        $HTMLPrint = $elements
            ->tag("div")
            ->setClass("regmain")
            ->setStyle("margin-top: -100px;")
            ->setCaption($HTMLAutor)
            ->getHTTPTag();


        include("HTML.php");
    }

    public function AuthorizationError()
    {
        \views\Views::MsgBlock("Ошибка!", "Неверный пользователь или пароль!");
    }
}