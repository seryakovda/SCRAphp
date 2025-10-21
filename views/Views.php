<?php
/**
 * Created by PhpStorm.
 * User: rezzalbob
 * Date: 24.01.2017
 * Time: 21:48
 */

namespace views;

class Views
{
    static function MsgBlock($mainMSG, $titleMSG)
    {
        $window = new \views\Elements\Window\Window();
        $text = new \views\Elements\MyText\MyText();
        $button = new \views\Elements\Button\Button();
        $elements = new \views\Elements\VElements();
        $mainMessage = $text->text($mainMSG)->class_("textColorWhite")->topLeft(10, 50)->position("absolute")->borderOff()->fontSizeBig()->get();
        $titleMessage = $text->text($titleMSG)->class_("textColorWhite")->topLeft(40, 50)->position("absolute")->borderOff()->get();
        $closeBottom = $button->set("OK")->topLeft(150, 100)->height(50)->width(200)->position("absolute")->func("closeBlockAPP()")->get();
        $windows = $window->set()->titleSmall("Информация")->top(0)->left(0)->height(340)->width(400)->backgroundAlert()->content($mainMessage . $titleMessage . $closeBottom)->get();
        $HTTP = $elements->tag("div")->setClass("MsgBlockAPP")
            ->setStyle("top:0px;left:0px;width:447px;height:465px ")// для прорисовки сооющения по центру экрана
            ->setCaption($windows)->getHTTPTag();
        print "<style> </style>";
        print "<code> $HTTP </code>";
        print "<script> function outputBlockAPP() {}";
        print "function loadscript(){}";
        print "</script>";

        //self::MessageBlockDB($mainMSG, $titleMSG);
    }
}
