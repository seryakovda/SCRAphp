<?php

namespace DB\Table;

use \DB\Type;

class pMark_TMP extends \DB\Table
{
    const uid = 'uid';

    const ID = 'ID';
    const Gtype = 'Gtype';
    const Config = 'Config';
    const CodeP_HEX = 'CodeP_HEX';
    const Status = 'Status';
    const Owner = 'Owner';
    const GroupID = 'GroupID';
    const Start = 'Start';
    const Finish = 'Finish';


    public function initColumn($childClass = '')
    {
        // обязательно для формирования структуры массива
        parent::initColumn(__CLASS__);

        $this->identifierColumn($this::uid);
        $this->declare_primaryIndex($this::uid);


        $this->declare_type($this::uid,       Type::int);

        $this->declare_type($this::ID,          Type::int);
        $this->declare_type($this::Gtype,       Type::int);
        $this->declare_type($this::Config,      Type::int);
        $this->declare_type($this::CodeP_HEX,   Type::varchar,20);
        $this->declare_type($this::Status,      Type::int);
        $this->declare_type($this::Owner,       Type::int);
        $this->declare_type($this::GroupID,     Type::int);
        $this->declare_type($this::Start,       Type::datetime);
        $this->declare_type($this::Finish,      Type::datetime);
    }

}