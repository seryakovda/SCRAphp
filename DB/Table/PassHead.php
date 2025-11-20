<?php

namespace DB\Table;

use \DB\Type;

class PassHead extends \DB\Table
{
    const id_Human = 'id_Human';

    const id = 'id';

    const id_TypePass = 'id_TypePass';

    const dateStart = 'dateStart';

    const dateEnd = 'dateEnd';

    /**
     * коментарий
     */
    const note =  'note';

    const number = 'number';

    const del = 'del';

    /**
     * объект сервера отчетов по которому будет формироваться пропуск для формировании истории
     * \\Reports\\AvtoLich
     */
    const objectReport = 'objectReport';

    const nameImage = 'nameImage';

    const qrCode = 'qrCode';

    const eMarin = 'eMarin';

    const CodeP = 'CodeP';

    const id_Orion = 'id_Orion';

    /**
     * назначается настройкой свойств пользователя FilterPassByUserGroup таблица security_userTypeSettings
     */
    const id_userGroup = 'id_userGroup';

    public function initColumn($childClass = '')
    {
        // обязательно для формирования структуры массива
        parent::initColumn(__CLASS__);

        //$this->identifierColumn($this::id);
        $this->declare_primaryIndex($this::id_Human);
        $this->declare_primaryIndex($this::id);


        $this->declare_type($this::id_Human,            Type::int);
        $this->declare_type($this::id,                  Type::int);
        $this->declare_type($this::id_TypePass,         Type::int);
        $this->declare_type($this::dateStart,           Type::date);
        $this->declare_type($this::dateEnd,             Type::date);
        $this->declare_type($this::note,                Type::varchar,200);
        $this->declare_type($this::number,              Type::int);
        $this->declare_type($this::objectReport,        Type::varchar,200);
        $this->declare_type($this::nameImage,           Type::varchar,36);
        $this->declare_type($this::qrCode,              Type::varchar,50);
        $this->declare_type($this::eMarin,              Type::varchar,50);
        $this->declare_type($this::CodeP,               Type::varchar,50);
        $this->declare_type($this::id_Orion,            Type::int);
        $this->declare_type($this::id_userGroup,        Type::int);


        $this->declare_defaultValue($this::note,        '');
        $this->declare_defaultValue($this::nameImage,   'Empty');
        $this->declare_defaultValue($this::eMarin,      '');
        $this->declare_defaultValue($this::CodeP,       '');
        $this->declare_defaultValue($this::id_Orion,    '0');
        $this->declare_defaultValue($this::id_userGroup,     '0');

        $this->declare_nonclusteredIndex('id_del',$this::id);
        $this->declare_nonclusteredIndex('id_del',$this::del);

        $this->declare_nonclusteredIndex('id',$this::id);

        $this->declare_nonclusteredIndex('qrCode',$this::qrCode);


        $this->declare_type($this::del,        Type::int);
        $this->declare_defaultValue($this::del,'0');

    }

}
