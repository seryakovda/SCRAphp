<?php
/**
 * входящие параметры для работы в методах POST и GET
 * parent - родитель, содержит путь от каталога [forms] до каталога (не вглючая) исполняемого класса [r0]
 * r0 - имя каталога исполняемого класса
 * r1 - имя метода в классе [r0]
 *
 * пример:
 * _G_Ajax({
 * type:"GET",
 * url:"index_ajax.php",
 * data:{parent:"LS\\Edit",r0:"HeaderSettings"},
 * dataType: 'text',
 * success: function(data){ integrationsScriptCSS("mainEditLS",data)}
 * });
 * путь: [корень приложения]\forms\LS\Edit\HeaderSettings\*.*
 *
 */

namespace models;
use \DB\Table\Users;
use \models\ErrorLog;

use \models\User;


class Router
{
    private static $_object;
    public $route;
    public $r1;

    private $f_grant;
    private $conn;
    private $parent;

    // Router constructor.
    // Определяются переменные маршрутизации

    function __construct()
    {
        $this->conn = new \DB\Connect();

        $this->parent = empty($_REQUEST["parent"]) ? false :  $_REQUEST["parent"];
        $this->route = empty($_REQUEST["r0"]) ? "index" : $_REQUEST["r0"];
        $this->r1 = empty($_REQUEST["r1"]) ? "" : $_REQUEST["r1"];
    }

    /**
     * @return Router Возвращает когдато созданный объект, (SingleTone)
     */
    public static function get()
    {
        if (!isset(self::$_object)) {
            self::$_object = new self;
        }
        return self::$_object;
    }

    /**
     * @param bool $newRoute не помню нафига я её сделал....
     */
    public function AppRun($newRoute = false)
    {

        // предполагалось что это будет контроллер ресурса но там осталось 2 общих функции
        // думаю перенести их в сюда в router
        //$controller = new \controllers\SiteController();
        // проверяем номер пользователя в переменной сессии если пользователь не задан выставляем 0
        $detectedUser = $this->detectActiveUser();


        // нужно додумать... не работает в случае обращения с сайтов

        //$this->detectStatusMonth($detectedUser);


        // проверяем блокировку базы данных (метод скорее всего нужно переместить сюда в роутер)


        //  Если пользователь не с сайта и и произиводиться попытка первого входа или полного обновления страницы
        //  при неавторизованом мользователе
        //  ТО
        //  Сначало нужно отправить Скелет приложения

        if (($detectedUser > 0) and (($this->route == "index") or ($this->route == "Authorization"))) {
            $this->route = $_SESSION[Users::runDefaultScript];
            //$this->route = "SKUD\EventsMonitor";
        }


        // Если база заблокирована ($userBlock!=0)
        // и если базу заблокировал ктото другой ($userBlock!=$detectedUser)
        // Значит невиг дальше работать!
        // Возможен альтернативный маршрут... но это я не помню нафига писал :)


        if ($newRoute) {
            $this->route = $newRoute;
        }



        // Если пользователь определён как пользователь системы (не сайта)
        // то смотрим в модель пользователя и читаем его свойства

        if ($detectedUser > 0) {
            $User = User::get();
            $User->find($detectedUser);
        }

        // возможно пользователю необходимо сменить пароль
//        $renewPassword = $this->getRenewPassword();
//        if ($renewPassword == '1'){
//            $this->parent = 'SYS';
//            $this->route = "ReplacePassword";
//            $this->r1 = '';
//        }


        // получает $this->checkMonth и $this->f_grant
        // мктод обработки, прав доступа, к методам конструктора форм
        // находится в разработке сейчас почти ни начто не влияет

        $this->checkRight($detectedUser);


        // $this->f_grant получает значение в методе $this->checkRight($detectedUser);
        if ($this->f_grant == 1) {
            switch ($detectedUser) {
                case 0: { // если пользователь ещё не ваторизован
                    switch ($this->route) {
                        // задаём пустые переменные в массиве сессий
                        // при следующем шаге загрузим  SkeletonApp а в неё уже Authorization
                        case "index" :
                            $_SESSION["id_user"] = 0;
                            $_SESSION["idMenu"] = 0;
                            break;
                        case "Authorization":
                            $run = new \forms\Authorization\Control();
                            $run->run();
                            break;
                        case "SYS":
                            $_SESSION["id_user"] = 0;
                            $_SESSION["idMenu"] = 0;
                            $modelExtension = array_key_exists("modelExtension",    $_SESSION)?     $_SESSION["modelExtension"]    : "";

                            $this->runInstruction($modelExtension);
                            break;
                    }
                    break;
                }
                default: {
                    //  срабатывание при условии блокировки базы
                    switch ($this->route) {
                        case "Блокировка_базы" : {
                            \views\Views::MsgBlock("Внимание", "В данное время производится глобальная операция, в связи с этим доступ Ограничен");
                            break;
                        }
                        case "SYS":
                            $modelExtension = array_key_exists("modelExtension",    $_SESSION)?     $_SESSION["modelExtension"]    : "";
                            $this->runInstruction($modelExtension);
                            break;
                        // Погнали в метод выполнения
                        default: {
                            $this->runInstruction();
                            break;
                        }
                    }
                }
            }
        } else {
            \views\Views::MsgBlock("Внимание", "Нет доступа.");

        }
    }

    /**
     * @return int
     * определяет имеется в переменной сесии id_user
     */
    private function detectActiveUser()
    {
        if (is_array($_SESSION))
            return array_key_exists("id_user", $_SESSION) ? $_SESSION["id_user"] : 0;
        else
            return 0;
    }

    /**
     * @param $detectedUser
     * int ID пользователя у которого нужно проверить права
     */
    private function checkRight($detectedUser)
    {
        /*
        $conn = new \DB\Connect();
        $resSecurity = $conn->table('proc_get_right_user')
            ->set("route", $this->route)
            ->set("r1", $this->r1)
            ->set('user', $detectedUser)
            ->SQLExec()
            ->fetch();

        $this->f_grant = $resSecurity['f_grant'];
        $this->checkMonth = $resSecurity['checkMonth'];
        */
        $this->f_grant = 1;
    }

    private function runInstruction($modelExtension = "")
    {
        // тут просто!!!
        // Все инструкции Инструкции группированы для конкретной формы хранятся в forms
        // имя основного маршрута $this->route
        // далее вызывается контроллер формы Control.php он управляет моделями и вьюверами
        // стартовый исполняемый метод каждого контроллера run()
        // ;
        $conn = new \DB\Connect();
        $saveLog = true;
        if (array_key_exists('parent',$_REQUEST)) // если в запросе есть parent
            if ($_REQUEST['parent'] == 'SUPERUSER'){ // если parent НЕ указаывает на SUPERUSER
                $saveLog = false;
            }
        if (array_key_exists('r1',$_REQUEST))
            if ($_REQUEST['r1'] == 'uploadPhoto'){ // это грузиться фотка ... пока не фиксируем
                $saveLog = false;
            }

        if ($saveLog){
            $json_request = json_encode($_REQUEST,true);

            $security_LOG = new \DB\Table\security_LOG();
            $security_LOG
                ->set('id_user',$_SESSION['id_user'])
                ->set('JSON_request',$json_request)
                ->insert();
        }

        //
        $l ="\\";
        $l1 ="/";
        if ($this->parent === false ){
            $l ="";
            $l1 ="";
        }
        $SearchFile = $_SERVER['DOCUMENT_ROOT']."/forms$l1$this->parent/$this->route/Control$modelExtension.php";
        $SearchFile = str_replace('\\','/',$SearchFile);

        if (file_exists($SearchFile)){
            $class = "\\forms$l$this->parent\\$this->route\\Control$modelExtension";
            $run = new $class;
            $run->run();
        }else{
            \views\Views::MsgBlock("Внимание !!! ", "Класс ненайден");
        }


    }


    public function MessageBlockDB()
    {
        \views\Views::MsgBlock("Внимание", "В данное время доступ Ограничен");
    }

    private function getRenewPassword()
    {
        $users = new \DB\Table\Users();
        if (_G_session::id_user() == null){
            return "0";
        }
        return $users
            ->where($users::id,_G_session::id_user())
            ->select($users::renewPassword)->fetchField($users::renewPassword);
    }


}



