<?php

namespace DB\Table;

use \DB\Type;

class Orion_settingsFor_pLogData extends \DB\Table
{
    /**
     * 0 или 1 выход или вход
     */
    const id = 'id';

    const RazdIndex = 'RazdIndex';

    const IndexZone = 'IndexZone';

    const ReaderIndex = 'ReaderIndex';

    const DoorIndex = 'DoorIndex';

    const Mode = 'Mode';

    const ZoneIndex = 'ZoneIndex';

    const Event = 'Event';


    public function initColumn($childClass = '')
    {
        // обязательно для формирования структуры массива
        parent::initColumn(__CLASS__);

        $this->declare_primaryIndex($this::id);

        $this->declare_type($this::id,          Type::int);
        $this->declare_type($this::RazdIndex,   Type::int);//
        $this->declare_type($this::IndexZone,   Type::int);//
        $this->declare_type($this::ReaderIndex, Type::int);//
        $this->declare_type($this::DoorIndex,   Type::int);//
        $this->declare_type($this::Mode,        Type::int);//
        $this->declare_type($this::ZoneIndex,   Type::int);//
        $this->declare_type($this::Event,       Type::int);

    }

}
