<?php

namespace DB\Table;

use \DB\Type;

class NumberCameraImage extends \DB\Table
{
    const id_event =  'id_event';

    const id =  'id';

    const f_main =  'f_main';

    const filename =  'filename';

    const img =  'img';


    public function initColumn($childClass = '')
    {
        // обязательно для формирования структуры массива
        parent::initColumn(__CLASS__);

        $this->identifierColumn($this::id);
        $this->declare_primaryIndex($this::id);


        $this->declare_type($this::id_event,    Type::int);
        $this->declare_type($this::id,          Type::int);
        $this->declare_type($this::f_main,      Type::int);
        $this->declare_type($this::filename,    Type::varchar, 100);
        $this->declare_type($this::img,         Type::mediumblob);

    }

}
