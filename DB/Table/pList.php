<?php

namespace DB\Table;

use \DB\Type;

class pList extends \DB\Table
{
    const uid = 'uid';

    const ID = 'ID';
    const Name = 'Name';
    const FirstName = 'FirstName';
    const MidName = 'MidName';
    const CompN = 'CompN';
    const DivN = 'DivN';
    const name_pPost = 'name_pPost';
    const Picture = 'Picture';

    public function initColumn($childClass = '')
    {
        // обязательно для формирования структуры массива
        parent::initColumn(__CLASS__);

        $this->identifierColumn($this::uid);
        $this->declare_primaryIndex($this::uid);


        $this->declare_type($this::uid,       Type::int);

        $this->declare_type($this::ID,          Type::int);
        $this->declare_type($this::Name,        Type::varchar,50);
        $this->declare_type($this::FirstName,   Type::varchar,50);
        $this->declare_type($this::MidName,     Type::varchar,50);
        $this->declare_type($this::CompN,       Type::varchar,200);
        $this->declare_type($this::DivN,        Type::varchar,200);
        $this->declare_type($this::name_pPost,  Type::varchar,200);
        $this->declare_type($this::Picture,     Type::mediumblob);

        $this->declare_nonclusteredIndex("nenID",$this::ID);
    }

}
