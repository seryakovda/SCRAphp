<?php
namespace forms\SKUD\EventsMonitor2;

use DB\Connection;
use DB\Table\Requisites;
use DB\View\View_EC_RowsCatalog;
use models\_G_session;
use \DB\View\View_Orion_getEvents;
use views\Elements\Window\Window;


class VIEW extends \forms\FormView
{
    public $windowContent;
    public $dataGrid_Object;
    private $P;

    private $ipCameraTrigger = false;
    /**
     * @var MODEL
     */
    public $MODEL;
    public $listURL = Array();
    public function initClass()
    {
        $this->TXT_headSmallTitle="Монитор событий";

        $this->BTN = new \views\Elements\Button\Button();
        $this->WND = new \views\Elements\Window\Window();
    }

    public function printMainWindow()
    {
        $this->listURL = json_encode($this->listURL, JSON_THROW_ON_ERROR|JSON_UNESCAPED_UNICODE);
        include "HTML.php";
    }


    public function setMODEL($MODEL): void
    {
        $this->MODEL = $MODEL;
    }

    public function ViewPass()
    {
        $HTML = "";
        while ($data = $this->dataGrid_Object->fetch()){
            $HTML = $HTML . $this->ViewPass_WS($data);
        }
        return $HTML;
    }

    public function setIpCameraTrigger($ipCameraTrigger)
    {
        $this->ipCameraTrigger  = $ipCameraTrigger;
    }

    public function ViewNumberPlate()
    {
        $HTML = "";
        $data = $this->MODEL->getListCameraEvent();
        while ($res = $data->fetch()){
            $win = new Window();
            $HTML = $HTML . $win->set()->nameId('winOneCamEvent')
                    ->width(475)
                    ->height(250)
                    ->headSizeNone()
                    ->shadowSmall()
                    ->floatLeft()
                    ->content($this->numberPlate($res))
                    ->get();
            ;
        }
        return $HTML;
    }

    public function numberPlate($dataEventNumber)
    {
        $HTML = '';

        $photoObject = new \views\Elements\Media\Media();
        $urlImage = "index_ajax.php?parent=SKUD&r0=EventsMonitor2&r1=getPhotoNumberplate&id={$dataEventNumber['id']}";
        $HTML = $HTML . $photoObject
                ->width( 200)->height(120)
                //        ->floateLeft()
                ->class_("shadowNormal")
                ->style("margin-left:3px;margin-top:3px")
                ->image($urlImage);

        $txt = new \views\Elements\MyText\MyText();

        $HTML .= $txt->text($dataEventNumber['number']."</br>".date('H:i:s',strtotime($dataEventNumber['dateTimeEvent'])))
            ->width(200)->height(50)
            ->fontSizeBig()
            ->floateLeft()
            ->horizontalPosCenter()
            ->position('relative')
            ->get();


        $win = new Window();
        $HTML =  $win->set()->nameId('win111')
            ->width(210)
            ->headSizeNone()
            ->shadowSmall()
            ->floatLeft()
            ->content($HTML)
            ->get();

        $greed = new \views\Elements\Grid\Grid();


        $data = $this->MODEL->getDataByNumber($dataEventNumber['number']);
        $HTML .= $greed->GNew('ListCar')
            ->width(280)->row(15)
            ->ColumnID('id')
            //->Column('nameORG')->Column_Caption('Организация')->Column_Width(200)
            ->Column("FIO")->Column_Caption('ФИО')->Column_Width(300)->Column_textFontMicro()
            ->allInsertOff()
            ->GetTable($data);


        return $HTML;
    }

    private function getHeight()
    {
        $height = (_G_session::widthMobile() - 435)/19*9;
        if ($this->ipCameraTrigger === false){
            $height =_G_session::heightMobile() - 10;
        }
        return $height;
    }


    public function MsgBlockAPP()
    {
        $winCamV = new Window();
        $winDor = new Window();
        $winCamT = new Window();
        $height = $this->getHeight();

        $this->windowContent = $winCamV->set()->nameId('winCamV')
            ->width(_G_session::widthMobile() - 435)
            ->height($height)
            ->headSizeNone()
            ->shadowSmall()
            ->setBackgroundCssClass('')
            ->floatLeft()
            ->content($this->VideoStreamWindow())
            ->marginMainDIV_OFF()
            ->get();


        if ($this->ipCameraTrigger !== false){
            $this->windowContent = $this->windowContent . $winCamT->set()->nameId('winCamT')
                    ->width(_G_session::widthMobile() - 435)
                    ->height(_G_session::heightMobile()-820)
                    ->setBackgroundCssClass('')
                    ->headSizeNone()
                    ->shadowSmall()
                    ->floatLeft()
                    ->marginMainDIV_OFF()
                    ->content($this->ViewNumberPlate())
                    ->get();
        }



        $this->windowContent = $this->windowContent .  $winDor->set()->nameId('winDor')
            ->width( 420)
            ->height(_G_session::heightMobile()-20,"height:")
            ->setBackgroundCssClass('')
            ->headSizeNone()
            ->shadowSmall()
            ->style("display:block; overflow-y: auto;")
            ->content($this->ViewPass())
            ->get();

    }


    public function VideoStreamWindow()
    {

        // http://10.13.18.154:555/Ea8fKquV?container=mjpeg&stream=main
        $HTML = '';
        $widthMain = (_G_session::widthMobile() - 530); // оставшийся размер от специальных окон
        $height = $this->getHeight();
        $this->listURL = Array();



        if ($listScreenCamera = $this->MODEL->getListScreenCamera()){
            foreach ($listScreenCamera as $key => $item){
                $HTML .= $this->BTN
                    ->set($item['caption'])
                    ->width(50)
                    ->height(50)
                    ->floateLeft()
                    ->horizontalPosCenter()
                    ->func("setIndexScreenCamera({$item['id']})") // nameVar
                    ->get();
            }
        }
        $win = new Window();
        $HTML =  $win->set()->nameId("ModifyDisplay")
                ->headSizeNone()
                ->shadowSmall()
                ->height($height)
                ->width(70)
                ->setBackgroundCssClass('')
                ->floatLeft()
                ->content($HTML)
                ->marginMainDIV_OFF()
                ->get();

        $i = 1;


        $dataArr = $this->MODEL->getListCameraForVideoStream();
        foreach ($dataArr as $key => $res ){
            $ipS = $res['cameraServerIp'];
            $canal = $res['cameraChannelGuid'];
            $width = $widthMain;
            $x = $res['x'];
            $command = '$width = '."(int) ($widthMain $x);";
            eval($command);

            $urlImage = "http://$ipS:555/$canal?container=mjpeg&amp;stream=main";
            $this->listURL[] = $urlImage;
            $img = "<img id = \"imgCamV$i\" width=\"$width\" src=\"$urlImage\">";

            $win = new Window();
            $HTML = $HTML .  $win->set()->nameId("winCamV$i")
                    ->headSizeNone()
                    ->shadowSmall()
                    ->setBackgroundCssClass('')
                    ->floatLeft()
                    ->content($img)
                    ->marginMainDIV_OFF()
                    ->get();
            $i++;
        }

        $win = new Window();
        $HTML = $HTML .  $win->set()->nameId("DateTime")
                ->headSizeNone()
                ->shadowNone()
                ->height(30)
                ->width(1000)
                ->setBackgroundCssClass('backgroundDatetime')
                ->floatLeft()
                ->content('Date time')
                ->marginMainDIV_OFF()
                ->get();
        return $HTML;
    }

    private function ViewPass_WS($data)
    {
        $HTML = '';
        $photoObject = new \views\Elements\Media\Media();
        $HTML = $HTML . $photoObject
                ->width( 150)->height(200)
                ->floateLeft()
                ->class_("shadowNormal")
                ->style("margin-left:3px;margin-top:3px")
                ->image("index_ajax.php?parent=SKUD&r0=EventsMonitor2&r1=downloadImage&idFile={$data['id']}");

        $HTML = $HTML . $this->printElementPass('Организация',$data['CompN'],"fontSizeSmall");
        $HTML = $HTML . $this->printElementPass('Фамилия',$data['Name']);
        $HTML = $HTML . $this->printElementPass('Имя',$data['FirstName']);
        $HTML = $HTML . $this->printElementPass('Отсество',$data['MidName']);
        $HTML = $HTML . $this->printElementPass('Подраздение',$data['DivN'],"fontSizeSmall");
        $HTML = $HTML . $this->printElementPass('Должность',$data['name_pPost'],"fontSizeSmall");
        if ($data['Comment'] != '')
            $HTML = $HTML . $this->printElementPass('Дополнительно!!! ',$data['Comment'],"fontSizeSmall");

        //$HTML = $HTML . $this->printElementPass('пропуск',"","fontSizeSmall");

        $exit = $data['Remark'];
        $exitArr = explode(" ",$exit);
        $exit = $exitArr[1];
//        $HTML = $HTML . $this->printElementPass('Дата прохода",
//                $exit." - ".
//                date("d.m.Y- H:i:s",strtotime($data['TimeVal])),
//                "fontSizeSmall");
        $txt = new \views\Elements\MyText\MyText();

        $HTML .= $txt->text($exit." - ".
            date("d.m.Y",strtotime($data['TimeVal']))."</br>".date("H:i:s",strtotime($data['TimeVal'])))
            ->width(148)->height(30)
            ->borderOff()
            ->floateLeft()
            ->horizontalPosLeft()
            ->position('relative')
            //->style('background-color:'.$data['color_doorIndex])
            ->fontSizeSmall()
            ->get();
        $HTML .= $txt->text($data['name_Acesspoint'])
            ->width(242)->height(30)
            ->borderOff()
            ->floateLeft()
            ->horizontalPosLeft()
            ->position('relative')
            //->style('background-color:'.$data['color_doorIndex])
            ->style('margin-bottom:5px')
            ->fontSizeBig()
            ->horizontalPosRight()
            ->get();

        $this->WND->set()
            ->width(410)
            ->height(205)
            ->floatLeft()
            ->shadowSmall()
            ->headSizeNone()
            ->marginMainDIV_OFF(1)
            ->content($HTML);
        if ($data['status_list'] != 0)
            $this->WND->backgroundAlert();
        if ($data['Config'] == 32896) //если пропуск заблокирован
            $this->WND->setBackgroundCssClass('backgroundAlert_block');
        if ($data['Comment'] != '')
            $this->WND->setBackgroundCssClass('backgroundAlert1');

        $HTML = $this->WND->get();


        return $HTML;
    }


    private function printElementPass($caption,$dataString,$fontSize = "fontSizeBig")
    {
        $txt = new \views\Elements\MyText\MyText();
        $HTML = '';
        $height = 30;
        if ($fontSize == "fontSizeSmall")
            $height = 30;
        $HTML .= $txt->text($dataString)

            ->width(245)->height($height)
            ->borderOff()
            ->floateLeft()
            ->horizontalPosLeft()
            ->position('relative')
            // ->style("margin-left:70px")
            ->$fontSize()
            ->get();

        return $HTML;
    }


}