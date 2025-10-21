<?php

namespace forms\Authorization;

use   \DB\Table\Users;
use models\_G_session;

class Control extends \forms\FormsControl
{
private $user;
    function __construct()
    {
        $this->VIEW = new VIEW();
        $this->MODEL = new MODEL();
        parent::__construct();
        $this->user = \models\User::get();
    }


    public function defaultMethod()
    {
        $this->VIEW->setLevelAuthorization($_REQUEST['LevelAuthorization']);
        $this->VIEW->AuthorizationForm();
    }

    public function enterLogin()
    {
        $login = empty($_GET["login"]) ? "login" : $_GET["login"];
        $password = empty($_GET["password"]) ? "password" : $_GET["password"];
        $this->findLogin($login,$password);
    }


    public function findLogin($login,$password,$runApp = true)
    {
        if ($id = $this->user->login($login, $password)) {
            $this->loginOK($id,$runApp);
        } else {
            // Если логин пароль НЕ верен то
            // либо Возвращаем инфу об ошибке
            // либо возвращаем логическое false
            if ($runApp === true){
                \views\Views::MsgBlock("ОШИБКА", "Неверное имя пользователя или пароль");
            } else
                return false;
        }
        return true;
    }
    
    public function loginOK($id,$runApp)
    {
        session_regenerate_id();
        $this->user->updateIdSession($id);
        $this->user->find($id);

        _G_session::id_user($this->user->data[Users::id]);
        $_SESSION[Users::id_human] = $this->user->data[Users::id_human];
        $_SESSION[Users::superUser] = $this->user->data[Users::superUser];
        $_SESSION[Users::runDefaultScript] = $this->user->data[Users::runDefaultScript];


        $router = \models\Router::get();

        // Если логин пароль верен то
        // либо запускаем приложение
        // либо возвращаем логическое true
        if ($runApp === true){
            $router->AppRun($_SESSION[Users::runDefaultScript]);
        } else
            return true;
    }
}