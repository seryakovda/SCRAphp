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
    private $ipCameraTrigger;
    private $ipCameraView;
    function __construct()
    {
        $user = \models\User::get();
        $this->DoorIndex = $user->data['US_DoorIndexFromOrion'];
        //$this->ipCameraTrigger = $user->data['ListIpCameraForTrigger'];
        $this->ipCameraView = $user->data['ListIpCameraForView'];
        $this->VIEW = new VIEW();
        //$this->VIEW->DEV=true;
        $this->MODEL = new MODEL();

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
/*
 http://dev-scra/index.php?r0=Authorization&r1=enterLogin&login=user&password=123456&LevelAuthorization=1

 <br />
<b>Fatal error</b>:  Uncaught PDOException: SQLSTATE[42S02]: [Microsoft][ODBC Driver 18 for SQL Server][SQL Server]Недопустимое имя объекта &quot;STRING_SPLIT&quot;. in /var/www/html/dev-scra/DB/Connection.php:498
Stack trace:
#0 /var/www/html/dev-scra/DB/Connection.php(498): PDOStatement-&gt;execute()
#1 /var/www/html/dev-scra/forms/SKUD/EventsMonitor2/MODEL.php(141): DB\Connection-&gt;SQLExec()
#2 /var/www/html/dev-scra/forms/SKUD/EventsMonitor2/Control.php(67): forms\SKUD\EventsMonitor2\MODEL-&gt;GetEvents()
#3 /var/www/html/dev-scra/forms/FormsControl.php(190): forms\SKUD\EventsMonitor2\Control-&gt;GetEvents()
#4 /var/www/html/dev-scra/models/Router.php(247): forms\FormsControl-&gt;run()
#5 /var/www/html/dev-scra/models/Router.php(162): models\Router-&gt;runInstruction()
#6 /var/www/html/dev-scra/index_ajax.php(29): models\Router-&gt;AppRun()
#7 {main}
  thrown in <b>/var/www/html/dev-scra/DB/Connection.php</b> on line <b>498</b><br />
 */