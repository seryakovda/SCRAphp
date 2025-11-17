<?php

namespace forms\SkeletonApp;

use models\_G_session;
use views\Elements\button\Button;
use \views\Elements\VerticalMenu\VerticalMenu;
use DB\Table\ConnectionSettings;

class VIEW extends \forms\FormView
{
    /**
     * @var MODEL
     */
    public $MODEL;

    public function initClass()
    {
        $this->TXT_headSmallTitle = "";
    }

    /**
     * @param MODEL $MODEL
     */
    public function setMODEL(MODEL $MODEL): void
    {
        $this->MODEL = $MODEL;
    }

// Работа только admin-a
    public function SkeletonApplication()
    {
        $elements = new \views\Elements\VElements();
        $text = new \views\Elements\MyText\MyText();
        $user = \models\User::get();
        $BTN = new Button();
        $HTML = '';
        $dataArray = $this->MODEL->getData();
        $var = ConnectionSettings::addressPS;
        $val = $dataArray[$var];
        $caption = 'Адрес pass-system:';
        $HTML = $HTML . $BTN->set($caption.$val)->width(600)->height(40)->func("replaceValue('$caption','$var','$val', false)")->horizontalPosLeft()->fontBig()->floateLeft()->get();

        $var = ConnectionSettings::loginPS;
        $val = $dataArray[$var];
        $caption = 'Логин pass-system:';
        $HTML = $HTML . $BTN->set($caption.$val)->width(600)->height(40)->func("replaceValue('$caption','$var','$val', false)")->horizontalPosLeft()->fontBig()->floateLeft()->get();

        $var = ConnectionSettings::passPS;
        $val = $dataArray[$var];
        $caption = 'Пароль pass-system: ******';
        $HTML = $HTML . $BTN->set($caption)->width(600)->height(40)->func("replaceValue('$caption','$var','$val', true)")->horizontalPosLeft()->fontBig()->floateLeft()->get();

        $HTML = $HTML . $BTN->set("Проверить соединение c pass-system")->width(600)->height(40)->func('testConnectPS()')->fontBig()->floateLeft()->get();

        $var = ConnectionSettings::address_DbOrion;
        $val = $dataArray[$var];
        $caption = 'Адрес ORION:';
        $HTML = $HTML . $BTN->set($caption.$val)->width(600)->height(40)->func("replaceValue('$caption','$var','$val', false)")->horizontalPosLeft()->fontBig()->floateLeft()->get();

        $var = ConnectionSettings::db_DbOrion;
        $val = $dataArray[$var];
        $caption = 'База данных ORION:';
        $HTML = $HTML . $BTN->set($caption.$val)->width(600)->height(40)->func("replaceValue('$caption','$var','$val', false)")->horizontalPosLeft()->fontBig()->floateLeft()->get();


        $var = ConnectionSettings::login_DbOrion;
        $val = $dataArray[$var];
        $caption = 'Логин ORION:';
        $HTML = $HTML . $BTN->set($caption.$val)->width(600)->height(40)->func("replaceValue('$caption','$var','$val', false)")->horizontalPosLeft()->fontBig()->floateLeft()->get();

        $var = ConnectionSettings::pass_DbOrion;
        $val = $dataArray[$var];
        $caption = 'Пароль ORION: ******';
        $HTML = $HTML . $BTN->set($caption)->width(600)->height(40)->func("replaceValue('$caption','$var','$val', true)")->horizontalPosLeft()->fontBig()->floateLeft()->get();

        $HTML = $HTML . $BTN->set("Проверить соединение cо СКУД Орион.")->width(600)->height(40)->func('testConnectOrion()')->fontBig()->floateLeft()->get();

        $var = ConnectionSettings::orion_door;
        $val = $dataArray[$var];
        $caption = 'Номер прибора ORION:';
        $HTML = $HTML . $BTN->set($caption.$val)->width(600)->height(40)->func("replaceValue('$caption','$var','$val', false)")->horizontalPosLeft()->fontBig()->floateLeft()->get();


        $HTML = $HTML . $BTN->set("Получить полные данные СКУД Орион.")->width(600)->height(40)->func('')->fontBig()->floateLeft()->get();

        $HTML = $HTML . $BTN->set("Сменить пароль")->width(600)->height(40)->func('exitAPP()')->fontBig()->floateLeft()->get();

        $HTML = $HTML . $BTN->set("Выход")->width(600)->height(40)->func('exitAPP()')->fontBig()->floateLeft()->get();


        $mainContent = $elements
            ->tag("div")
            ->setId("mainContent")
            ->setClass("mainContent")
            ->setCaption($HTML)
            ->getHTTPTag();


        $fixHeadBlock = $elements->getHTTPTag();

        if (array_key_exists('heightBrowse',$_SESSION))
            $heightBrowse = _G_session::heightMobile();
        else
            $heightBrowse = 0;

        $mainContent = $elements->tag("div")->setClass("MsgBlockAPP")
            ->setStyle("top:0px;left:0px")// для прорисовки сообщения по центру экрана
            //->setStyle("top:0px;left:0px;width:1280px")// для прорисовки сообщения по центру экрана
            ->setCaption($mainContent )->getHTTPTag();

        $HTML = $elements->tag("div")
                ->setId("frameApp")
                ->setCaption($mainContent)
                ->getHTTPTag() . $fixHeadBlock;


        include("HTML.php");
    }


    public function logOut()
    {
        require "HTML_logOut.php";
    }
}