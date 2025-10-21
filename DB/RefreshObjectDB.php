<?php


namespace DB;

use \DB\ObjectDB;
use Properties\Security;
use views\mPrint;

class RefreshObjectDB
{
    const View = 'View';
    const Proc = 'Proc';
    const Func = 'Func';
    public $MSSQL;
    public function __construct($nameObjectDB)
    {
        $this->setNameObjectDB($nameObjectDB);
        $this->MSSQL = Security::getTypeDB();
    }


    /**
     * @var \DB\ObjectDB_tot_PHP
     */
    private $nameObjectRefreshInterface;
    private $nameObjectDB;
    private $className;
    
    /**
     * @var \DB\ObjectDB
     */
    private $object;
    
    private $propertiesInToProject;
    private $propertiesInToDataBase;

    /**
     * @param mixed $nameOjectDB
     */
    public function setNameObjectDB($nameObjectDB): void
    {
        $this->nameObjectDB = $nameObjectDB;
    }



    /**
     * @param $className
     * @return $this
     */
    public function setClassName($className)
    {
        $this->className = $className;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getPropertiesInToDataBase()
    {
        $conn = new \DB\Connect();
        if ($this->MSSQL == Security::TYPE_dB_MS_SQL)
            $query = "  SELECT        name
                        FROM            
                             sys.objects
                        WHERE        
                              (type IN ('TR', 'FN', 'P', 'V')) AND 
                              (
                                  CASE [type] 
                                      WHEN 'TR' THEN 'Trig' 
                                      WHEN 'FN' THEN 'Func' 
                                      WHEN 'P' THEN 'Proc' 
                                      WHEN 'V' THEN 'View' 
                                  END = '$this->nameObjectDB')";

        if ($this->MSSQL == Security::TYPE_dB_My_SQL)
            $query = "
            SELECT 
                    name,
                    type
                FROM (
                    -- Процедуры и функции
                    SELECT 
                        ROUTINE_NAME AS name,
                        CASE ROUTINE_TYPE
                            WHEN 'PROCEDURE' THEN 'Proc'
                            WHEN 'FUNCTION' THEN 'Func'
                        END AS type
                    FROM information_schema.ROUTINES 
                    WHERE ROUTINE_SCHEMA = DATABASE()
                    
                    UNION ALL
                    
                    -- Триггеры
                    SELECT 
                        TRIGGER_NAME AS name,
                        'Trig' AS type
                    FROM information_schema.TRIGGERS 
                    WHERE TRIGGER_SCHEMA = DATABASE()
                    
                    UNION ALL
                    
                    -- Представления
                    SELECT 
                        TABLE_NAME AS name,
                        'View' AS type
                    FROM information_schema.VIEWS 
                    WHERE TABLE_SCHEMA = DATABASE()
                ) AS objects
                WHERE type = CASE '$this->nameObjectDB'
                    WHEN 'Trig' THEN 'Trig'
                    WHEN 'Func' THEN 'Func' 
                    WHEN 'Proc' THEN 'Proc'
                    WHEN 'View' THEN 'View'
                    ELSE type
                END
                ORDER BY name;
            ";
        $allObjectsDB = $conn->complexQuery($query)->fetchAll();

        foreach ($allObjectsDB as $key => $row){
            $SQL = $this->getTextQueryObject($row['name']);
            $this->propertiesInToDataBase[$row['name']]['LevelQuery'] = false;
            $this->propertiesInToDataBase[$row['name']]['SQL'] = $SQL;

        }
        return $this->propertiesInToDataBase;
    }


    private function getTextQueryObject($name)
    {
        $textQuery = "";
        $conn = new \DB\Connect();

        if ($this->MSSQL == Security::TYPE_dB_MS_SQL) {
            $command = "exec sp_helptext '$name'";
            $textArray = $conn->complexQuery($command)->fetchAll();
            foreach($textArray as $key => $item){
                $textQuery = $textQuery . $item['Text'];
            }
        }

        if ($this->MSSQL == Security::TYPE_dB_My_SQL) {
            switch ($this->nameObjectDB){
                case 'View':
                    $obj = 'View';
                    $getField = 'Create View';
                    break;
                case 'Proc':
                    $obj = 'Procedure';
                    $getField = 'Create Procedure';
                    break;

            }
            $command = "SHOW CREATE $obj $name";
            $textQuery = $conn->complexQuery($command)->fetchField($getField);
        }


        return $textQuery;
    }


    /**
     * @return mixed
     */
    public function getPropertiesInToProject()
    {
        $this->propertiesInToProject = Array();
        $d = dir( $_SERVER['DOCUMENT_ROOT']."/DB/SQL/".$this->nameObjectDB);
        while (false !== ($className = $d->read())) {
            $extend = explode(".",$className);
            $extend = end($extend);
            if ( !(($className == '.') or ($className == '..')) ){
                if ($extend == 'php'){
                    $className = str_replace('.php','',$className);
                    // очищаем массив
                    $arrObject = Array();
                    //создаём объект на по имени
                    $object = $this->setClassName($className)->createObject();
                    $arrObject['LevelQuery'] = $object->getLevelQuery();
                    $arrObject['SQL'] = $object->get_SQL_QUERY();
                    $this->propertiesInToProject[$className] = $arrObject;
                }
            }
        }
        $d->close();
        return $this->propertiesInToProject;
    }


    /**
     * @return \DB\ObjectDB
     */
    public function createObject() :\DB\ObjectDB 
    {
        // описываем класс и его рапсположение
        $class = "\DB\SQL\\$this->nameObjectDB\\$this->className";

        // создаём и инициализируем объект
        $this->object = new $class;
        $this->object->init();

        return $this->object;
    }

    /**
     * получение одномерного массива с именами полей полученного из массива
     * @param $arr
     * @return array
     */
    private function getOnlyColumnName($arr)
    {
        $retArray = Array();
        foreach ($arr as $column => $prop){
            $retArray[] = $column;
        }
        return $retArray;
    }


    public function refresh()
    {
        $this->getPropertiesInToProject();
        $this->getPropertiesInToDataBase();
        $listNameTableProject = $this->getOnlyColumnName($this->propertiesInToProject);
        $listNameTableDataBase = $this->getOnlyColumnName($this->propertiesInToDataBase);

        $listDelete = array_diff($listNameTableDataBase,$listNameTableProject);
        if (!empty($listDelete)){
            mPrint::R("Удаление объектов $this->nameObjectDB",mPrint::YELLOW);
            mPrint::R($listDelete);
            $this->deleteObjectsDB($listDelete);
        }
        mPrint::R("пересоздание объектов $this->nameObjectDB",mPrint::YELLOW);
        $this->reCreateDbObjects();
    }


    private function reCreateDBObjects()
    {
        $maxLevelQuery = $this->getMaxLevelQuery();
        for ($LevelQuery = 1; $LevelQuery <= $maxLevelQuery; $LevelQuery++){
            foreach ($this->propertiesInToProject as $nameObject => $properties){
                if ($properties['LevelQuery'] == $LevelQuery){//Если уровень объекта совпадает переходим к его пересозданию
                    $this->reCreateDBObject($nameObject);
                }
            }
        }
    }



    private function reCreateDbObject($nameObject)
    {
        $conn = new \DB\Connect();
        $this->deleteObjectDB($nameObject);
        try {
            $query = $this->propertiesInToProject[$nameObject]['SQL'];
            $conn->complexQuery($query);
            mPrint::R($nameObject);
            // в случае успешного создания объекта формируем список полей

            $classRefreshInterface = "\\DB\\{$this->nameObjectDB}_to_PHP";
            $this->nameObjectRefreshInterface = new $classRefreshInterface;
            $this->nameObjectRefreshInterface->setTypeObject($this->nameObjectDB);
            $this->nameObjectRefreshInterface->setObjectDB($nameObject);
            $this->nameObjectRefreshInterface->runProcess();

        } catch (\PDOException $e) {
            mPrint::PDO($e,$query);
            mPrint::R("Попытка восстановить исходный объект = [$nameObject] базы",mPrint::GREEN);
            try {
                $query = $this->propertiesInToDataBase[$nameObject]['SQL'];
                $conn->complexQuery($query);
                mPrint::R("Попытка восстановить исходный объект = [$nameObject] УДАЛАСЬ!",mPrint::GREEN);
            } catch (\PDOException $e) {
                mPrint::PDO($e,$query);
                mPrint::R("Попытка восстановить исходный объект = [$nameObject] не удалась",mPrint::RED);
            }
            mPrint::R("Процесс остановлен",mPrint::RED);
            exit;
        }
    }



    /**
     * возвращает количество уровней объектов.
     * У всех один, но у объектов типа View может быть несколько уровней,
     * т.к. они могут зависить от друг от друга
     * @return int
     */
    private function getMaxLevelQuery()
    {
        $LevelQuery = 1;

        foreach ($this->propertiesInToProject as $nameTable => $properties){
            if ($LevelQuery < $properties['LevelQuery']){
                $LevelQuery = (int) $properties['LevelQuery'];
            }
        }

        return $LevelQuery;
    }




    /**
     * удаления списка объектов
     * @param array $listDelete перечень удаляемых объектов БД
     */
    private function deleteObjectsDB(array $listDelete)
    {
        foreach ($listDelete as $objectDB){
            $this->deleteObjectDB($objectDB);
        }
    }


    /**
     * Удаление указанной таблицы
     * @param string $objectDB имя удаляемой таблицы
     */
    private function deleteObjectDB(string $objectDB)
    {
        $conn = new Connect();

        $DropNameTypeObject = '';
        switch ($this->nameObjectDB){
            case self::Func:
                $DropNameTypeObject = "FUNCTION";
                break;
            case self::Proc:
                $DropNameTypeObject = "PROCEDURE";
                break;
            case self::View:
                $DropNameTypeObject = "VIEW";
                break;
        }
        $dbo = '';
        if ($this->MSSQL == Security::TYPE_dB_MS_SQL)
            $dbo = 'dbo.';
        $conn->complexQuery("DROP $DropNameTypeObject IF EXISTS $dbo$objectDB");
    }

}