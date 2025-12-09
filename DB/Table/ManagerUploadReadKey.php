<?php

namespace DB\Table;

use \DB\Type;

class ManagerUploadReadKey extends \DB\Table
{
    const job =  'job';

    const timeJob = 'timeJob';

    public function initColumn($childClass = '')
    {
        // обязательно для формирования структуры массива
        parent::initColumn(__CLASS__);

        $this->declare_primaryIndex($this::job);


        $this->declare_type($this::job,     Type::int);
        $this->declare_type($this::timeJob, Type::datetime);

    }
    public function defaultRows()
    {
        //$this->deleteDataFor_defaultRows = false;
        return array(
            array(
                $this::job => 0,
                $this::timeJob => date ("Y-m-d H:i:s"),
            ),
        );
    }
}
