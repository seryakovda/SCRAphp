<?php

namespace DB\Table;

use \DB\Type;

class PassFields extends \DB\Table
{
    const id_typePass = 'id_typePass';

    const sort  ='sort';

    const id = 'id';

    const name =  'name';

    const id_field = 'id_field';

    const linkObject = 'linkObject';

    const filterField = 'filterField';

    const defaultValue = 'defaultValue';

    const del = 'del';

    public function initColumn($childClass = '')
    {
        // обязательно для формирования структуры массива
        parent::initColumn(__CLASS__);

        $this->declare_primaryIndex($this::id_typePass);
        $this->declare_primaryIndex($this::sort);
        $this->declare_primaryIndex($this::id);


        $this->declare_type($this::id_typePass,         Type::int);
        $this->declare_type($this::id,                  Type::int);
        $this->declare_type($this::id_field,            Type::int);
        $this->declare_type($this::filterField,         Type::varchar,50);
        $this->declare_type($this::defaultValue,        Type::varchar,200);

        $this->declare_defaultValue($this::sort,0);
        $this->declare_defaultValue($this::linkObject,'');
        $this->declare_defaultValue($this::filterField,'');

        $this->declare_type($this::del,        Type::int);
        $this->declare_defaultValue($this::del,'0');

    }
}
