<?php

namespace DB\Table;

use \DB\Type;

class BatMonArduinoSettings extends \DB\Table
{
    const deviceId = 'deviceId';

    const name = 'name';

    const address = 'address';


    public function initColumn($childClass = '')
    {
        // обязательно для формирования структуры массива
        parent::initColumn(__CLASS__);

        $this->declare_primaryIndex($this::deviceId);


        $this->declare_type($this::deviceId,Type::varchar,36);
        $this->declare_type($this::name,Type::varchar,6);
        $this->declare_type($this::address,Type::varchar,200);

    }

}
