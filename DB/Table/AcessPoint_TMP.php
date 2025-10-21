<?php

namespace DB\Table;

use \DB\Type;

class AcessPoint_TMP extends \DB\Table
{
    const Uid = 'Uid';

    const ID = 'ID';
    const GIndex = 'GIndex';


    public function initColumn($childClass = '')
    {
        // обязательно для формирования структуры массива
        parent::initColumn(__CLASS__);

        $this->identifierColumn($this::Uid);
        $this->declare_primaryIndex($this::Uid);


        $this->declare_type($this::Uid,         Type::int);
        $this->declare_type($this::ID,          Type::int);
        $this->declare_type($this::GIndex,      Type::int);

    }

}
