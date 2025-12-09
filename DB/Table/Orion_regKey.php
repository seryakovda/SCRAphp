<?php

namespace DB\Table;

use \DB\Type;

class Orion_regKey extends \DB\Table
{
    const id = 'id';

    const keyCard =  'keyCard';

    const inOut_ = 'inOut_';

    const f_upload = 'f_upload';

    const dateTimeEvent = 'dateTimeEvent';

    public function initColumn($childClass = '')
    {
        // обязательно для формирования структуры массива
        parent::initColumn(__CLASS__);

        $this->identifierColumn($this::id);
        $this->declare_primaryIndex($this::id);


        $this->declare_type($this::id,              Type::int);
        $this->declare_type($this::keyCard,         Type::varchar,10);
        $this->declare_type($this::inOut_,           Type::int);
        $this->declare_type($this::f_upload,        Type::int);
        $this->declare_type($this::dateTimeEvent,   Type::datetime);

        $this->declare_defaultValue($this::dateTimeEvent,'NOW()');
        $this->declare_defaultValue($this::f_upload,'0');
        $this->declare_defaultValue($this::inOut_,'0');

    }

}
