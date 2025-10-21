<?php

namespace models;

use \DB\View\View_security_all_reportsForClass;


class ControlElements
{
    private static $_object;
    private $arraySecurity;
    private $conn;
    private $files;

    private $blockNum;

    function __construct()
    {
        $this->conn = new \DB\Connect();
        $this->createArraySecurity();
        $this->init();
    }

    private function createArraySecurity()
    {
        $this->arraySecurity = Array();
        $data = $this->conn->table("View_security_right_Elements")
            ->where("id_user", $_SESSION['id_user'])
            ->select();
        while ($res = $data->fetch()) {
            $this->arraySecurity[] = $res['element'];
        }
    }

    private function init()
    {
        $this->blockNum = 0;
    }

    public static function get()
    {
        if (!isset(self::$_object)) {
            self::$_object = new self;
        }
        return self::$_object;
    }

    /**
     * @param $blockNum
     * @return $this
     */
    public function setBlockNum($blockNum)
    {
        $this->blockNum = $blockNum;
        return $this;
    }

    public function getElementsHTTP($class, $deleteEndName = false)
    {
        $class = str_replace("\\VIEW", "", $class);
        if ($deleteEndName !== false) {
            $class = str_replace($deleteEndName, "", $class);
        }
        $HTTP = "";
        if (\models\_G_session::superUser() == '1'){
            $this->conn->table("security_Elements");
        }else{
            $this->conn->table("View_security_all_elementsForClassAndUser");
            $this->conn->where("id_user", $_SESSION['id_user']);
        }
        $this->conn->where("_class", $class)
//            ->where("ORG", $_SESSION['ORG'])
            ->orderBy("sort");
        $dataObject = $this->conn->select();

        while ($res = $dataObject->fetch()) {
            $class = trim($res['_class']);
            $name = trim($res['name']);
            $class = "\\$class\\Elements\\$name";

            $object = new $class;
            if ( ( $object->blockNum == $this->blockNum ) || ( $object->blockNum == 0 ) ) {
                $HTTP = $HTTP . $object->Get();
            }

        }

        $this->init();// Сбрасывеант настройки $this->forORG_Only и $object->blockNum на поумолчанию
        return $HTTP;
    }

    public function getReports_returnDadaObject($class)
    {
        $class = str_replace("\\VIEW", "", $class);
        $dataObject = $this->conn->table(View_security_all_reportsForClass::getName())
            //->where("ORG", $_SESSION['ORG'])
            ->where("_class", "\\$class")
            ->orderBy('name')
            ->select();
        return $dataObject;
    }


    public function getNameMethod($class, $method)
    {
        $res = explode("::", $method);
        return $class . "_" . $res[1];
    }

    public function registerAllElements()
    {
        print __CLASS__;
        print "</br>";
        print __METHOD__;
        print "</br>";
        $this->files = array();
        $d = new \DB\Table\security_Elements_tmp();
        $d->delete();
        $d = new \DB\Table\security_method_tmp();
        $d->delete();

        $this->scanDirs($_SERVER['DOCUMENT_ROOT'] . "/forms", "/forms", "forms");
        $this->replaceDataFromTMP();

    }

    private function replaceDataFromTMP()
    {
        $this->conn->table("security_class")->delete();
        $this->conn->complexQuery("insert into security_class select * from security_class_tmp");

        $this->conn->table("security_Elements")->delete();
        $this->conn->complexQuery("insert into security_Elements select * from security_Elements_tmp");

        $this->conn->table("security_method")->delete();
        $this->conn->complexQuery("insert into security_method select * from security_method_tmp");

    }

    private function scanDirs($start, $path, $lastDir)
    {
        print "</br> ====================== старт  ======================= </br>";
        $handle = opendir($start);
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                if (is_dir($start . '/' . $file)) {
                    $this->scanDirs($start . '/' . $file, $path . "/" . $file, $file);

                } else {
                    print "$lastDir ==  $file </br>";
                    if ($lastDir == "Elements") {
                        array_push($this->files, $path . "/" . $file);
                        $arrFile = explode(".", $file);
                        $nameFile = $arrFile[0];
                        $path = str_replace("/forms", "forms", $path);
                        $element = str_replace("/", "\\", "$path/$nameFile");
                        $class = str_replace("/Elements", "", $path);
                        $class = str_replace("/", "\\", $class);
                        print "</br>class = $class</br>";

                        print "</br>Element = $element</br>";
                        try{
                            $this->conn->table("security_Elements_tmp")
                                ->set("element", "$class\\$nameFile")
                                ->insert();
                        }catch (\PDOException $e){}

                        $class1 = "\\$class\\Elements\\$nameFile";
                        $Object = new $class1;
                        //$Object = new \forms\LS\Catalog\Elements\Accruals();

                        $this->conn->table("security_Elements_tmp")
                            ->set("_class", $class)
                            ->set("name", "$nameFile")
                            ->set("caption", $Object->caption)
                            ->set("sort", $Object->sort)
                            ->where("element", "$class\\$nameFile")
                            ->update();
                        unset($Object);
                    }
                    if ($file == "Control.php") {
                        array_push($this->files, $path . "/" . $file);
                        print "methods $path</br>";

                        $class = $path;
                        $class = str_replace("/", "\\", $class);

                        $class1 = $path . "/Control";
                        $class1 = str_replace("/", "\\", $class1);
                        $Object = new $class1;

                        try{
                            $this->conn->table("security_class_tmp")
                                ->set("_class", $class)
                                ->insert();
                        }catch (\PDOException $e){}

                        $this->conn->table("security_class_tmp")
                            ->where("_class", $class)
                            ->set("name", $Object->getTXT_headSmallTitle())
                            ->update();

                        unset($Object);

                        $method = get_class_methods("$class\\Control");
                        foreach ($method as $value) {
                            $fullRight = 0;
                            if (
                                ($value == "__construct") ||
                                //    ($value == "run") ||
                                ($value == "defaultMethod") ||
                                ($value == "defineObjectName") ||
                                ($value == "defineViewVariable") ||
                                ($value == "defineModelVariable") ||
                                ($value == "defineFilterParent") ||
                                ($value == "getFormForParent") ||
                                ($value == "getGreedForParent") ||
                                ($value == "getListFilter") ||
                                ($value == "getIdMainGreed") ||
                                ($value == "setFilter") ||
                                ($value == "setTable") ||
                                ($value == "setFormWidth") ||
                                ($value == "setAllInsertOff") ||
                                ($value == "getTXT_headSmallTitle") ||

                                ($value == "formForSelect")
//                                ($value == "classNamePrepare")
                            ) {
                                $fullRight = 1;
                            }
                            print "</br>method $class::$value";
                            $this->conn->table("security_method_tmp")
                                ->set("method", "$class\\$value")
                                ->set("_class", "$class")
                                ->set("name", "$value")
                                ->set("fullRight", $fullRight)
                                ->insert();

                        }
                    }

                }
            }
        }
        closedir($handle);
    }
}