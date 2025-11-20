<?php

namespace DB\Table;

use \DB\Type;

class PassTable extends \DB\Table
{
    const id_head =  'id_head';

    const id_field =  'id_field';

    const id_RowField =  'id_RowField';

    const id = 'id';

    const value = 'value';

    const del = 'del';

    public function initColumn($childClass = '')
    {
        // обязательно для формирования структуры массива
        parent::initColumn(__CLASS__);

//        $this->identifierColumn($this::id);
        $this->declare_primaryIndex($this::id_head);
        $this->declare_primaryIndex($this::id_field);
        $this->declare_primaryIndex($this::id_RowField);
        $this->declare_primaryIndex($this::id);


        $this->declare_type($this::id,          Type::int);
        $this->declare_type($this::id_head,     Type::int);
        $this->declare_type($this::id_field,    Type::int);
        $this->declare_type($this::id_RowField, Type::int);
        $this->declare_type($this::value,       Type::varchar,500);

        $this->declare_nonclusteredIndex('id_head',$this::id_head);

        $this->declare_type($this::del,        Type::int);
        $this->declare_defaultValue($this::del,'0');

    }

}
