<?php

namespace views\Elements\VerticalMenu;

use models\_G_session;

class VerticalMenu
{
    private $ClassName, $data;

    private $nameMenu = "Меню";

    private $fonsize = 'textFontSmall';


    public function setFonSizeBig()
    {
        $this->fonsize = 'textFontBig';
        return $this;
    }
    public function setClassNameMenu($val)
    {
        $this->ClassName = $val;
        return $this;
    }

    public function setDataMenu($val)
    {
        $this->data = $val;
        return $this;
    }


    public function setNameMenu(string $nameMenu)
    {
        $this->nameMenu = $nameMenu;
        return $this;
    }

    public function getMenu()
    {
        $ClassName = $this->ClassName;
        $data = $this->data;

        $vMenu = ".vMenu$ClassName {
        /*.borderRadiusTop*/
        /*.$this->fonsize*/
    width:100%;
    height:auto;
    padding:0px 0px 1px 0px;
    overflow:hidden;
    box-sizing:border-box;
    }";

        $h1 = '.h1{
        /*.backgroundInsert*/
        /*.textColorWhite*/
        text-align: center;
        position:relative;
        display:block;
        margin:0px;
        padding:9px 0px;
    }';
        $vMenu_ul = '.vMenu' . $ClassName . ' ul{
    display:block;
    margin:0px;
    padding:0px;
    list-style:none;
    }';
        $vMenu_ul_li = '.vMenu' . $ClassName . ' ul li{
    display:block;
    margin:0px;
    padding:0px;
    }';
        $vMenu_ul_li_p = '.vMenu' . $ClassName . ' ul li p{
    /*.borderBottom*/
    /*.background-Menu*/
    /*.textColorBlack*/
    margin: 0;
    display:block;
    padding:10px 7px 10px 10px;
    text-decoration:none;
    }';
        $vMenu_ul_li_p_hover = '.vMenu' . $ClassName . ' ul li p:hover {
    /*.background-Insert-Menu*/
    /*.textColorWhite*/
    }';
        $vMenu_ul_li_act_p = '.vMenu' . $ClassName . ' > ul > li.act > p{
    /*.background-Insert-Menu*/
    /*.textColorWhite*/
    }';
        $vMenu_ul_li_li_p = ".vMenu$ClassName  ul li li p{
    /*.$this->fonsize*/
    padding:7px 7px 8px 30px;
    }";
        $vMenu_ul_ul = '.vMenu' . $ClassName . ' ul ul{
    display:none;
}';

        $vMenu_ul_li_current_menu_item = '.vMenu' . $ClassName . ' ul li.current-menu-item ul, .vMenu' . $ClassName . ' ul li.current-menu-parent ul{
    display:block;
}';

        $script = '       var XX=$(this).parent("li");
                if(!XX.hasClass("act")){
                    $(".vMenu' . $ClassName . ' > ul > li.act > ul").slideUp(500);
                    $(".vMenu' . $ClassName . ' > ul > li.act").removeClass("act");
                    XX.addClass("act");
                    XX.children("ul").slideDown(500);
                }
                return false;';
        ob_start();
        print "<style>";
        print "$vMenu";
        print "$h1";
        print "$vMenu_ul";
        print "$vMenu_ul_li";
        print "$vMenu_ul_li_p";
        print "$vMenu_ul_li_p_hover";
        print "$vMenu_ul_li_act_p";
        print "$vMenu_ul_li_li_p";
        print "$vMenu_ul_ul";
        print "$vMenu_ul_li_current_menu_item";
        print "</style>";
        $styleWidth = '';
        if (_G_session::typeDevice() != _G_session::workstation){
            $width = _G_session::widthMobile() - 40;
            $styleWidth = "style = 'width:{$width}px'";
        }
        print "<div id='{$ClassName}' $styleWidth class='vMenu" . $ClassName . "'>";
        print "<div class='h1'>{$this->nameMenu}</div>";
        print "<ul>";
        $memGroup = 0;
        if (!is_null($data)) {
            while ($res = $data->fetch()) {
                $idMenu = $res['id'];
                $group = $res['group_m'];
                $uroven = $res['level'];
                $nameMenu = rtrim($res['name']);
                $command = $res['command'];
                if ($uroven == 0) {
                    if ($memGroup == 1) {
                        print "</ul></li>";
                    }
                    $memGroup = 1;
                    print "<li><p id=\"{$ClassName}_{$idMenu}\" onclick='" . $script . "' class='current-menu-item'>$nameMenu</p><ul>";
                } else {
                    print "<li><p id=\"{$ClassName}_{$idMenu}\" onclick='" . rtrim($command) . "'  >$nameMenu</p></li>";
                }
            }
            print "</ul></li>";
            $output = ob_get_contents();
            ob_end_clean();
            return $output;
        }
    }


}