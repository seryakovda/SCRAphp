<?php

namespace forms\SYS;

use DB\Table\Users;
use \models\_G_session;
use \models\ErrorLog;

/**
 * Created by PhpStorm.
 * User: rezzalbob
 * Date: 24.09.2019
 * Time: 18:00
 */
class Control extends \forms\FormsControl
{
    /**
     * @var \forms\SYS\MODEL
     */
    public $MODEL;

    function __construct()
    {
        $this->MODEL = new MODEL();
        parent::__construct();

    }


    public function defaultMethod()
    {
        return false;
    }


    public function registrationKeyAPI()
    {
        $answer = Array();
        $answer['state'] = 'false';
        $answer["sessionHandle"] = "";
        if ($res = $this->MODEL->detectKeyAPI($_REQUEST['keyAPI'])) {
            $answer['state'] = 'true';
            $answer["sessionHandle"] = session_id();
            $_SESSION['modelExtension'] = $res[\DB\Table\API_keys::modelExtension];
        }
        header("content-type:application/json");
        print json_encode($answer);
        session_write_close();
    }

    public function autorisation()
    {
        $user = new \models\User();
        $answer = Array();
        $answer['state'] = 'false';
        if ($id = $user->login($_REQUEST['login'],$_REQUEST['pass'])){
            $answer['state'] = 'true';
            _G_session::id_user($id);
        };
        header("content-type:application/json");
        $json_data = json_encode($answer);
        \models\ErrorLog::saveError($json_data);
        print $json_data;
        session_write_close();
    }

    public function downloadImage()
    {
        $image = new \models\Images();
        $image->setIdFile($_REQUEST['idFile']);
        header("content-type:image/jpeg");
        echo $image->getImageFromDB();
    }

    public function downloadReport()
    {
        $report = new \models\Reports();
        print json_encode($report->getInfo($_GET['id_report']));
    }


    public function getPhotoFromOrion()
    {

    }
    /*
        public function runReports()
        {
            $report = new  \models\Reports();
            $report->setWait($_REQUEST['wait']);
            print $report->createReport($_REQUEST['report']);
        }
    */
    public function sendSetUpData()
    {
        _G_session::typeDevice($_REQUEST['device']);
        _G_session::widthMobile($_REQUEST['widthBrowse']);
        _G_session::heightMobile($_REQUEST['heightBrowse']);
    }



    public function fixingAnError()
    {
        /*
        $conn = new \DB\Connect();
        $conn->table("fixingAnError")
            ->set("JsonTXT",$_REQUEST['error'])
            ->insert();
        */
    }


}