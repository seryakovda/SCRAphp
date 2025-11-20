<?php

namespace DB\Table;

use \DB\Type;

class Car extends \DB\Table
{
    const id = 'id';

    /**
     * марка авто
     */
    const brand =  'brand';

    /**
     * госномер
     */
    const stateNumber = 'stateNumber';

    const del = 'del';

    public function initColumn($childClass = '')
    {
        // обязательно для формирования структуры массива
        parent::initColumn(__CLASS__);

        $this->identifierColumn($this::id);
        $this->declare_primaryIndex($this::id);


        $this->declare_type($this::id,        Type::int);
        $this->declare_defaultValue($this::brand,'');
        $this->declare_defaultValue($this::stateNumber,'');

        $this->declare_type($this::del,        Type::int);
        $this->declare_defaultValue($this::del,'0');

    }

}
