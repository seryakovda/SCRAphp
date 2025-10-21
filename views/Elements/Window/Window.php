<?php

namespace views\Elements\Window;

class Window
{
    const sizeHeadWinBig = 3;
    const sizeHeadWinNormal = 2;
    const sizeHeadWinSmall = 1;
    const sizeHeadWinNone = 0;
    const positionAbsolute = "absolute";
    const positionRelative = "relative";
    const positionInitial = "initial";
    const positionInherit = "inherit";

    const marginOn = 5;
    const marginOff = 0;
    private $marginMainDIV;
    private $headWin;
    private $TitleBig;
    private $TitleSmall;
    private $NameId;
    private $style;
    private $func;
    private $height;
    private $width;
    private $class;
    private $InnerHTTP;
    private $microHead;
    private $backgroundClass;
    private $floatLeft;
    private $shadow;

    private $btnCloseWindowFunction;

    public function set()
    {
        $this->headWin = $this::sizeHeadWinNormal;
        $this->backgroundClass = "backgroundNormal";
        $this->microHead = 0;
        $this->floatLeft = false;
        $this->btnCloseWindowFunction = false;
        $this->TitleBig = "";
        $this->TitleSmall = "";

        $this->InnerHTTP = "";

        $this->style = "";
        $this->class = "";
        $this->func = false;
        $this->shadow = 'shadowNormal';
        $this->NameId = '';
        $this->marginMainDIV = self::marginOn;
        return $this;
    }

    public function marginMainDIV_OFF($value = false)
    {
        if ($value === false)
            $this->marginMainDIV = self::marginOff;
        else
            $this->marginMainDIV = $value;
        return $this;
    }

    public function setBtnCloseWindowFunction($btnCloseWindowFunction)
    {
        $this->btnCloseWindowFunction = $btnCloseWindowFunction;
        return $this;
    }

    public function shadowNone()
    {
        $this->shadow = '';
        return $this;
    }

    public function shadowSmall()
    {
        $this->shadow = 'shadowSmall';
        return $this;
    }

    public function backgroundAlert()
    {
        $this->backgroundClass = "backgroundAlert";
        return $this;
    }

    public function setBackgroundCssClass($CssClass)
    {
        $this->backgroundClass = $CssClass;
        return $this;
    }

    public function headSizeBig()
    {
        $this->headWin = self::sizeHeadWinBig;
        return $this;
    }

    public function headSizeNormal()
    {
        $this->headWin = self::sizeHeadWinNormal;
        return $this;
    }

    public function headSizeSmall()
    {
        $this->headWin = self::sizeHeadWinSmall;
        return $this;
    }

    public function headSizeNone()
    {
        $this->headWin = self::sizeHeadWinNone;
        return $this;
    }

    public function sizeHead($val)
    {
        $this->headWin = $val;
        return $this;
    }

    public function titleBig($val)
    {
        $this->TitleBig = $val;
        return $this;
    }

    public function titleSmall($val)
    {
        $this->TitleSmall = $val;
        return $this;
    }

    public function style($val)
    {
        $this->style = $this->style . $val . ";";
        return $this;
    }

    public function top($val)
    {
        $this->style = $this->style . "top:" . $val . "px;";
        return $this;
    }

    public function left($val)
    {
        $this->style = $this->style . "left:" . $val . "px;";
        return $this;
    }

    public function height($val,$command_Height = "height-min:")
    {
        $this->height = $val;
        $this->style = $this->style . $command_Height . $val . "px;";
        return $this;
    }

    public function width($val)
    {
        $this->width = $val;
        $this->style = $this->style . "width:" . $val . "px;";
        return $this;
    }

    public function position($val)
    {
        $this->style = $this->style . "position:" . $val . ";";
        return $this;
    }

    public function class_($val)
    {
        $this->class = $this->class . " " . $val;
        return $this;
    }

    public function func($val)
    {
        $this->func = $val;
        return $this;
    }

    public function nameId($val)
    {
        $this->NameId = $val;
        return $this;
    }

    public function content($val)
    {
        $this->InnerHTTP = $this->InnerHTTP . $val;
        return $this;
    }

    public function floatLeft()
    {
        $this->floatLeft = true;
        return $this;
    }

    public function get()
    {
        $element = new \views\Elements\VElements;
        $BTN = new \views\Elements\Button\Button();

        $replsaeHeightStyle = "";
        $replsaeTopStyle = "";
        $replsaeTopSmallHead = "";
        $head1 = "";
        $head2 = "";
        $headHeight = 0;
        if ($this->headWin == $this::sizeHeadWinBig) {
            $headHeight = 105;
        } elseif ($this->headWin == $this::sizeHeadWinNormal) {
            $replsaeTopSmallHead = "top:17px;";
            $headHeight = 53;
        } elseif ($this->headWin == $this::sizeHeadWinSmall) {
            $replsaeTopSmallHead = "top:3px;";
            $headHeight = 24;
        }

        if ($this->headWin == $this::sizeHeadWinBig) {
            $head1 = $element->tag("div")->setClass("WinHead1")->setCaption($this->TitleBig)->getHTTPTag();
        }
        if ($this->headWin > $this::sizeHeadWinNone) {
            $head2 = $element->tag("div")->setClass("WinHead2")->setStyle($replsaeTopSmallHead)->setCaption($this->TitleSmall)->getHTTPTag();
        }
        $head3 = '';
        if ($this->btnCloseWindowFunction !== false) {
            $btnClose = $BTN->set("X")
                ->width(23)->height(22)
                ->fontSmall()
                ->floateLeft()
                ->func($this->btnCloseWindowFunction)
                ->class_("borderRadiusTop")
                ->class_("borderRadiusBottom")
                ->get();
            $head3 = $element->tag("div")->setClass("WinHead3")
                ->setCaption($btnClose)->getHTTPTag();
        }
        $tr1 = "";
        if ($this->headWin != $this::sizeHeadWinNone) {

            $div1 = $element->tag("div")
                ->setStyle("position:relative")->setCaption($head3 . $head1 . $head2)->getHTTPTag();
            $td1 = $element->tag("td")->setStyle("height:" . $headHeight . "px")->setStyle("width:" . $this->width . "px")->setClass("backgroundInsert borderRadiusTop borderRadiusBottom")->setCaption($div1)->getHTTPTag();
            $tr1 = $element->tag("tr")
                ->setId("head__" . $this->NameId)
                ->setStyle("height:" . $headHeight . "px")->setCaption($td1)->getHTTPTag();
        }
        $heightBody = $this->height - $headHeight;
        $div2 = $element->tag("div")
            ->setId($this->NameId)
            ->setStyle("position:relative;height:100%;width:100%")
            ->setCaption($this->InnerHTTP)
            ->getHTTPTag();

        $td2 = $element->tag("td")->setCaption($div2)->setStyle("display: block;    border-spacing: 0px;")->getHTTPTag();
        $tr2 = $element->tag("tr")
//            ->setStyle("position: fixed")
//            ->setStyle("height:" . ($this->height-$headHeight) . "px")
            ->setCaption($td2)->getHTTPTag();
        $element->tag("table")->setStyle("height:" . $this->height . "px; border:0px")
            ->setCaption($tr1 . $tr2);
        if (strlen((string)$this->width) == 0)
            $element->setStyle("width:100%");
        else
            $element->setStyle("width:" . $this->width . "px");
        $table = $element->getHTTPTag();

        $floatLeft = $this->floatLeft === false ?  "" : "float:left";
        $element->tag("div")->setId("WinMain__" . $this->NameId)->setClass("WinMain " . $this->backgroundClass)
            ->setStyle("display: table")
            ->setClass($this->shadow)
            ->setClass($this->class)
            ->setStyle($floatLeft)
            ->setStyle("padding:0px")
            ->setStyle("margin:{$this->marginMainDIV}px")
            ->setStyle($this->style)

            ->setCaption($table);
        if ($this->func !== false){
            $element->setFunction('onclick="' . $this->func . '"');
        }
        $HTTPWindow = $element->getHTTPTag();
        /*
        $HTTPWindow = $element->tag("div")->setCaption($HTTPWindow)
              ->setStyle("margin-right:5px")
              ->setStyle("display: table")
              ->setStyle("float:left")
              ->getHTTPTag();
  */
        return $HTTPWindow;
    }
}