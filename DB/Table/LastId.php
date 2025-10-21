<?php

namespace DB\Table;

use \DB\Type;

class LastId extends \DB\Table
{
    const id = 'id';

    const id_pList = 'id_pList';

    const id_pMark =  'id_pMark';

    const id_SPR =  'id_SPR';


    const f_pList = 'f_pList';

    const f_pMark =  'f_pMark';

    const f_SPR =  'f_SPR';

    public function initColumn($childClass = '')
    {
        // обязательно для формирования структуры массива
        parent::initColumn(__CLASS__);
        $this->identifierColumn($this::id);
        $this->declare_primaryIndex($this::id);

        $this->declare_type($this::id,          Type::int);
        $this->declare_type($this::id_pList,    Type::int);
        $this->declare_type($this::id_pMark,    Type::int);
        $this->declare_type($this::id_SPR,      Type::int);
        $this->declare_type($this::f_pList,    Type::int);
        $this->declare_type($this::f_pMark,    Type::int);
        $this->declare_type($this::f_SPR,      Type::int);


        $this->declare_defaultValue($this::id_pList,'0');
        $this->declare_defaultValue($this::id_pMark,'0');
        $this->declare_defaultValue($this::id_SPR,  '0');
        $this->declare_defaultValue($this::f_pList, '0');
        $this->declare_defaultValue($this::f_pMark, '0');
        $this->declare_defaultValue($this::f_SPR,   '0');
    }
    public function defaultRows()
    {
        $this->deleteDataFor_defaultRows = false;
        return array(
            array(
                $this::id => 1,
                $this::id_pList =>  '0',
                $this::id_pMark =>  '0',
                $this::id_SPR   =>  '0',
                $this::f_pList =>   '0',
                $this::f_pMark =>   '0',
                $this::f_SPR   =>   '0',
            ),
        );
    }
}
