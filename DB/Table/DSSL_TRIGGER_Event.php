<?php


namespace DB\Table;

use \DB\Type;

class DSSL_TRIGGER_Event extends \DB\Table
{
    const id = 'id';

    const ipCamera =  'ipCamera';

    public function initColumn($childClass = '')
    {
        // обязательно для формирования структуры массива
        parent::initColumn(__CLASS__);

        $this->declare_primaryIndex($this::id);
        $this->declare_primaryIndex($this::ipCamera);

        $this->declare_type($this::id, Type::int);
        $this->declare_type($this::ipCamera,        Type::varchar,15);
    }

}
