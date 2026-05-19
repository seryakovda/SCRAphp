<?php

namespace DB\Table;

use \DB\Type;

class BatMon extends \DB\Table
{
    const id = 'id';

    const keyAPI = 'keyAPI';

    const dateTimeIndex = 'dateTimeIndex';

    const timestamp = 'timestamp';

    const dateTime =  'dateTime';

    const batteryLevel =  'batteryLevel';

    const batteryTemperature =  'batteryTemperature';

    const chargingStatus =  'chargingStatus';

    const voltage =  'voltage';

    public function initColumn($childClass = '')
    {
        // обязательно для формирования структуры массива
        parent::initColumn(__CLASS__);

        $this->declare_primaryIndex($this::id);
        $this->declare_primaryIndex($this::keyAPI);
        $this->identifierColumn($this::id);


        $this->declare_type($this::keyAPI,Type::varchar,36);
        $this->declare_type($this::id,Type::int);

        $this->declare_type($this::dateTimeIndex,Type::datetime);
        $this->declare_type($this::timestamp,Type::bigint);
        $this->declare_type($this::dateTime,Type::datetime);
        $this->declare_type($this::batteryLevel,Type::varchar,6);
        $this->declare_type($this::batteryTemperature,Type::varchar,6);
        $this->declare_type($this::chargingStatus,Type::int);
        $this->declare_type($this::voltage,Type::varchar,6);

        $this->declare_defaultValue($this::dateTimeIndex,'NOW()');
    }

}
