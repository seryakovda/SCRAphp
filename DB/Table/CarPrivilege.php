<?php

namespace DB\Table;

use \DB\Type;

class CarPrivilege extends \DB\Table
{
    /**
     * госномер
     */
    const stateNumber = 'stateNumber';

    const typeNumber = 'typeNumber';

    const del = 'del';

    public function initColumn($childClass = '')
    {
        // обязательно для формирования структуры массива
        parent::initColumn(__CLASS__);


        $this->declare_primaryIndex($this::stateNumber);


        $this->declare_type($this::stateNumber, Type::varchar,10);
        $this->declare_defaultValue($this::stateNumber,'');

        $this->declare_type($this::typeNumber, Type::int);
        $this->declare_defaultValue($this::typeNumber,'0');

        $this->declare_type($this::del,        Type::int);
        $this->declare_defaultValue($this::del,'0');

    }

}
