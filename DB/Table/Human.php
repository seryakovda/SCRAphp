<?php

namespace DB\Table;

use \DB\Type;

class Human extends \DB\Table
{
    const id = 'id';
    /**
     * фамилия
     */
    const surname =  'surname';

    /**
     * имя
     */
    const name = 'name';

    /**
     * отчество
     */
    const patronName = 'patronName';

    /**
     * дата рождения
     */
    const DOB = 'DOB';

    const doc_series = 'doc_series';
    const doc_number = 'doc_number';
    const doc_date = 'doc_date';
    const doc_issued = 'doc_issued';
    const doc_code = 'doc_code';

    const address = 'address';

    const address2 = 'address2';

    const sex = 'sex';

    const passportOld = 'passportOld';

    const del = 'del';

    const citizenship = 'citizenship';

    const id_organization = 'id_organization';

    const id_post = 'id_post';

    const id_Orion = 'id_Orion';

    public function initColumn($childClass = '')
    {
        // обязательно для формирования структуры массива
        parent::initColumn(__CLASS__);

        $this->identifierColumn($this::id);
        $this->declare_primaryIndex($this::id);

        $this->declare_type($this::id,              Type::int);
        $this->declare_type($this::DOB,             Type::date);
        $this->declare_type($this::doc_series,      Type::varchar,15);
        $this->declare_type($this::doc_number,      Type::varchar,25);
        $this->declare_type($this::doc_date,        Type::date);
        $this->declare_type($this::doc_issued,      Type::varchar,100);
        $this->declare_type($this::doc_code,        Type::varchar,7);
        $this->declare_type($this::id_organization, Type::int);
        $this->declare_type($this::id_post,         Type::int);


        $this->declare_defaultValue($this::DOB,             '01.01.1900');
        $this->declare_defaultValue($this::doc_series,      'XX XX');
        $this->declare_defaultValue($this::doc_number,      'XXXXXX');
        $this->declare_defaultValue($this::doc_date,        '01.01.1900');
        $this->declare_defaultValue($this::doc_issued,      'Выдан...');
        $this->declare_defaultValue($this::doc_code,        'XXX-XXX');
        $this->declare_defaultValue($this::id_organization, 0);
        $this->declare_defaultValue($this::id_post,         0);

        $this->declare_type($this::sex,    Type::int);
        $this->declare_defaultValue($this::sex,    '1');

        $this->declare_type($this::address,    Type::varchar,200);
        $this->declare_defaultValue($this::address,    'Адрес');

        $this->declare_type($this::address2,    Type::varchar,200);
        $this->declare_defaultValue($this::address2,    'Адрес');

        $this->declare_type($this::passportOld,    Type::varchar,200);
        $this->declare_defaultValue($this::passportOld,    '');

        $this->declare_type($this::citizenship,    Type::varchar,20);
        $this->declare_defaultValue($this::citizenship,    'РФ');

        $this->declare_type($this::id_Orion,    Type::int);
        $this->declare_defaultValue($this::id_Orion,0);


        $this->declare_nonclusteredIndex('FIO',$this::surname);
        $this->declare_nonclusteredIndex('FIO',$this::name);
        $this->declare_nonclusteredIndex('FIO',$this::patronName);

        $this->declare_type($this::del,        Type::int);
        $this->declare_defaultValue($this::del,'0');

    }

    public function defaultRows()
    {
        $this->deleteDataFor_defaultRows = false;
        return array(
            array(
                $this::id => 1,
                $this::surname => 'admin',
                $this::name => 'admin',
                $this::patronName => 'admin',

            ),
            array(
                $this::id => 2,
                $this::surname => 'user',
                $this::name => 'user',
                $this::patronName => 'user',
            ),
        );
    }

}
