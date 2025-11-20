<?php

namespace DB\Table;

use \DB\Type;

class PassStatus extends \DB\Table
{
    const id = 'id';

    const name =  'name';

    const color = 'color';

    public function initColumn($childClass = '')
    {
        // обязательно для формирования структуры массива
        parent::initColumn(__CLASS__);

        $this->declare_primaryIndex($this::id);


        $this->declare_type($this::id,      Type::int);
        $this->declare_type($this::name,    Type::varchar,50);
        $this->declare_type($this::color,   Type::varchar,7);

    }

}
