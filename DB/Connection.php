<?php

namespace DB;

use Properties\Security;
use views\mPrint;

/**
 * Class Connection
 * @package backend
 *
 * отвеччает за соединение с БД и реализовывает
 * inset, update, delete, exec
 */
abstract class Connection
{
    public $MSSQL;
    private $typeFetch = \PDO::FETCH_ASSOC;

    private $dbh;

    private $stmt;

    private $table;
    private $orderBy;
    private $groupBy;
    public $lastInsertID;

    public $idSet;
    private $set;

    private $idWhere;
    private $where = array();

    private $arrayConnectionSettings;

    private $join = '';

    function __construct($newConnection = false)
    {

        if ($newConnection === false)
            $this->MSSQL = Security::getTypeDB();
        else{
            $this->MSSQL = $newConnection['MSSQL'];
        }

        $security = new \Properties\Security();
        $this->arrayConnectionSettings = $security->getRight($newConnection);

//        $t_arr = explode('\\', static::class);
        $this->table(self::getName());

    }

    public function setArrayConnectionSettings($arrayConnectionSettings)
    {
        $this->arrayConnectionSettings = $arrayConnectionSettings;
    }
    private function Conn()
    {
        require Security::DIR()."/Properties/error_reporting.php";

        if ($this->MSSQL == Security::TYPE_dB_MS_SQL)
            $this->connPDO();
        if ($this->MSSQL == Security::TYPE_dB_My_SQL)
            $this->connPDO_MySQL();



    }

    private function connPDO_MySQL()
    {
        try {

            $this->dbh = new \PDO("mysql:host=" . $this->arrayConnectionSettings["serverName"] . ";dbname=" . $this->arrayConnectionSettings["dataBase"] . ";charset=utf8",
                $this->arrayConnectionSettings["userName"], $this->arrayConnectionSettings["password"],
                array("charset" => "UTF-8"));
            $this->dbh->setAttribute(\PDO::SQLSRV_ATTR_QUERY_TIMEOUT, 300); // timeout in seconds
            $this->dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->dbh->setAttribute(\PDO::SQLSRV_ATTR_ENCODING, \PDO::SQLSRV_ENCODING_UTF8);
            //$this->dbh->setAttribute(\PDO::ATTR_PERSISTENT, true); // try to enable this value, but the timeout should suffice
            //  => 300,
            // PDO::ATTR_PERSISTENT => true,
        } catch (\PDOException $e) {
            if (Security::DEVELOPMENT){
                print $e->getMessage();
                exit;
            }
        }
    }

    private function connPDO()
    {

        try {
            $this->dbh = new \PDO("sqlsrv:Server=" . $this->arrayConnectionSettings["serverName"] . ";Database=" . $this->arrayConnectionSettings["dataBase"] . ";Encrypt=0;TrustServerCertificate=1"
                , $this->arrayConnectionSettings["userName"], $this->arrayConnectionSettings["password"]);
            $this->dbh->setAttribute(\PDO::SQLSRV_ATTR_QUERY_TIMEOUT, 300); // timeout in seconds
            $this->dbh->setAttribute(\PDO::SQLSRV_ATTR_ENCODING, \PDO::SQLSRV_ENCODING_UTF8);
            //$this->dbh->setAttribute(\PDO::ATTR_PERSISTENT, true); // try to enable this value, but the timeout should suffice
        } catch (\PDOException $e) {
            if (Security::DEVELOPMENT){
                print $e->getMessage();
                exit;
            }
        }
    }

    /*
    *******************************************
    *******************************************
    *******************************************
    */
    public function table($nameTable)
    {
        $this->table = $nameTable;
        $this->init();
        return $this;
    }


    /**
     * @return mixed
     */
    static function getName()
    {
        $t_arr = explode('\\', static::class);
        return array_pop($t_arr);
    }


    public function init()
    {
        $this->join = '';
        $this->idSet = -1;
        $this->set = array();
        $this->idWhere = -1;
        $this->where = array();
        $this->orderBy = false;
        $this->groupBy = false;
    }



    public function INNER_JOIN($nameTable)
    {
        $this->join .= " INNER JOIN $nameTable ON";
        return $this;
    }

    public function LEFT_OUTER_JOIN($nameTable)
    {
        $this->join .= " LEFT OUTER JOIN $nameTable ON";
        return $this;
    }

    public function FULL_OUTER_JOIN($nameTable)
    {
        $this->join .= " FULL OUTER JOIN $nameTable ON";
        return $this;
    }

    public function RIGHT_OUTER_JOIN($nameTable)
    {
        $this->join .= " RIGHT OUTER JOIN $nameTable ON";
        return $this;
    }


    public function WHERE_JOIN($param1,$znak ,$param2, $logical = '')
    {
        $this->join .= " $param1 $znak $param2 ";
        return $this;
    }



    public function where($var, $data, $znak = "=", $logical = 'and')
    {
        $this->idWhere++;
        $this->where[$this->idWhere]['variable'] = $var;
        $this->where[$this->idWhere]['data'] = $data;
        $this->where[$this->idWhere]['znak'] = $znak;
        $this->where[$this->idWhere]['logical'] = $logical;
        return $this;
    }

    public function set($var, $data, $binary = false)
    {
        $this->idSet++;
        $this->set[$this->idSet]['variable'] = $var;
        $this->set[$this->idSet]['data'] = $data;
        $this->set[$this->idSet]['binary'] = $binary;

        return $this;
    }

    /**
     * @param $orderBy string поля которые будут участвовать вв сортировке
     * @return $this
     */
    public function orderBy($orderBy)
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    /**
     * @param int $typeFetch
     */
    public function setTypeFetch(int $typeFetch)
    {
        $this->typeFetch = $typeFetch;
    }

    public function fetch($goTop = 1)
    {
        if ($goTop == 0) {
            $ret = $this->stmt->fetch($this->typeFetch, \PDO::FETCH_ORI_FIRST);
        } else {
            $ret = $this->stmt->fetch($this->typeFetch);
        }
        if ($ret) {
            return $ret;
            //return $this->IconvArray($ret);
        } else return false;

    }

    public function fetchField($filed)
    {
        if (isset($filed)) {
            if ($res = $this->fetch()) {
                return $res[$filed];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function fetchAll()
    {
        return $this->stmt->fetchAll($this->typeFetch);
    }

    public function groupBy($groupBy)
    {
        $this->groupBy = $groupBy;
        return $this;
    }

    /*
     * ***********************************************************************************
     * ***********************************************************************************
     * ***********************************************************************************
     */
    public function complexSelect()
    {
        $this->Conn();

        $query = $this->table;
        $this->stmt = $this->dbh->prepare($query);

        if ($this->idWhere >= 0) {
            for ($i = 0; $i <= $this->idWhere; $i++) {
                $param = ':' . $this->where[$i]['variable'];
                $data = $this->where[$i]['data'];
                $this->stmt->bindValue($param, $data);

            }
        }
        $this->stmt->execute();
        $this->lastInsertID = $this->dbh->lastInsertId();
        return $this;
    }


    public function getQuerySelect(Array $columns)
    {
        $this->Conn();
        $fileds = '';
        foreach ($columns as $column){
            $fileds .= $column.",";
        }
        $fileds = substr($fileds,0,-1);

        $textWhere = "";
        if ($this->idWhere >= 0) {
            $textWhere = " where  ";
            for ($i = 0; $i <= $this->idWhere; $i++) {
                $_if = $this->where[$i]['data'] != Null ? " :var" . $i : 'Null';
                $_if = $this->where[$i]['znak'] == 'IN' ? "( {$this->where[$i]['data']} )" : $_if;
                $logical = "";
                if ($i + 1 <= $this->idWhere) {
                    $logical = " {$this->where[$i]['logical']} ";
                }

                $textWhere0 = "(" . $this->where[$i]['variable'] . " " . $this->where[$i]['znak'] . $_if . ")" . $logical;
                $textWhere = $textWhere . $textWhere0;
            }

        }
        $orderBy = $this->orderBy ? " ORDER BY " . $this->orderBy : "";
        $groupBy = $this->groupBy ? " GROUP BY " . $this->groupBy : "";

        $query = "select  " . $fileds . " from " . $this->table." ". $this->join . " " . $textWhere . " " . $groupBy . " " . $orderBy;

        return $query;
    }


    public function select($fileds = " * ")
    {
        $query = $this->getQuerySelect([$fileds]);
        $this->stmt = $this->dbh->prepare($query);
        if ($this->idWhere >= 0) {
            for ($i = 0; $i <= $this->idWhere; $i++) {
                $param = ':var' . $i;
                $data = $this->where[$i]['data'];
                if ( $this->where[$i]['znak'] == 'IN')
                    $data = null;

                if ($data != Null) {
                    $this->stmt->bindValue($param, $data);
                }

            }
        }

        $this->init();
        $this->stmt->execute();

        return $this;
    }

    public function delete()
    {
        $this->Conn();

        $textWhere = "";
        if ($this->idWhere >= 0) {
            $textWhere = " where  ";
            for ($i = 0; $i <= $this->idWhere; $i++) {
                $textWhere = $textWhere . "(" . $this->where[$i]['variable'] . " " . $this->where[$i]['znak'] . " :var" . $i . ")" . ($i + 1 > $this->idWhere ? "" : " and ");
            }

        }
        if ($this->MSSQL == Security::TYPE_dB_MS_SQL)
            $commandDelete = "delete ";
        if ($this->MSSQL == Security::TYPE_dB_My_SQL)
            $commandDelete = "delete from ";

        $query = $commandDelete . $this->table . " " . $textWhere;
        $this->stmt = $this->dbh->prepare($query);

        if ($this->idWhere >= 0) {
            for ($i = 0; $i <= $this->idWhere; $i++) {
                $param = ':var' . $i;
                $data = $this->where[$i]['data'];
                $this->stmt->bindValue($param, $data);
            }
        }
        $this->init();
        $this->stmt->execute();

    }

    public function complexQuery($query)
    {
        $this->Conn();

        //$query=iconv('UTF-8','windows-1251', $query);
        $this->stmt = $this->dbh->prepare("$query"); //
        $this->init();
        $this->stmt->execute();
        return $this;
    }

    public function update()
    {
        $this->Conn();

        $textWhere = "";
        if ($this->idWhere >= 0) {
            $textWhere = " where  ";
            for ($i = 0; $i <= $this->idWhere; $i++) {
                $textWhere = $textWhere . "(" . $this->where[$i]['variable'] . " " . $this->where[$i]['znak'] . " :" . $this->where[$i]['variable'] . "_w )" . ($i + 1 > $this->idWhere ? "" : " and ");
            }

        }
        $textSET = "";
        if ($this->idSet >= 0) {
            $textSET = " set  ";
            for ($i = 0; $i <= $this->idSet; $i++) {
                $textSET = $textSET . $this->set[$i]['variable'] . " =   :" . $this->set[$i]['variable'] . " " . ($i + 1 > $this->idSet ? "" : " , ");
            }

        }


        $query = "update " . $this->table . " " . $textSET . " " . $textWhere;


        $this->stmt = $this->dbh->prepare("$query");
        for ($i = 0; $i <= $this->idSet; $i++) {
            if ($this->set[$i]['binary'])
                $this->stmt->bindParam(':' . $this->set[$i]['variable'], $this->set[$i]['data'], \PDO::PARAM_LOB, 0, \PDO::SQLSRV_ENCODING_BINARY);
            else
                $this->stmt->bindParam(':' . $this->set[$i]['variable'], $this->set[$i]['data']);

        }
        for ($i = 0; $i <= $this->idWhere; $i++) {
            $this->stmt->bindParam(':' . $this->where[$i]['variable'] . "_w", $this->where[$i]['data']);
        }

        $this->init();
        $res = $this->stmt->execute();
        unset($stmt);
        return $res;

    }
    public function complexUpdate()
    {
        $this->Conn();

        $this->stmt = $this->dbh->prepare($this->table);
        for ($i = 0; $i <= $this->idSet; $i++) {
            if ($this->set[$i]['binary'])
                $this->stmt->bindParam(':' . $this->set[$i]['variable'], $this->set[$i]['data'], \PDO::PARAM_LOB, 0, \PDO::SQLSRV_ENCODING_BINARY);
            else
                $this->stmt->bindParam(':' . $this->set[$i]['variable'], $this->set[$i]['data']);

        }
        for ($i = 0; $i <= $this->idWhere; $i++) {
            $this->stmt->bindParam(':' . $this->where[$i]['variable'] . "_w", $this->where[$i]['data']);
        }

        $this->init();
        $res = $this->stmt->execute();
        unset($stmt);
        return $res;

    }

    public function insert()
    {
        $this->Conn();

        $textVar = "";
        $textData = "";
        $d1 = '';
        $d2 = '';
        if ($this->MSSQL == Security::TYPE_dB_MS_SQL){
            $d1 = '[';
            $d2 = ']';
        }
        if ($this->idSet >= 0) {
            for ($i = 0; $i <= $this->idSet; $i++) {
                $textVar = $textVar . $d1.$this->set[$i]['variable'].$d2 . " " . ($i + 1 > $this->idSet ? "" : " , ");
                $textData = $textData . ":" . $this->set[$i]['variable'] . " " . ($i + 1 > $this->idSet ? "" : " , ");
            }

        }
        $query = "INSERT INTO " . $this->table . " (" . $textVar . ") values(" . $textData . ");";


        $stmt = $this->dbh->prepare("$query");
        for ($i = 0; $i <= $this->idSet; $i++) {
            if ($this->set[$i]['binary'])
                $stmt->bindParam(':' . $this->set[$i]['variable'], $this->set[$i]['data'], \PDO::PARAM_LOB, 0, \PDO::SQLSRV_ENCODING_BINARY);
            else
                $stmt->bindParam(':' . $this->set[$i]['variable'], $this->set[$i]['data']);
        }

        $this->init();
        $stmt->execute();


        unset($stmt);

        $ret = $this->dbh->lastInsertId();
        return $ret;
    }


    public function SQLExec()
    {
        $this->Conn();

        $query = 'exec ' . $this->table . ' ';

        for ($i = 0; $i <= $this->idSet; $i++) {
            $query = $query . " :" . $this->set[$i]['variable'] . " " . ($i + 1 > $this->idSet ? "" : " , ");
        }

        $this->stmt = $this->dbh->prepare($query);
        for ($i = 0; $i <= $this->idSet; $i++) {
            $this->stmt->bindParam(':' . $this->set[$i]['variable'], $this->set[$i]['data']);

        }

        $this->lastInsertID = $this->dbh->lastInsertId();
        $this->init();
        $this->stmt->execute();

        return $this;
    }

    public function SQLExec_MySQL()
    {
        $this->Conn();

        $query = 'call ' . $this->table . '( ';

        for ($i = 0; $i <= $this->idSet; $i++) {
            $query = $query . " :" . $this->set[$i]['variable'] . " " . ($i + 1 > $this->idSet ? "" : " , ");
        }
        $query = $query . ' )';
        $this->stmt = $this->dbh->prepare($query);
        for ($i = 0; $i <= $this->idSet; $i++) {
            $this->stmt->bindParam(':' . $this->set[$i]['variable'], $this->set[$i]['data']);

        }

        $this->lastInsertID = $this->dbh->lastInsertId();
        $this->init();
        $this->stmt->execute();

        return $this;
    }
}