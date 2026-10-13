<?php

namespace DB\Table;

use \DB\Type;

class BatMonArduinoSettings extends \DB\Table
{
    const deviceId = 'deviceId';

    const name = 'name';

    const keyAPI = 'keyAPI';

    const address = 'address';

    const reboot = 'reboot';

    public function initColumn($childClass = '')
    {
        // обязательно для формирования структуры массива
        parent::initColumn(__CLASS__);

        $this->declare_primaryIndex($this::deviceId);


        $this->declare_type($this::deviceId,Type::varchar,36);
        $this->declare_type($this::name,    Type::varchar,6);
        $this->declare_type($this::address, Type::varchar,200);
        $this->declare_type($this::keyAPI,  Type::varchar,36);
        $this->declare_type($this::reboot,  Type::int);
        $this->declare_defaultValue($this::reboot,"0");
    }

}
