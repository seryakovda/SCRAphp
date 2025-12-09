<?php


namespace DB;



use Properties\Security;
use views\mPrint;

class RefreshTables
{

    private $exisTablesInToBatabase = Array();
    private $exisTablesInToProject = Array();
    private $IDENTITY_INSERT_JOB = true;
    private $tablesWithDefaultRows = Array();
    /**
     * @var \DB\Table
     */
    private $object;

    private  $nameTable;

    public $MSSQL = false;

    public function __construct()
    {
        $this->MSSQL = Security::getTypeDB();
    }

    /**
     * @param mixed $nameTable
     */
    public function setNameTable($nameTable): void
    {
        $this->nameTable = $nameTable;
    }



    /**
     * @return string
     */
    public function getNameTable():string
    {
        return $this->nameTable;
    }

    private function NotExcludedTable($name)
    {
        switch ($name) {
            case 'Images':
            case 'HumanPhoto':
            case 'OTIPB_ReadEmail':
            case 'OTIPB_Images':
            case 'nXms_CameraScreenshot':
                return false;
            default:
                return true;
        }

    }

    /**
     * создание новых и удаление ненужных таблиц
     */
    public function createNewTale()
    {
        $this->getExistTablesFromDataBase();
        $this->getExistTablesFromProject();

        mPrint::R('Таблицы содержащиеся в проекте',mPrint::BLUE);
        mPrint::R($this->exisTablesInToProject);

        mPrint::R('Таблицы содержащиеся в БД',mPrint::BLUE);
        mPrint::R($this->exisTablesInToBatabase);

        mPrint::R('таблицы которые необходимо создать',mPrint::BLUE);
        $createTables = array_diff($this->exisTablesInToProject,$this->exisTablesInToBatabase);
        mPrint::R($createTables);
        $this->createTablesFromArray($createTables);

        mPrint::R('таблицы которые необходимо удалить',mPrint::YELLOW);
        $deleteTables = array_diff($this->exisTablesInToBatabase,$this->exisTablesInToProject);
        mPrint::R($deleteTables);
        $this->deleteTables($deleteTables);
    }



    /**
     * сопоставление полей таблиц которые находятся в разработке и таблиц которые в БД
     * для выявления расхождений и пересоздания таблиц без потери данных
     */
    public function matchingTable()
    {
        mPrint::R("Соопоставление таблиц",mPrint::YELLOW);
        $this->getExistTablesFromProject();

        foreach ($this->exisTablesInToProject as $nameTable){
            mPrint::R($nameTable);
            $this->setNameTable( $nameTable);
            $fieldsDataBase = $this->getArrayPropertiesTableFromDataBase();
            $fieldsProject = $this->getArrayPropertiesTableFromProject();
            $recreateTable = false;

            $Arr = array_diff_key($fieldsProject['columns'],$fieldsDataBase['columns']);
            if (!empty($Arr)){
                mPrint::R("Поля таблицы $nameTable которые нужно Добавтиь",mPrint::BLUE);
                mPrint::R($Arr);
                $recreateTable = true;
            }

            $Arr = array_diff_key($fieldsDataBase['columns'],$fieldsProject['columns']);
            if (!empty($Arr)){
                mPrint::R("Поля таблицы $nameTable которые нужно удалить",mPrint::BLUE);
                mPrint::R($Arr);
                $recreateTable = true;
            }

            // Если поля таблиц совпали  то прорабатываем поиск расхождений в типе и длинне типа
            if ($recreateTable === false){
                foreach ($fieldsProject['columns'] as $key => $itemP){
                    $itemDB = $fieldsDataBase['columns'][$key];
                    if ($itemDB['type'] != $itemP['type']) {
                        $s1 = $itemDB['type'];
                        $s2 = $itemP['type'];
                        mPrint::R("Поля $key таблицы $nameTable имеет другой тип ( DB - ( $s1 ) и Proj - ( $s2 ))",mPrint::BLUE);
                        $recreateTable = true;
                    }
                    if ($itemDB['size'] != $itemP['size']) {
                        $s1 = $itemDB['size'];
                        $s2 = $itemP['size'];
                        mPrint::R("Поля $key таблицы $nameTable имеет другую размерность ( DB - ( $s1 ) и Proj - ( $s2 ))",mPrint::BLUE);
                        $recreateTable = true;
                    }
                }
            }
            $identifierColumnDB = 0;
            if (array_key_exists('identifierColumn',$fieldsDataBase))
                if ($fieldsDataBase['identifierColumn'] != '')
                    $identifierColumnDB = 1;

            $identifierColumnPHP = 0;
            if (array_key_exists('identifierColumn',$fieldsProject))
                if ($fieldsProject['identifierColumn'] != '')
                    $identifierColumnPHP = 1;

            

            if ($identifierColumnPHP != $identifierColumnDB){
                mPrint::R("необходимо пересоздать таблицу т.к изменился столбец автоинкремента (Было/стало) DB=$identifierColumnDB PHP = $identifierColumnPHP ({$fieldsDataBase['identifierColumn']})({$fieldsProject['identifierColumn']})",mPrint::BLUE);
                $recreateTable = true;
                if ($identifierColumnDB < $identifierColumnPHP){
                    $this->IDENTITY_INSERT_JOB = false;
                }
            }elseif ( ($identifierColumnPHP == 1) || ($identifierColumnDB == 1))
                if ($fieldsDataBase['identifierColumn'] != $fieldsProject['identifierColumn']){
                    mPrint::R("необходимо пересоздать таблицу т.к изменился столбец автоинкремента",mPrint::BLUE);
                    $recreateTable = true;
                }

            // сравнение кластеризованного индекса
            if($this->key_compare_func($fieldsDataBase['primaryIndex'],$fieldsProject['primaryIndex']) === false){
                mPrint::R("необходимо пересоздать таблицу т.к изменился кластеризованный индекс",mPrint::BLUE);
                $recreateTable = true;
            }


            if ($recreateTable){
                mPrint::R("Запуск полного пересоздания таблицы $nameTable ",mPrint::BLUE);
                $this->reCreateTable($fieldsProject,$fieldsDataBase);
            }
        }
    }



    /**
     * Выявление расхождений индексов только для удаления либо создания
     */
    public function matchingIndexes_For_DROP_CREATE()
    {
        mPrint::R("ыявление расхождений индексов только для удаления либо создания",mPrint::YELLOW);

        $this->getExistTablesFromProject();

        foreach ($this->exisTablesInToProject as $nameTable) {
            mPrint::R($nameTable);

            $this->setNameTable($nameTable);

            $fieldsDataBase = $this->getArrayPropertiesTableFromDataBase();
            $fieldsProject = $this->getArrayPropertiesTableFromProject();


           // сравнение набора некластеризованных индексов
            $listIndexesInProject = Array();
            if (array_key_exists('nonclusteredIndex',$fieldsProject))
                $listIndexesInProject   = $this->getOnlyColumnName($fieldsProject['nonclusteredIndex']);

            $listIndexesInDB = Array();
            if (array_key_exists('nonclusteredIndex',$fieldsDataBase))
            $listIndexesInDB        = $this->getOnlyColumnName($fieldsDataBase['nonclusteredIndex']);

            //Удаление не нужных индексов
            $arr = array_diff_assoc($listIndexesInDB,$listIndexesInProject);
            if(!empty($arr)){
                mPrint::R("Удаление индексов",mPrint::BLUE);
                mPrint::R($arr);
                $this->DROP_listIndexes($arr);
            }

            //создание совершенно новых индексов
            $arr = array_diff_assoc($listIndexesInProject,$listIndexesInDB);
            if(!empty($arr)){
                mPrint::R("создание совершенно новых индексов",mPrint::BLUE);
                mPrint::R($arr);
                $this->CREATE_listIndexes($arr,$fieldsProject['nonclusteredIndex']);
            }
        }
    }



    /**
     * Выявление расхождений индексов и пересоздания
     */
    public function matchingIndexes_For_RECREATE()
    {
        mPrint::R("Выявление расхождений индексов и пересоздания",mPrint::YELLOW);
        $this->getExistTablesFromProject();

        foreach ($this->exisTablesInToProject as $nameTable) {
            mPrint::R($nameTable);
            $this->setNameTable($nameTable);
            $fieldsDataBase = $this->getArrayPropertiesTableFromDataBase();
            $fieldsProject = $this->getArrayPropertiesTableFromProject();

            $listIndexesInProject   = $this->getOnlyColumnName($fieldsProject['nonclusteredIndex']);

            foreach ($listIndexesInProject as $index){
                $indexProject = $fieldsProject['nonclusteredIndex'][$index];
                $indexDB = $fieldsDataBase['nonclusteredIndex'][$index];
                $res = $this->key_compare_func($indexProject,$indexDB);
                if($res === false){
                    mPrint::R("Пересоздание индекса $index",mPrint::BLUE);
                    $conn = new \DB\Connect();
                    $this->newObject($nameTable);

                    $query = $this->object->DROP_NoClusteredIndex($index);
                    mPrint::R($query,mPrint::LIGHT_BLUE);
                    $conn->complexQuery($query);

                    $query = $this->object->createNoClusteredIndex($index,$indexProject);
                    mPrint::R($query,mPrint::LIGHT_BLUE);
                    $conn->complexQuery($query);

                }
            }
        }
    }


    /**
     * Сопоставление массивов (сопоставляются ключи согласно своего расположения в массиве а также их значения)
     * если меются одинаковые ключи но находятся на разных местах это считается расхождением, т.к.
     * при построении индекса важно соблюсти расположение колонок внутри индекса
     * @param $a
     * @param $b
     * @return bool
     */
    private function key_compare_func($a, $b)
    {
        $ret = true;
        // если не совпадает по количеству элементов
        if ( count($a) != count($b) )
            $ret = false;

        //сли порядок элементов не совпадает и не соврадает тип сортировки
        while (current($a)){
            $key_a = key($a);
            $key_b = key($b);
            $val_a = current($a);
            $val_b = current($b);

            if ( $key_a != $key_b )
                $ret = false;

            if ( $val_a != $val_b )
                $ret = false;
            next($a);
            next($b);
        }
        return $ret;
    }


    private function recreate_ClusteredIndex($index_DB,$index_Project)
    {
        $conn = new \DB\Connect();
        $this->newObject($this->nameTable);

    }


    /**
     * Созхдание некластеризованных индексов согласно списка
     * @param array $listIndexes
     * @param array $indexProject
     */
    private function CREATE_listIndexes(Array $listIndexes,array $indexProject)
    {
        $conn = new \DB\Connect();
        $this->newObject($this->nameTable);

        foreach ($listIndexes as $nameIndex){

            $query = $this->object->createNoClusteredIndex($nameIndex,$indexProject[$nameIndex]);
            mPrint::R($query,mPrint::LIGHT_BLUE);
            $conn->complexQuery($query);
        }
    }


    /**
     * Удаление некластеризованных индексов вогласно списка
     * @param array $listIndexes
     */
    private function DROP_listIndexes(Array $listIndexes)
    {
        $conn = new \DB\Connect();
        $this->newObject($this->nameTable);

        foreach ($listIndexes as $nameIndex){
            $query = $this->object->DROP_NoClusteredIndex($nameIndex);
            mPrint::R($query,mPrint::LIGHT_BLUE);
            $conn->complexQuery($query);
        }
    }


    /**
     * пересоздание таблицы
     * @param $fieldsProject
     * @param $fieldsDataBase
     */
    private function reCreateTable($fieldsProject,$fieldsDataBase)
    {
        $this->deleteTable("{$this->nameTable}__TMP__");
        $this->createTable($this->nameTable,true);
        $this->loadDataToTMpTable($fieldsProject,$fieldsDataBase);
        $this->deleteTable("{$this->nameTable}");
        $this->createTable($this->nameTable);
        $this->loadDataFromTMpTable($fieldsProject,$fieldsDataBase);
        $this->deleteTable("{$this->nameTable}__TMP__");
    }


    /**
     * Получние построенного массива опиысывающего таблицу находящейся в БД
     * @return array
     */
    private function getArrayPropertiesTableFromDataBase()
    {
        $fieldsDataBase = $this->getFieldsForTableFromDataBase();

        if ($array = $this->CreatArrayForPrimaryIndex())
            $fieldsDataBase['primaryIndex'] = $array;

        if ($array = $this->CreatArrayForNonclusteredIndex())
            $fieldsDataBase['nonclusteredIndex'] = $array;
        return $fieldsDataBase;
    }


    /**
     * получение всех полей указанной таблицы из базы данных
     * @return array
     */
    private function getFieldsForTableFromDataBase()
    {
        $nameTable = $this->getNameTable();
        $variable = Array();
        $conn = new Connect();

        if ($this->MSSQL == Security::TYPE_dB_MS_SQL)
            $query =  "EXEC sp_columns $nameTable";
        if ($this->MSSQL == Security::TYPE_dB_My_SQL)
            $query =  "
                SELECT 
                    COLUMN_NAME,
                    COLUMN_DEFAULT as COLUMN_DEF,
                    CASE
                        when NUMERIC_PRECISION is null 
                          then 
                            CASE 
                              when CHARACTER_MAXIMUM_LENGTH is NULL then DATETIME_PRECISION
                              else CHARACTER_MAXIMUM_LENGTH 
                            END
                        else NUMERIC_PRECISION 
                    END  as PRECISION1,
                    CASE 
                        WHEN EXTRA LIKE '%auto_increment%' THEN 1 
                        ELSE 0 
                    END AS is_identity,
                    COLUMN_TYPE as TYPE_NAME
                    
                FROM information_schema.COLUMNS 
                WHERE TABLE_SCHEMA = DATABASE() 
                  AND TABLE_NAME = '$nameTable'
                ORDER BY ORDINAL_POSITION

            ";
        $dataArray = $conn->complexQuery($query)->fetchAll();



        if ($this->MSSQL == Security::TYPE_dB_MS_SQL)
            $query =  "SELECT * FROM sys.columns WHERE object_id = OBJECT_ID('$nameTable') order by column_id";
        if ($this->MSSQL == Security::TYPE_dB_My_SQL)
            $query =  "
            SELECT 
                    COLUMN_NAME as name,
                    COLUMN_DEFAULT as COLUMN_DEF,
                    CASE
                        when NUMERIC_PRECISION is null 
                          then 
                            CASE 
                              when CHARACTER_MAXIMUM_LENGTH is NULL then DATETIME_PRECISION
                              else CHARACTER_MAXIMUM_LENGTH 
                            END
                        else NUMERIC_PRECISION 
                    END  as PRECISION1,
                    CASE 
                        WHEN EXTRA LIKE '%auto_increment%' THEN 1 
                        ELSE 0 
                    END AS is_identity,
                    COLUMN_TYPE as TYPE_NAME
                    
                FROM information_schema.COLUMNS 
                WHERE TABLE_SCHEMA = DATABASE() 
                  AND TABLE_NAME = '$nameTable'
                ORDER BY ORDINAL_POSITION
            ";
        $data = $conn->complexQuery($query);



        while ($res = $data->fetch()){
            $dataArray2[$res['name']] = $res;
        }

        $columns = Array();
        $identifierColumn = false;
        foreach ($dataArray as  $key => $row ){
            if ($dataArray2[$row['COLUMN_NAME']]['is_identity'] == '1')
                $identifierColumn = $row['COLUMN_NAME'];

            if ($this->MSSQL == Security::TYPE_dB_MS_SQL)
                $type = str_replace(' identity','',$row['TYPE_NAME']);
            if ($this->MSSQL == Security::TYPE_dB_My_SQL) {
                $type = preg_replace("/[^\p{Latin}]/ui", '', $row['TYPE_NAME']);
                $type = str_replace(' identity', '', $type);
            }

            $PRECISION = array_key_exists('PRECISION',$row) ? $row['PRECISION'] : $row['PRECISION1'];
            if ($PRECISION == 1073741823){
                $type = 'nvarchar';
                $PRECISION = 'MAX';
            }

            if ($PRECISION == 2147483647){
                $type = 'varchar';
                $PRECISION = 'MAX';
            }


            $arr = Array();
            $arr['type'] = $type;

            $size = $PRECISION;

            $arr['size'] = Type::getSizeFalseForType($type,$size);

            $defaultValue = $row['COLUMN_DEF'];
            $defaultValue = str_replace("(N'",'',$defaultValue);
            $defaultValue = str_replace("('",'',$defaultValue);
            $defaultValue = str_replace("')",'',$defaultValue);

            $defaultValue = str_replace("((",'',$defaultValue);
            $defaultValue = str_replace("))",'',$defaultValue);

            if ($defaultValue != '')
                $arr['defaultValue'] = $defaultValue;

            //
            $columns[$row['COLUMN_NAME']] = $arr;
        }

        // Блок столбца identity
        if ($identifierColumn !== false)
            $variable['identifierColumn'] = $identifierColumn;


        $variable['columns'] = $columns;
        return $variable;
    }


    /**
     * получение массива описывающего некластеризованные индексы таблицы БД
     * @return mixed
     */
    private function CreatArrayForNonclusteredIndex()
    {
        $returnArray = false;
        $dataArray = $this->getColumnIndexFromDataBase(2);
        $len_NameTable = strlen($this->nameTable)+4;
        foreach ($dataArray as $key => $row){
            $nameIndex = substr($row['name_index'],$len_NameTable);
            $returnArray[$nameIndex][$row['name_column']] = $row['sort'];
        }
        return $returnArray;
    }



    /**
     * получение массива описывающего первичный (кластеризованный) индекс таблицы БД
     * @return mixed
     */
    private function CreatArrayForPrimaryIndex()
    {
        $returnArray = false;
        $dataArray = $this->getColumnIndexFromDataBase();
        foreach ($dataArray as $key => $row){
            $returnArray[$row['name_column']] = $row['sort'];
        }
        return $returnArray;
    }


    /**
     * получение информации об индексах находящихся в БД
     * @param int $primaryIndex первичный кластеризованный значени 1 некластеризованный значение 2
     * @return mixed
     */
    private function getColumnIndexFromDataBase($primaryIndex = 1)
    {
        $nameTable = $this->getNameTable();

        $conn = new Connect();
        if ($this->MSSQL == Security::TYPE_dB_MS_SQL)
            $query = "
            SELECT        TOP (100) PERCENT
                   sys.objects.object_id, 
                   sys.indexes.name AS name_index,
                   sys.columns.name AS name_column, 
                   sys.indexes.index_id, 
                   sys.indexes.type,
                   CASE 
                       WHEN indexes.[type] = 1 THEN 'Clustered index' 
                       WHEN indexes.[type] = 2 THEN 'Nonclustered unique index' 
                       WHEN indexes.[type] = 3 THEN 'XML index' 
                       WHEN indexes.[type] = 4 THEN 'Spatial index' 
                       WHEN indexes.[type] = 5 THEN 'Clustered columnstore index' 
                       WHEN indexes.[type] = 6 THEN 'Nonclustered columnstore index' 
                       WHEN indexes.[type] = 7 THEN 'Nonclustered hash index' 
                       END AS index_type, 
                   sys.indexes.type_desc,
                   sys.index_columns.key_ordinal,
                   sys.index_columns.partition_ordinal,
                   CASE
                        WHEN sys.index_columns.is_descending_key = 1 THEN 'DESC'
                        ELSE 'ASC'
                        END AS sort
            FROM
                sys.objects 
                    INNER JOIN sys.indexes 
                        ON 
                            sys.objects.object_id = sys.indexes.object_id 
                    INNER JOIN sys.index_columns 
                        ON 
                            sys.index_columns.object_id = sys.objects.object_id AND 
                            sys.indexes.index_id = sys.index_columns.index_id 
                    INNER JOIN sys.columns 
                        ON 
                            sys.index_columns.object_id = sys.columns.object_id AND 
                            sys.index_columns.column_id = sys.columns.column_id
            WHERE        
                  (sys.objects.object_id = OBJECT_ID('$nameTable')) AND 
                  (sys.indexes.type = $primaryIndex)
            ORDER BY 
                     sys.indexes.type, 
                     sys.indexes.index_id, 
                     sys.index_columns.key_ordinal
            ";
        if ($this->MSSQL == Security::TYPE_dB_My_SQL)
            $query="
            SELECT 
                s.TABLE_NAME AS object_id,
                s.INDEX_NAME AS name_index,
                s.COLUMN_NAME AS name_column,
                CASE 
                    WHEN s.INDEX_NAME = 'PRIMARY' THEN 1
                    ELSE ROW_NUMBER() OVER (PARTITION BY s.INDEX_NAME ORDER BY s.SEQ_IN_INDEX)
                END AS index_id,
                CASE 
                    WHEN s.INDEX_NAME = 'PRIMARY' THEN 1
                    ELSE 2
                END AS type,
                CASE 
                    WHEN s.INDEX_NAME = 'PRIMARY' THEN 'Clustered index'
                    WHEN s.NON_UNIQUE = 0 THEN 'Nonclustered unique index'
                    ELSE 'Nonclustered index'
                END AS index_type,
                CASE 
                    WHEN s.INDEX_NAME = 'PRIMARY' THEN 'PRIMARY'
                    WHEN s.NON_UNIQUE = 0 THEN 'UNIQUE'
                    ELSE 'INDEX'
                END AS type_desc,
                s.SEQ_IN_INDEX AS key_ordinal,
                0 AS partition_ordinal,
                CASE 
                    WHEN s.COLLATION = 'D' THEN 'DESC'
                    ELSE 'ASC'
                END AS sort
            FROM 
                information_schema.STATISTICS s
            WHERE 
                s.TABLE_SCHEMA = DATABASE()
                AND s.TABLE_NAME = '$nameTable'
                AND CASE 
                    WHEN s.INDEX_NAME = 'PRIMARY' THEN 1
                    ELSE 2
                END = $primaryIndex
            ORDER BY 
                s.INDEX_NAME,
                s.SEQ_IN_INDEX;
            ";

        return $conn->complexQuery($query)->fetchAll();
    }


    /**
     * получение масива описывающего таблицу находящуюся в проекте
     * @param $nameTable
     * @return mixed
     */
    private function getArrayPropertiesTableFromProject()
    {
        $nameTable = $this->getNameTable();

        $this->newObject($nameTable);

        $this->object->initColumn();

        $variable = $this->object->getDeclareVariable();

        return $variable;
    }


    /**
     * Получение массива содержащего перечень таблиц хранащихся в базе данных
     */
    private function getExistTablesFromDataBase()
    {
        $conn = new Connect();
        $this->exisTablesInToBatabase = Array();

        if ($this->MSSQL == Security::TYPE_dB_MS_SQL)
            $query =  "select name from sys.tables  order by name";

        if ($this->MSSQL == Security::TYPE_dB_My_SQL)
            $query =  "
                SELECT TABLE_NAME as name
                FROM information_schema.TABLES 
                WHERE TABLE_SCHEMA = DATABASE()
                ORDER BY TABLE_NAME;
            ";

        $dataArray = $conn->complexQuery($query)->fetchAll();
        foreach ($dataArray as  $key => $row ){
            $nameTable = $row['name'];
            if ($this->NotExcludedTable($nameTable))
                $this->exisTablesInToBatabase[] = $nameTable;
        }
    }


    /**
     * Получение перечня таблиц имеющихся в проекте
     */
    private function getExistTablesFromProject()
    {
        $this->exisTablesInToProject = Array();
        $d = dir( $_SERVER['DOCUMENT_ROOT']."/DB/Table");
        while (false !== ($fileName = $d->read())) {
            if ( !(($fileName == '.') or ($fileName == '..')) ){
                $nameTable = str_replace('.php','',$fileName);
                if ($this->NotExcludedTable($nameTable))
                    $this->exisTablesInToProject[] = $nameTable;
            }
        }
        $d->close();
    }



    /**
     * Получение перечня таблиц имеющих перечень строк по умолчанию
     */
    public function reRecordDefaultRows()
    {
        mPrint::R('Пересоздание таблиц с фиксированными записями',mPrint::YELLOW);

        $this->exisTablesInToProject = Array();
        $d = dir( $_SERVER['DOCUMENT_ROOT']."/DB/Table");
        while (false !== ($fileName = $d->read())) {
            if ( !(($fileName == '.') or ($fileName == '..')) ){
                $nameTable = str_replace('.php','',$fileName);
                $class = "\DB\Table\\$nameTable";
                $object = new $class;
                if ($object->defaultRows() !== false){
                    mPrint::R($nameTable);
                    $object->reRecordDefaultRows();
                }
            }
        }
        $d->close();
    }

    /**
     * Получение перечня таблиц имеющих перечень строк по умолчанию
     */
    public function reCreateTriggers()
    {
        mPrint::R('Пересоздание триггеров',mPrint::YELLOW);

        $this->exisTablesInToProject = Array();
        $d = dir( $_SERVER['DOCUMENT_ROOT']."/DB/Table");
        while (false !== ($fileName = $d->read())) {
            mPrint::R("|",mPrint::GREEN, false );
            if ( !(($fileName == '.') or ($fileName == '..')) ){
                $nameTable = str_replace('.php','',$fileName);
                $class = "\DB\Table\\$nameTable";
                $object = new $class;
                if ($object->triggers() !== false){
                    mPrint::R("-",mPrint::GREEN);
                    mPrint::R($nameTable);
                    $object->reCreateTriggers();
                }
            }
        }
        $d->close();
        mPrint::R("-",mPrint::GREEN);

    }


    /**
     * создание таблиц указанных в массиве
     * @param array $newTables массив имён таблиц
     */
    private function createTablesFromArray(array $newTables)
    {
        foreach ($newTables as $table){
            $this->createTable($table);
        }
    }


    /**
     * создание объекта
     * @param $nameClass
     */
    private function newObject($nameClass)
    {
        $class = "\DB\Table\\$nameClass";
        $this->object = new $class();
    }



    /**
     * создание таблицы
     * @param string $table
     * @param bool $TMP если значение принимает true
     */
    private function createTable(string $table, bool $TMP = false)
    {
        $this->newObject($table);
        if ($TMP)
            $this->object->setTMpNameTable("__TMP__");

        $this->object->create();
    }


    /**
     * удаления списка таблиц
     * @param array $deleteTables перечень удаляемых таблиц
     */
    private function deleteTables(array $deleteTables)
    {
        foreach ($deleteTables as $table){
            $this->deleteTable($table);
        }
    }


    /**
     * Удаление указанной таблицы
     * @param string $table имя удаляемой таблицы
     */
    private function deleteTable(string $table)
    {
        $conn = new Connect();
        $dbo = '';
        if ($this->MSSQL == Security::TYPE_dB_MS_SQL)
            $dbo = 'dbo.';

        $conn->complexQuery("DROP TABLE IF EXISTS $dbo$table");
    }

    /**
     * перенос данных их "боевой" во временную таблицу
     * @param $fieldsProject
     * @param $fieldsDataBase
     */
    private function loadDataToTMpTable($fieldsProject,$fieldsDataBase)
    {
        $fieldsOnlyProject = $this->getOnlyColumnName($fieldsProject['columns']);
        $fieldsOnlyDataBase = $this->getOnlyColumnName($fieldsDataBase['columns']);

        $lisctColumsProject = $this->listColumns(array_intersect($fieldsOnlyProject,$fieldsOnlyDataBase));
        //$lisctColumsProject

        $insertInto = "INSERT INTO {$this->nameTable}__TMP__ ($lisctColumsProject)";
        $select = "SELECT $lisctColumsProject FROM {$this->nameTable}";

        $query = "
        $insertInto 
        $select";



        if ($fieldsProject['identifierColumn'] !== false){
            if ($this->IDENTITY_INSERT_JOB === true) {
                if ($this->MSSQL == Security::TYPE_dB_MS_SQL){
                    $query = "
                        set identity_insert dbo.{$this->nameTable}__TMP__ ON;
                        
                        $query;
                        
                        set identity_insert dbo.{$this->nameTable}__TMP__ OFF;
                        ";
                }
                if ($this->MSSQL == Security::TYPE_dB_My_SQL){
                    $query = "
                                SET FOREIGN_KEY_CHECKS = 0;
                                SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
                                
                                $query;
                                                                
                                SET SQL_MODE = @@sql_mode;
                                SET FOREIGN_KEY_CHECKS = 1;
                        ";
                }
            }
            $this->IDENTITY_INSERT_JOB = true;
        }

        $conn = new Connect();
        try {
            $conn->complexQuery($query);
        }catch (\PDOException $e){
            mPrint::R($query);
        }
    }

    /**
     * перенос данных из временной в "боевую" таблицу
     * @param $fieldsProject
     * @param $fieldsDataBase
     */
    private function loadDataFromTMpTable($fieldsProject,$fieldsDataBase)
    {
        $fieldsOnlyProject = $this->getOnlyColumnName($fieldsProject['columns']);

        $lisctColumsProject = $this->listColumns($fieldsOnlyProject);
        //$lisctColumsProject

        $insertInto = "INSERT INTO {$this->nameTable} ($lisctColumsProject)";
        $select = "SELECT $lisctColumsProject FROM {$this->nameTable}__TMP__";

        $query = "
        $insertInto 
        $select";

        if ($fieldsProject['identifierColumn'] !== false) {
            if ($this->MSSQL == Security::TYPE_dB_MS_SQL) {
                $query = "
                        set identity_insert dbo.{$this->nameTable} ON;
                        
                        $query;
                        
                        set identity_insert dbo.{$this->nameTable} OFF;
                        ";
            }
            if ($this->MSSQL == Security::TYPE_dB_My_SQL) {
                $query = "
                                SET FOREIGN_KEY_CHECKS = 0;
                                SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
                                
                                $query;
                                                                
                                SET SQL_MODE = @@sql_mode;
                                SET FOREIGN_KEY_CHECKS = 1;
                        ";
            }
        }


        $conn = new Connect();
        $conn->complexQuery($query);
    }


    /**
     * подготовка перечня полей для запроса переноса
     * @param $arrayColumns
     * @return string
     */
    private function listColumns($arrayColumns)
    {
        $list = "";

        foreach ($arrayColumns as $column ){
            if ($this->MSSQL == Security::TYPE_dB_MS_SQL)
                $list .= "[$column],";
            if ($this->MSSQL == Security::TYPE_dB_My_SQL)
                $list .= "$column,";
        }
        $list = substr($list,0,-1);

        return $list;
    }

    /**
     * получение одномерного массива с именами полей полученного из массива свойсв таблицы
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
}