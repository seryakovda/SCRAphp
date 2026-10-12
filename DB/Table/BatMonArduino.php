<?php

namespace DB\Table;

use \DB\Type;

class BatMonArduino extends \DB\Table
{
    const deviceId = 'deviceId';

    const dateTimeIndex = 'dateTimeIndex';


    const f_relayPin_trigger =  'f_relayPin_trigger';

    const f_restart_btn =  'f_restart_btn';

    const f_restart_trigger =  'f_restart_trigger';

    const voltage =  'voltage';

    public function initColumn($childClass = '')
    {
        // обязательно для формирования структуры массива
        parent::initColumn(__CLASS__);

        $this->declare_primaryIndex($this::deviceId);
        $this->declare_primaryIndex($this::dateTimeIndex);


        $this->declare_type($this::deviceId,            Type::varchar,36);
        $this->declare_type($this::dateTimeIndex,       Type::datetime);

        $this->declare_type($this::f_relayPin_trigger,  Type::int);
        $this->declare_type($this::f_restart_btn,       Type::int);
        $this->declare_type($this::f_restart_trigger,   Type::int);
        $this->declare_type($this::voltage,             Type::money);

        $this->declare_defaultValue($this::dateTimeIndex,'getdate()');
    }

}
