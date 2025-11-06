<?php
namespace forms\SKUD\EventsMonitor2;

use DB\Proc\Proc_RefreshDateEnd;
use DB\Table\pList;
use \DB\View\View_Orion_getEvents;
use forms\FormView;
use models\_G_session;
use Mpdf\Tag\P;

class Control extends \forms\FormsControl
{
    private $DoorIndex;
    private $ipCameraTrigger = false;
    private $ipCameraView;
    function __construct()
    {
        $this->VIEW = new VIEW();
        //$this->VIEW->DEV=true;
        $this->MODEL = new MODEL();

        $user = \models\User::get();
        $resolution = explode('.',$user->data['ResolutionScreenForPost']);
        _G_session::widthMobile($resolution[0],0);
        _G_session::heightMobile($resolution[1]);


        $this->DoorIndex = $user->data['US_DoorIndexFromOrion'];

        $this->ipCameraTrigger = array_key_exists('ListIpCameraForTrigger',$user->data) ? $user->data['ListIpCameraForTrigger'] : false;
        $this->VIEW->setIpCameraTrigger($this->ipCameraTrigger);

        $this->ipCameraView = $user->data['ListIpCameraForView'];

        if ($this->ipCameraTrigger !== false)
            $this->MODEL->setIpCameraTrigger($this->ipCameraTrigger);

        $this->MODEL->setListCameraViewIp($this->ipCameraView);

        parent::__construct();
    }

    public function defineTable()
    {
//        $this->setTable(View_Orion_getEvents::getName());
//        $this->setColumnID(View_Orion_getEvents::id);
//        $this->setTOP(' (4) ');
//        $this->setOrderString(View_Orion_getEvents::TimeVal." DESC");
    }


    public function defaultMethod()
    {
        parent::defaultMethod();
        $this->setFormWidth(_G_session::widthMobile());
        $this->defineTable();
        $this->init();

        $data = $this->MODEL->getData_event($this->DoorIndex);
        $this->VIEW->setDataGridObject($data);

        $this->VIEW->setMODEL($this->MODEL);

        $this->VIEW->MsgBlockAPP();
        $this->VIEW->printMainWindow();
    }

    public function init()
    {
        $this->setFilter("DoorIndex",$this->DoorIndex,"IN");
        $this->defineTable();

    }


    public function GetEvents()
    {
        session_write_close() ;
        $this->MODEL->GetEvents($this->DoorIndex);
        $this->setFormWidth(_G_session::widthMobile());
        $this->defineTable();
        $this->init();

        $data = $this->MODEL->getData_event($this->DoorIndex);
        $this->VIEW->setDataGridObject($data);
        $greed = $this->VIEW->ViewPass();
        $this->VIEW->printElement($greed);
    }

    public function GetEventsNumber()
    {
        session_write_close();
        $this->MODEL->GetEventsNumber();
        $this->VIEW->setMODEL($this->MODEL);
        $HTML = $this->VIEW->ViewNumberPlate();
        $this->VIEW->printElement($HTML);
    }


    public function GetEventsIpCamera()
    {
        $this->setFormWidth(_G_session::widthMobile());
        $this->defineTable();
        $this->init();

        session_write_close();
        $this->MODEL->GetEvents($this->DoorIndex);

        $data = $this->MODEL->getData();
        $this->VIEW->setDataGridObject($data);
        $greed = $this->VIEW->ViewPass();
        $this->VIEW->printElement($greed);
    }

    public function getPhotoNumberplate()
    {
        $d = new \DB\View\View_NumberCameraImage();

        $imageBlob = $d
            ->where($d::id_event,$_REQUEST['id'])
            ->where($d::f_main,1)
            ->select($d::img)->fetchField($d::img);

        $image = new \Imagick();
        $image->readImageBlob($imageBlob);
        $image->setImageFormat('jpeg');
        $image->setImageCompression(\Imagick::COMPRESSION_JPEG);
        $image->setImageCompressionQuality(50);

        $image->resizeImage(150,205, \Imagick::FILTER_UNDEFINED  , 1, true);


        header("content-type:image/jpeg");
        echo $image->getImageBlob();
    }


    public function downloadImage()
    {
        $d = new pList();

        //$image->setCompressionQuality(100);
        $imageBlob = $d->where($d::ID,$_REQUEST['idFile'])
            ->select($d::Picture)
            ->fetchField($d::Picture);

        header("content-type:image/jpeg");
        if (strlen($imageBlob) > 1000){
            $image = new \Imagick();
            $image->readImageBlob($imageBlob);
            $image->setImageFormat('jpeg');
            $image->setImageCompression(\Imagick::COMPRESSION_JPEG);
            $image->setImageCompressionQuality(50);

            $image->resizeImage(150,205, \Imagick::FILTER_UNDEFINED  , 1, true);


            echo $image->getImageBlob();
        }
    }
}
