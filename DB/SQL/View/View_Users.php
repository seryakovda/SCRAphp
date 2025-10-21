<?php

namespace DB\SQL\View;

use DB\ObjectDB;

class View_Users extends ObjectDB
{

    // Если требуется изменить очерёдность исполнения
     public function init()
    {
        parent::init();
        $this->setLevelQuery(1);
    }


    public function SQL()
    {
        $objectName = $this->getName();

        $select = "
                   SELECT        
                          Users.*, 
                         Human.surname, 
                         Human.name, 
                         Human.patronName
                   FROM
                         Users 
                   INNER JOIN Human 
                       ON 
                           Users.id_human = Human.id
                    ";

        $this->SQL_QUERY="CREATE VIEW $objectName
        as
            $select
        ";
    }
}