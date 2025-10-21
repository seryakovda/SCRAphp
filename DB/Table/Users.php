<?php

namespace DB\Table;

use \DB\Type;

class Users extends \DB\Table
{
    const login =  'login';

    const password_crypto =  'password_crypto';

    const id =  'id';

    const id_human = 'id_human';

    const session_id =  'session_id';

    const superUser = 'superUser';

    const admin = 'admin';

    const uploadDMz = 'uploadDMz';

    const renewPassword = 'renewPassword';

    const del = 'del';

    const runDefaultScript = 'runDefaultScript';

    public function initColumn($childClass = '')
    {
        // обязательно для формирования структуры массива
        parent::initColumn(__CLASS__);

        $this->identifierColumn($this::id);
        $this->declare_primaryIndex($this::id);


        $this->declare_type($this::id,               Type::bigint);
        $this->declare_type($this::login,            Type::nvarchar,40);
        $this->declare_type($this::password_crypto,  Type::nvarchar,64);
        $this->declare_type($this::id_human,         Type::int);
        $this->declare_type($this::session_id,       Type::nvarchar,64);
        $this->declare_type($this::superUser,        Type::int);
        $this->declare_type($this::uploadDMz,        Type::int);


        $this->declare_defaultValue($this::id_human,   '0');
        $this->declare_defaultValue($this::uploadDMz,  '0');

        $this->declare_defaultValue($this::superUser,   '0');
        $this->declare_defaultValue($this::admin,   '0');

        $this->declare_type($this::renewPassword,       Type::int);
        $this->declare_defaultValue($this::renewPassword,   0);

        $this->declare_type($this::runDefaultScript,        Type::varchar,100);
        $this->declare_defaultValue($this::runDefaultScript,   'SkeletonApp');


        //$this->declare_nonclusteredIndex('id',$this::id);

        $this->declare_type($this::del,        Type::int);
        $this->declare_defaultValue($this::del,'0');

    }


    public function defaultRows()
    {
        $this->deleteDataFor_defaultRows = false;
        return array(
            array(
                $this::id => 1,
                $this::id_human => '1',
                $this::login => 'admin',
                $this::password_crypto => '66d45d5b1d4d0ee6c236828a8dab10027fd4de8a4cf19e2018fac28bc299035d',//admin
                $this::admin => '1',
                $this::superUser => '1',
                $this::renewPassword => '0',
            ),
            array(
                $this::id => 2,
                $this::id_human => '2',
                $this::login => 'user',
                $this::password_crypto => '95eb7e3fb0d6784765127c79b5eb555e5c1bff3da4f4300ef8101d742457adbf', //123456
                $this::admin => '0',
                $this::superUser => '0',
                $this::renewPassword => '0',
                $this::runDefaultScript => 'SKUD\EventsMonitor2',
            ),
        );
    }
}
