<?php

namespace forms\SYS\ReplacePassword;

use models\_G_session;

class Control extends \forms\FormsControl
{
    function __construct()
    {
        $this->VIEW = new VIEW();
        $this->VIEW->DEV = true;
        $this->MODEL = new MODEL();
        parent::__construct();
    }

    public function defaultMethod()
    {
        parent::defaultMethod();
        $this->setFormWidth(360);
        $this->VIEW->createBottomWindowEdit();
        $this->VIEW->mainWindow();
        $this->VIEW->printMainWindow();
    }

    public function ReplacePassword()
    {
        $Password1 = $_REQUEST['Password1'];
        $Password2 = $_REQUEST['Password2'];
        if ($Password1 != $Password2){
            \views\Views::MsgBlock('Ошибка','Пароли не совпадают');
            return false;
        }
        $u = new \models\User();
        $u->ReplacePassword($_REQUEST['Password1'],_G_session::id_user(),'0');
        print "OK";
        return true;
    }



}