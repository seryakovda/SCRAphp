<?php


namespace DB\Table;

use \DB\Type;

class DSSL_EventNumberCamera extends \DB\Table
{
    const id = 'id';

    const number = 'number';

    const camera =  'camera';

    const ipCamera =  'ipCamera';

    const xmlData = 'xmlData';

    const dateTimeEvent = 'dateTimeEvent';

    const dateTimeFile = 'dateTimeFile';


    public function initColumn($childClass = '')
    {
        // обязательно для формирования структуры массива
        parent::initColumn(__CLASS__);

        $this->identifierColumn($this::id);
        $this->declare_primaryIndex($this::id);

        $this->declare_type($this::id, Type::int);

        $this->declare_type($this::number,          Type::nvarchar,20);
        $this->declare_type($this::camera,          Type::int);
        $this->declare_type($this::ipCamera,        Type::varchar,15);
        $this->declare_type($this::xmlData,         Type::longtext);
        $this->declare_type($this::dateTimeEvent,   Type::datetime);
        $this->declare_type($this::dateTimeFile,    Type::datetime);

        $this->declare_defaultValue($this::xmlData,"");
        $this->declare_defaultValue($this::dateTimeEvent,'CURRENT_TIMESTAMP');

        $this->declare_nonclusteredIndex("dateTimeEvent",$this::dateTimeFile);
        $this->declare_nonclusteredIndex("number",$this::number);
        $this->declare_nonclusteredIndex("ipCamera",$this::ipCamera);
    }

    /**
     * Должна возвращать массив со строками Содержащими триггеры для таблицы, либо false
    Array(
    Array(
    'name' => 'nameTrigger',
    'body' => 'SQL'
    )
    )
     * @return mixed
     */
    public function triggers(): mixed
    {
        return Array(
            Array(
                'name' => 'TRg_EventNumberCamera',
                'after'=>'AFTER INSERT',
                'body' => /** @lang SQL */'
                    insert into DSSL_TRIGGER_Event (id,ipCamera)
                    VALUES (NEW.id, NEW.ipCamera);
                    '
            )
        );
    }
}

