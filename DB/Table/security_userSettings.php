<?php

namespace DB\Table;

use \DB\Type;

class security_userSettings extends \DB\Table
{
    const id = 'id';

    const id_user =  'id_user';

    const nameVar = 'nameVar';

    const value = 'value';

    public function initColumn($childClass = '')
    {
        // обязательно для формирования структуры массива
        parent::initColumn(__CLASS__);
        $this->identifierColumn($this::id);
        $this->declare_primaryIndex($this::id);

        $this->declare_type($this::id_user, Type::bigint);
        $this->declare_type($this::nameVar, Type::varchar,50);
        $this->declare_type($this::id,      Type::int);
        $this->declare_type($this::value,   Type::varchar,50);

    }

}
