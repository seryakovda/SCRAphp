<?php


namespace forms\inputEditVariable;


class filterGAR_MODEL
{
    private $data,$idInput,$val;
    public function __construct()
    {
        $this->data = $_REQUEST['data'];
        $this->idInput = $_REQUEST['idInput'];
        $this->val = $_REQUEST['val']; //принимает значение val в случае текстового запроса и PATH в случае выбранного объекта
    }

    /**
     * @return mixed
     */
    public function getIdInput(): mixed
    {
        return $this->idInput;
    }

    /**
     * @return mixed
     */
    public function getVal(): mixed
    {
        return $this->val;
    }


    public function getData()
    {
        $idInput = $this->idInput;
        return $this->$idInput();
    }


    private function area()
    {
        $conn = new \DB\Connect();
        $query = " select TOP (10) * from View_FIAS_area where NAME LIKE '$this->val%'";
        return $conn->complexQuery($query)
            ->fetchAll();
    }


    private function district()
    {
        $conn = new \DB\Connect();
        $query = " select TOP (10) * from View_FIAS_district where NAME LIKE '$this->val%' ";
        $query .=$this->addWhere();
        return $conn->complexQuery($query)
            ->fetchAll();
    }


    private function town()
    {
        $conn = new \DB\Connect();
        $query = " select TOP (10) * from View_FIAS_town where NAME LIKE '$this->val%'";
        $query .=$this->addWhere();
        return $conn->complexQuery($query)
            ->fetchAll();
    }


    private function street()
    {
        $conn = new \DB\Connect();
        $query = " select TOP (10) * from View_FIAS_street where NAME LIKE '$this->val%'";
        $query .=$this->addWhere();
        return $conn->complexQuery($query)
            ->fetchAll();
    }

    private function house()
    {
        $conn = new \DB\Connect();
        $PARENTOBJID = $this->data['street']['OBJECTID'];
        $query = " select TOP (10) * from View_FIAS_house where NAME LIKE '$this->val%' AND PARENTOBJID = $PARENTOBJID";
        return $conn->complexQuery($query)
            ->fetchAll();
    }


    private function room()
    {
        $conn = new \DB\Connect();
        $PARENTOBJID = $this->data['house']['OBJECTID'];
        $query = " select TOP (10) * from View_FIAS_room where NAME LIKE '$this->val%' AND PARENTOBJID = $PARENTOBJID";
        return $conn->complexQuery($query)
            ->fetchAll();
    }


    private function addWhere()
    {
        $query = '';
        $PATH = '';
        foreach ($this->data as $key => $item){
            $OBJECTID = $item['OBJECTID'] == 'false' ? false : $item['OBJECTID'];
            if ($OBJECTID !== false) {
                $PATH = $PATH . $OBJECTID . ".";
            }
        }
        if (strlen($PATH) != 0){
            $query = " and PATH like '$PATH%' ";
        }
        return $query;
    }

    public function getFullArray()
    {
        $PATH = $this->val;
        $PATH_arr = explode('.',$PATH);
        //$item_DATA = $this->data[$this->idInput];
        $where  = "WHERE (_ADDR_OBJ.OBJECTID IN (";
        foreach ($PATH_arr as $key => $value){
            $where .= $value.",";
        }
        $where = substr($where,0,-1)."))";
        $query = "SELECT        _ADDR_OBJ.OBJECTID, _ADDR_OBJ.NAME, _ADDR_OBJ.TYPENAME, _INPUTS.idInput
                    FROM            _ADDR_OBJ INNER JOIN
                         _INPUTS ON _ADDR_OBJ.LEVEL_ = _INPUTS.id_LEVEL
                    $where
                    order by LEVEL_";
        $conn = new \DB\Connect();

        $data_obj = $conn ->complexQuery($query);
        while($res = $data_obj->fetch()){
            $data[$res['OBJECTID']] = $res;
        }

        foreach ($PATH_arr as $key => $OBJECTID){
            $idInput = $data[$OBJECTID]['idInput'];
            $this->data[$idInput]['OBJECTID'] = $OBJECTID;
            $this->data[$idInput]['val'] = $data[$OBJECTID]['NAME'];
            $this->data[$idInput]['TYPENAME'] = $data[$OBJECTID]['TYPENAME'];
        }
        return $this->data;
    }
}