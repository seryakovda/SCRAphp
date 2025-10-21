<?php


namespace DB;



use Properties\Security;
use views\mPrint;

class Table extends Connection
{
    private $declareVariable = Array();
    private $TMpNameTable = '';

    public $nameTable_ORION = '';

    public $deleteDataFor_defaultRows = true;
    private $childClass;
    /**
     * Создание новой таблицы не существующей в БД
     */
    public function create()
    {
        $this->initColumn();
        $query = $this->createQuery();
        try {
            $this->complexQuery($query);
        }catch (\PDOException $e) {
            mPrint::PDO($e,$query);
            exit;
        }


        $indexes = $this->createNoClusteredIndexes();
        foreach ($indexes as $query){
            $this->complexQuery($query);
        }

        $this->TMpNameTable = '';
    }



    public function setTMpNameTable($TMpNameTable)
    {
        $this->TMpNameTable = $TMpNameTable;
    }

    /**
     * базовая инициализация массива описания таблицы
     * @param string $childClass
     */
    public function initColumn($childClass = '')
    {
        $this->childClass = $childClass;
        $this->declareVariable = Array(
            'identifierColumn' => false,
            'columns' => Array(),
            'primaryIndex' => Array(),
            'nonclusteredIndex' => Array(),
        );

        $this->setDefaultType($this->getConstants());
    }


    /**
     * назначает всем константам значение по умолчанию
     * @param $arrayAllColumns
     */
    private function setDefaultType($arrayAllColumns)
    {
        foreach ($arrayAllColumns as $column){
            $this->declare_type($column,Type::nvarchar,50);
        }
    }



    /**
     * получает список всех констант(полей БД) в дочернем классе
     * @return array
     */
    private function getConstants() {
        $oClass = new \ReflectionClass($this->childClass);
        return $oClass->getConstants();
    }




    /**
     * @param string $variable
     * @param string $type
     * @param string|bool $size
     */
    public function declare_type(string $variable,string $type,string|bool  $size = false)
    {
        $this->declareVariable['columns'][$variable]['type'] = $type;
        $size = \DB\Type::getSizeFalseForType($type,$size);
        $this->declareVariable['columns'][$variable]['size'] = $size;
    }


    /**
     * @param string $variable
     * @param string|null $value
     */
    public function declare_defaultValue(string $variable,string $value = null)
    {
        $this->declareVariable['columns'][$variable]['defaultValue'] = $value;
    }


    /**
     * @param string $variable
     * @param string $sort прямая сортировка по умолчанию (ASC) обратьная должно быть значение DESC
     */
    public function declare_primaryIndex(string $variable,string $sort = 'ASC')
    {
        $this->declareVariable['primaryIndex'][$variable] = $sort;
    }


    /**
     * @param string $nameIndex
     * @param string $variable
     * @param string $sort
     */
    public function declare_nonclusteredIndex(string $nameIndex,string $variable,string $sort = 'ASC')
    {
        $this->declareVariable['nonclusteredIndex'][$nameIndex][$variable] = $sort;
    }



    /**
     * @param string $variable
     */
    public function identifierColumn(string $variable)
    {
        $this->declareVariable['identifierColumn'] = $variable;
    }



    /**
     * @return array
     */
    public function getDeclareVariable(): array
    {
        return $this->declareVariable;
    }



    private function createQuery()
    {
        $table = $this->getName().$this->TMpNameTable;
        $query = '';

        foreach ($this->declareVariable['columns'] as $column => $properties){

            $type = $properties['type'];
            $type .= $properties['size'] !== false ? "( {$properties['size']} )" : "";

            // блок определения значенеи поля по умолчанию

            $defaultValue = 'NULL';

            // Если поле попадает в кластеризованный индекс оно автоматом становитья не нуливое
            if (array_key_exists($column,$this->declareVariable['primaryIndex'])){
                $defaultValue = 'NOT NULL';
            }

            if (array_key_exists('defaultValue',$properties))
                switch ($properties['type']){
                    case 'int':
                    case 'money':
                        $defaultValue = "default ({$properties['defaultValue']})";
                        break;
                    default:{
                        $l = "'"; // по умолчанию дефолтное значение берётся в одинарные кавычки
                        if ( // но если присутствует скобочка, то дефолтное значение возвращает какаято функция
                            (str_contains($properties['defaultValue'],'(')) ||
                            (str_contains($properties['defaultValue'],')'))

                        ){
                            $l = ''; // в этой связи дефолтное значение не берётся в одинарные кавычки
                        }
                        if ($properties['defaultValue'] == 'CURRENT_TIMESTAMP') // MySQL
                            $defaultValue = "default CURRENT_TIMESTAMP ";
                        else
                            $defaultValue = "default ($l{$properties['defaultValue']}$l)";
                    }

                }

            // в случае если столбец имеет автоинкремент то значение по умочанию замещается инструкцией
            if (array_key_exists('identifierColumn',$this->declareVariable)){
                if ($this->declareVariable['identifierColumn'] == $column){
                    if ($this->MSSQL == Security::TYPE_dB_MS_SQL)
                        $defaultValue = 'IDENTITY(1,1) NOT NULL';
                    if ($this->MSSQL == Security::TYPE_dB_My_SQL)
                        $defaultValue = 'NOT NULL AUTO_INCREMENT';
                }
            }

            // построение кластеризоыванного индекса
            $B1 = '';
            $B2 = '';
            if ($this->MSSQL == Security::TYPE_dB_MS_SQL){
                $B1 = '[';
                $B2 = ']';

            }

            $query .= " $B1$column$B2 $type $defaultValue,";
        }
        $query = substr($query,0,-1);


        if ($this->MSSQL == Security::TYPE_dB_MS_SQL)
            $clusteredIndex = $this->createClusteredIndex();
        if ($this->MSSQL == Security::TYPE_dB_My_SQL)
            $clusteredIndex = $this->createClusteredIndex_mySQL();

        if ($this->MSSQL == Security::TYPE_dB_MS_SQL)
            $query = "CREATE TABLE dbo.$table ( $query  $clusteredIndex )";
        if ($this->MSSQL == Security::TYPE_dB_My_SQL)
            $query = "CREATE TABLE $table ( $query, $clusteredIndex )  ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ";

        return $query;
    }


    public function createClusteredIndex()
    {
        $query  = '';
        $table = $this->getName().$this->TMpNameTable;
        if (array_key_exists('primaryIndex',$this->declareVariable)){
            foreach ($this->declareVariable['primaryIndex'] as $column => $sort) {
                $query .= "[$column] $sort,";
            }
            $query = substr($query,0,-1);

            $query = ", CONSTRAINT [{$table}_PK_{$table}] PRIMARY KEY CLUSTERED (	$query )WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]";
        }
        return $query;
    }

    public function createClusteredIndex_mySQL()
    {
        $query  = '';
        $table = $this->getName().$this->TMpNameTable;
        if (array_key_exists('primaryIndex',$this->declareVariable)){
            foreach ($this->declareVariable['primaryIndex'] as $column => $sort) {
                $query .= "$column $sort,";
            }
            $query = substr($query,0,-1);

            $query = "PRIMARY KEY ( $query )";
        }
        return $query;
    }
    /**
     * @return array
     */
    private function createNoClusteredIndexes()
    {
        $query = Array();
        if (array_key_exists('nonclusteredIndex',$this->declareVariable)) {
            foreach ($this->declareVariable['nonclusteredIndex'] as $nameIndex => $fields) {
                $query[] = $this->createNoClusteredIndex($nameIndex, $fields);
            }
        }
        return $query;
    }



    /**
     *  формирование запроса по создапнию индекса
     * @param string $nameIndex имя индекса
     * @param array $fields Список полей индекса и тип сортировки полей
     * @return string SQL запрос
     */
    public function createNoClusteredIndex(string $nameIndex, Array $fields)
    {
        $query = '';
        $table = $this->getName().$this->TMpNameTable;
        foreach ($fields as $column => $sort) {
            if ($this->MSSQL == Security::TYPE_dB_MS_SQL)
                $query .= "[$column] $sort,";

            if ($this->MSSQL == Security::TYPE_dB_My_SQL)
                $query .= "$column $sort,";
        }
        $query = substr($query,0,-1);

        if ($this->MSSQL == Security::TYPE_dB_MS_SQL)
            $query = "CREATE NONCLUSTERED INDEX [{$table}_IX_$nameIndex] ON [dbo].$table ( $query )WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]";

        if ($this->MSSQL == Security::TYPE_dB_My_SQL)
            $query = "CREATE INDEX {$table}_IX_$nameIndex ON $table ( $query )";

        return $query;
    }

    /**
     * формирование запроса по удалению индекса
     * @param string $nameIndex имя индекса
     * @return string SQL запрос
     */
    public function DROP_NoClusteredIndex(string $nameIndex)
    {
        $table = $this->getName();
        if ($this->MSSQL == Security::TYPE_dB_MS_SQL)
            $query = " DROP INDEX  IF EXISTS  {$table}_IX_$nameIndex ON [dbo].$table";
        if ($this->MSSQL == Security::TYPE_dB_My_SQL)
            $query = /** @lang SQL */ "
            SET @index_exists = (SELECT COUNT(*) FROM information_schema.statistics 
                     WHERE table_schema = DATABASE() 
                     AND table_name = '$table' 
                     AND index_name = '{$table}_IX_$nameIndex');
            SET @sql = IF(@index_exists > 0, 
                'DROP INDEX {$table}_IX_$nameIndex ON $table', 
                'SELECT ''Index does not exist''');
            PREPARE stmt FROM @sql;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt; 
            ";

        return $query;
    }

    /**
     * Должна возвращать массив со строками по умолчанию, либо false
      Array(
            Array(
                'id' => 1,
                'name' => 'Красный'
            )
      )
     * @return mixed
     */
    public function defaultRows()
    {
        return false;
    }

    public function reRecordDefaultRows()
    {
        $rows = $this->defaultRows();
        if ($this->deleteDataFor_defaultRows){
            $this->table(self::getName());
            $this->delete();
        }
        foreach ($rows as $key => $row){
            $this->init();
            foreach ($row as $field => $value){
                $this->set($field , $value);
            }
            try{
                $this->insert();
            }catch (\PDOException $e){

            }

        }
    }


    /**
     * Должна возвращать массив со строками Содержащими триггеры для таблицы, либо false
    Array(
        Array(
            'name' => 'nameTrigger',
            'body' => 'SQL'
        )
    )
     * @return mixed
     */
    public function triggers(): mixed
    {
        return false;
    }

    /*
     CREATE TRIGGER dbo.[TRg_DSSL_EventNumberCamera_insertNumber]
       ON  dbo.[DSSL_EventNumberCamera]
       AFTER  INSERT
    AS
    BEGIN
        -- SET NOCOUNT ON added to prevent extra result sets from
        -- interfering with SELECT statements.
        SET NOCOUNT ON;
        insert into DSSL_TRIGGER_Event (ipCamera)
        select isnull(ipCamera,0) from inserted
    END
    GO

     */
    public function reCreateTriggers()
    {
        $nameTable = self::getName();
        $triggers = $this->triggers();
        foreach($triggers as $key => $trigger){
            $nameTrigger = $trigger['name'];
            $after = $trigger['after'];
            $body = $trigger['body'];

            if ($this->MSSQL == Security::TYPE_dB_MS_SQL){
                $query = /** @lang  SQL */"
                IF EXISTS (SELECT * FROM sys.triggers WHERE name = '{$nameTable}_$nameTrigger' AND parent_id = OBJECT_ID('dbo.[$nameTable]'))
                BEGIN
                    EXEC('ALTER TRIGGER dbo.[{$nameTable}_$nameTrigger] ON dbo.[$nameTable] $after AS BEGIN $body END');
                END
                ELSE
                BEGIN
                    EXEC('CREATE TRIGGER dbo.[{$nameTable}_$nameTrigger] ON dbo.[$nameTable] $after AS BEGIN $body END');
                END
                ";
                mPrint::R($query,mPrint::PINK);
                $this->complexQuery($query);
            }
            if ($this->MSSQL == Security::TYPE_dB_My_SQL){
                $query = /** @lang  SQL */"DROP TRIGGER IF EXISTS {$nameTable}_$nameTrigger;";
                mPrint::R($query,mPrint::PINK);
                $this->complexQuery($query);

                $query = /** @lang  SQL */"
                CREATE TRIGGER {$nameTable}_$nameTrigger
                $after ON $nameTable
                FOR EACH ROW
                BEGIN
                    -- В MySQL используем NEW для доступа к новым данным
                    $body
                END";
                mPrint::R($query,mPrint::PINK);
                $this->complexQuery($query);

            }

        }

    }

    public function updateDataForOtherServers($id)
    {
        if ($this->nameTable_ORION != ''){
            $nameTable_ORION = $this->nameTable_ORION;


            $s = new Security();
            $serv = $s->getOrionServerName();
            $dbO = $s->getOrionDataBase();
            $conn = new \DB\Connect();

            $this->init();
            $dataArr = $this->where($this::id,$id)
                ->select()->fetch();
            $name = $dataArr[$this::name]; // получаем значение справочника
            $id_Orion = $dataArr[$this::id_Orion]; // и значение в орионе


            if ($id_Orion == 0){ // если не зафиксировно значение из Орион

                // проверяем не имеется ли в базе орион одноименное название
                $query = "select ID from $serv$dbO.dbo.$nameTable_ORION where name = '$name'";
                $id_Orion = $conn->complexQuery($query)->fetchField("ID");

                // если одноименного названия не нашлось
                if ($id_Orion === false){
                    $query = "select max(ID)+1 as max_id from $serv$dbO.dbo.$nameTable_ORION ";
                    $id_Orion = $conn->complexQuery($query)->fetchField("max_id");
                    // Добавляем новуюзапись
                    $query = "insert into $serv$dbO.dbo.$nameTable_ORION (ID, Name) values ($id_Orion,'$name')";
                    $conn->complexQuery($query);
                }

            }

            $this->init();
            $this->set($this::id_Orion,$id_Orion)
                ->where($this::id,$id)
                ->update();

        }
    }

}
