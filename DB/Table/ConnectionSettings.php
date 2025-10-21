<?php

namespace DB\Table;

use \DB\Type;

class ConnectionSettings extends \DB\Table
{
    const id = 'id';

    const addressPS =  'addressPS';

    const loginPS =  'loginPS';

    const passPS =  'passPS';

    const address_DbOrion =  'address_DbOrion';

    const db_DbOrion =  'db_DbOrion';

    const login_DbOrion =  'login_DbOrion';

    const pass_DbOrion =  'pass_DbOrion';

    const orion_door =  'orion_door';

    public function initColumn($childClass = '')
    {
        // обязательно для формирования структуры массива
        parent::initColumn(__CLASS__);

        $this->identifierColumn($this::id);
        $this->declare_primaryIndex($this::id);


        $this->declare_type($this::id,          Type::int);
        $this->declare_type($this::addressPS,   Type::varchar,50);
        $this->declare_type($this::db_DbOrion,   Type::varchar,50);
        $this->declare_type($this::loginPS,     Type::varchar,50);
        $this->declare_type($this::passPS,      Type::varchar,50);

        $this->declare_type($this::address_DbOrion,   Type::varchar,50);
        $this->declare_type($this::login_DbOrion,     Type::varchar,50);
        $this->declare_type($this::pass_DbOrion,      Type::varchar,50);
        $this->declare_type($this::orion_door,      Type::int);

        $this->declare_defaultValue($this::addressPS,           '10.10.10.10');
        $this->declare_defaultValue($this::loginPS,             'loginPS');
        $this->declare_defaultValue($this::passPS,              'passPS');
        $this->declare_defaultValue($this::address_DbOrion,     '10.10.10.10');
        $this->declare_defaultValue($this::db_DbOrion,          'Orion_XXXX');
        $this->declare_defaultValue($this::login_DbOrion,       'login');
        $this->declare_defaultValue($this::pass_DbOrion,        'pass');
        $this->declare_defaultValue($this::orion_door,          '0');

    }
    public function defaultRows()
    {
        $this->deleteDataFor_defaultRows = false;
        return array(
            array(
                $this::id => 1,
                $this::addressPS =>     '10.10.10.10',
                $this::loginPS =>       'loginPS',
                $this::passPS =>        'passPS',
                $this::address_DbOrion =>     '10.10.10.10',
                $this::db_DbOrion =>     'Orion_XXXX',
                $this::login_DbOrion =>       'login',
                $this::pass_DbOrion =>        'pass',
                $this::orion_door =>        '0',

            ),
        );
    }
}
