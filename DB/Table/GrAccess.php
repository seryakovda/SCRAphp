<?php

namespace DB\Table;

use \DB\Type;

class GrAccess extends \DB\Table
{
    const Uid = 'Uid';

    const ID = 'ID';
    const GroupID = 'GroupID';
    const Mode = 'Mode';
    const AccessID = 'AccessID';
    const Config = 'Config';

    public function initColumn($childClass = '')
    {
        // обязательно для формирования структуры массива
        parent::initColumn(__CLASS__);

        $this->identifierColumn($this::Uid);
        $this->declare_primaryIndex($this::Uid);


        $this->declare_type($this::Uid,         Type::int);
        $this->declare_type($this::ID,          Type::int);
        $this->declare_type($this::GroupID,     Type::int);
        $this->declare_type($this::Mode,        Type::int);
        $this->declare_type($this::AccessID,    Type::int);
        $this->declare_type($this::Config,      Type::int);

    }

}
