<?php
/**
 * Created by PhpStorm.
 * User: rezzalbob
 * Date: 06.05.2020
 * Time: 20:52
 */

namespace forms;


use models\_G_session;

abstract class FormsModel
{
    private $ColumnID = "id"; //поле для работы и редактированием данных
    /**
     * @var \DB\Connect
     */
    public $conn;

    public $filter;
    public $filterGlobal = Array();
    public $table;
    public $tableForEdit;
    public $TOP = '(120)';
    public $id_inserted;
    public $data;
    public $OrderString;

    public $variables;

    /**
     * @param mixed $TOP
     */
    public function setTOP($TOP)
    {
        $this->TOP = $TOP;
    }

    public function getData()
    {
        $this->conn = new \DB\Connect();
        $this->prefixFor_getData();
        $this->conn->table($this->table);
        if (is_array($this->filter))
            if (is_array($this->filter))
            if (count($this->filter) > 0) {
            foreach ($this->filter as $field => $rrow) {
                $this->conn->where($rrow['field'], $rrow['value'], $rrow['znak']);
            }
        }
        if (count($this->filterGlobal) > 0) {
            foreach ($this->filterGlobal as $field => $rrow) {
                $this->conn->where($rrow['field'], $rrow['value'], $rrow['znak']);
            }
        }

        if (strlen($this->OrderString) > 0)
            $this->conn->orderBy($this->OrderString);

        $this->postfixFor_getData0();

        $this->data = $this->conn->select(" top $this->TOP *");
        $this->postfixFor_getData();

        return $this->data;
    }
    public function prefixFor_getData(){}
    public function postfixFor_getData0(){}
    public function postfixFor_getData(){}



    public function convertDataToArray($data)
    {
        $returnArray = Array();
        while ($res = $data->fetch()){
            $returnArray[] = $res;
        }
        return $returnArray;
    }


    public function replaceValue($variables)
    {
        $this->variables = $variables;
        $this->replaceValue_prefix();
        $conn = new \DB\Connect();
        $conn->table($this->tableForEdit);
        if (count((array)$this->filter) > 0) {
            foreach ((array)$this->filter as $field => $rrow) {
                $conn->where($rrow['field'], $rrow['value'], $rrow['znak']);
            }
        }
        if (count((array)$this->variables) > 0) {
            foreach ((array)$this->variables as $field => $value) {
                $conn->set($field, $value);
            }
        }
        $this->replaceValue_prefix_conn();
        $conn->update();
        $this->replaceValue_postfix();
    }

    public function replaceValue_prefix_conn(){}
    public function replaceValue_prefix(){}
    public function replaceValue_postfix(){}

    public function deleteRow()
    {

        $this->prefixFor_deleteRow();

        $conn = new \DB\Connect();
        $conn->table($this->tableForEdit);
        if (is_array($this->filter))
            if (count($this->filter) > 0) {
            foreach ($this->filter as $field => $rrow) {
                $conn->where($rrow['field'], $rrow['value'], $rrow['znak']);
            }
        }
        $conn->delete();
    }

    public function prefixFor_deleteRow()
    {

    }



    public function addData($variables)
    {
        $this->conn = new \DB\Connect();
        $this->prefixFor_addData();
        $this->conn->table($this->table);
        if (is_array($this->filter))
            if ((array)count($this->filter) > 0) {
            foreach ($this->filter as $field => $rrow) {
                $this->conn->set($rrow['field'], $rrow['value']);
            }
        }
        if ((array)count($variables) > 0) {
            foreach ($variables as $field => $value) {
                $this->conn->set($field, $value);
            }
        }
        $this->postfixFor_pre_addData();
        $this->id_inserted = $this->conn->insert();
        $this->postfixFor_addData();
        return $this->id_inserted;
    }

    public function prefixFor_addData()
    {
    }
    public function postfixFor_preInsert()
    {
    }
    public function postfixFor_pre_addData()
    {
    }
    public function postfixFor_addData()
    {
    }

    public function addFilterByUser()
    {
        $rrow = Array();
        $rrow['field'] = 'id_user';
        $rrow['value'] = _G_session::id_user();
        $rrow['znak'] = '=';

        $this->filter[] = $rrow;

    }


    public function addFilterByIdNot_0()
    {
        $rrow = Array();
        $rrow['field'] = 'id';
        $rrow['value'] = '0';
        $rrow['znak'] = '<>';

        $this->filter[] = $rrow;
    }


    public function addFilterByRegion()
    {
        $rrow = Array();
        $rrow['field'] = 'id_region';
        $rrow['value'] = $_SESSION['region'];
        $rrow['znak'] = '=';

        $this->filter[] = $rrow;

    }
    public function addFilterBy_IdNoNull()
    {
        $rrow = Array();
        $rrow['field'] = $this->ColumnID;
        $rrow['value'] = '0';
        $rrow['znak'] = '<>';

        $this->filter[] = $rrow;
    }


    /**
     * @param $valArray
     * Array('field'=>Array('prefix'=>'','value'=>'1','sign'=>'=','postfix'=>''))
     */
    public function setFilter($valArray)
    {
        foreach ($valArray as $field => $condition) {
            $prefix = $condition['prefix'];
            $value = $condition['value'];
            $sign = $condition['sign'];
            $postfix = $condition['postfix'];

            $rrow = Array();
            $rrow['field'] = $field;
            $rrow['value'] = $prefix . $value . $postfix;
            $rrow['znak'] = $sign;

            if ($value !== '') $this->filter[] = $rrow;
        }

    }

    public function GUID()
    {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    public function prepareData($classReports,$manageTable)
    {

    }
}