<?php

namespace DB\Table;

use \DB\Type;

class nXms_Excel extends \DB\Table
{
    const ip = 'ip';

    const id = 'id';

    const mac =  'mac';

    const name = 'name';

    const description = 'description';

    const location = 'location';

    const infa = 'infa';

    const typeObject = 'typeObject';

    const subTypeObject = 'subTypeObject';

    const lastDataPing = 'lastDataPing';

    const pingStart = 'pingStart';

    const pingEnd = 'pingEnd';

    const percentLost = 'percentLost';

    const ms = 'ms';

    const cameraChannelGuid = 'cameraChannelGuid';

    const cameraServerIp = 'cameraServerIp';

    const cameraServerName = 'cameraServerName';

    public function initColumn($childClass = '')
    {
        // обязательно для формирования структуры массива
        parent::initColumn(__CLASS__);

        $this->declare_primaryIndex($this::id);
        $this->identifierColumn($this::id);
        $this->declare_type($this::id,          Type::int);

        $this->declare_type($this::ip,                  Type::varchar,15);
        $this->declare_type($this::name,                Type::varchar,255);
        $this->declare_type($this::mac,                 Type::varchar,255);
        $this->declare_type($this::description,         Type::varchar,255);
        $this->declare_type($this::location,            Type::varchar,255);
        $this->declare_type($this::infa,                Type::varchar,255);
        $this->declare_type($this::typeObject,          Type::int);
        $this->declare_type($this::subTypeObject,       Type::int);
        $this->declare_type($this::lastDataPing,        Type::datetime);
        $this->declare_type($this::pingStart,           Type::varchar,1);
        $this->declare_type($this::pingEnd,             Type::varchar,1);
        $this->declare_type($this::percentLost,         Type::int);
        $this->declare_type($this::ms,                  Type::nvarchar,10);
        $this->declare_type($this::cameraChannelGuid,   Type::varchar,20);
        $this->declare_type($this::cameraServerIp,      Type::varchar,15);
        $this->declare_type($this::cameraServerName,    Type::varchar,100);

        $this->declare_defaultValue($this::subTypeObject,       0);
        $this->declare_defaultValue($this::percentLost,         0);
        $this->declare_defaultValue($this::ms,                  0);
        $this->declare_defaultValue($this::cameraChannelGuid,   '');
        $this->declare_defaultValue($this::cameraServerIp,      '');
        $this->declare_defaultValue($this::cameraServerName,    '');

        $this->declare_nonclusteredIndex('IP',$this::ip);

    }

}
