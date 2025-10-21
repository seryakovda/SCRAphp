<?php


namespace DB;


use Properties\Security;
use views\mPrint;

abstract class ObjectDB_tot_PHP
{
    const View = 'View';
    const Proc = 'Proc';
    const Func = 'Func';

    public $MSSQL;

    public $objectDB = null;

    public $typeObject = null;

    public $fileHandle = null;

    public $pathObject = null;

    public $columns = Array();


    public function __construct()
    {
        $this->pathObject = $_SERVER['DOCUMENT_ROOT']."/DB/";
        $this->MSSQL = Security::getTypeDB();
    }

    /**
     * @param mixed $objectDB
     */
    public function setObjectDB($objectDB): void
    {
        $this->objectDB = $objectDB;
    }

    /**
     * @param null $typeObject
     */
    public function setTypeObject($typeObject): void
    {
        $this->typeObject = $typeObject;
    }


    public function runProcess()
    {
        $this->getListColumnsFromDB();
        $this->openFile();
        $this->createHandleFile();
        $this->addFields();
        $this->createEndFile();

    }

    public function openFile()
    {
        $path_name = "$this->pathObject$this->typeObject/$this->objectDB.php";
        $this->fileHandle = fopen($path_name, 'wb+');
    }

    /**
     * Получение списка полей для указанной таблицы
     */
    public function getListColumnsFromDB()
    {
        $conn = new \DB\Connect();
        $retArray = Array();
        $IS = 'COLUMNS'; // схема запроса смотри INFORMATION_SCHEMA.txt
        $CN = 'COLUMN_NAME';// Поле содержащее имя столбца или параметра функции
        $W = 'TABLE_NAME';//по какому полю строитьсмя условие
        switch ($this->typeObject){

            case self::View:
                $IS = 'COLUMNS';
                break;

            case self::Func:
            case self::Proc:
                $IS = 'PARAMETERS';// схема запроса смотри INFORMATION_SCHEMA.txt
                $CN = 'PARAMETER_NAME';// Поле содержащее имя столбца или параметра функции
                $W = 'SPECIFIC_NAME';//по какому полю строитьсмя условие
                break;

        }


        $conn->table("INFORMATION_SCHEMA.$IS")
            ->where($W,$this->objectDB)
            ->orderBy("ORDINAL_POSITION");
        if ($this->typeObject == self::Func)
            $conn->where("PARAMETER_MODE","IN");

        $data = $conn->select($CN);

        while ($res = $data->fetch()){
            switch ($this->typeObject){
                case self::Func:
                case self::Proc:
                    $retArray[] = substr($res[$CN],1); // первым символом параметра в mssql идёт @, его удаляем
                    break;
                default:
                    $retArray[] = $res[$CN];
            }

        }

        $this->columns = $retArray;
    }


    public function addFields()
    {
        foreach ($this->columns as $key => $ColumnName){
            $this->addFieldsInToObject($ColumnName);
        }
    }

    public function  createHandleFile() {}

    public function addFieldsInToObject(string $ColumnName){}

    public function  createEndFile()
    {
        fwrite($this->fileHandle,"}\r\n");
        fclose($this->fileHandle);
    }

}