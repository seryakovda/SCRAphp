<?php

namespace DB\Table;

use \DB\Type;

class Block extends \DB\Table
{
    const id = 'id';

    const value =  'value';

    public function initColumn($childClass = '')
    {
        // обязательно для формирования структуры массива
        parent::initColumn(__CLASS__);

        $this->identifierColumn($this::id);
        $this->declare_primaryIndex($this::id);


        $this->declare_type($this::id,        Type::int);
        $this->declare_type($this::value,     Type::int);

    }

}
