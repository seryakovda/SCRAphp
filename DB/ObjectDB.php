<?php

namespace DB;

class ObjectDB extends Connection
{
    public $SQL_QUERY;

    /**
     * Очерёдность (сортрирвка) с формирования представления
     * для ситуаций когда предствление зависит от другого предстваления, соответственно необходима очерёдность
     * какое предствление в каком порядке создать чтобы зависимости не развалились
     * @var int
     */
    private int $levelQuery = 1;

    public function init()
    {
        parent::init();
        $this->setLevelQuery(1);
        $this->SQL();
    }


    public function SQL()
    {
        $this->SQL_QUERY="";
    }

    public function get_SQL_QUERY()
    {
        return $this->SQL_QUERY;
    }

    /**
     * @param int $levelQuery
     */
    public function setLevelQuery(int $levelQuery): void
    {
        $this->levelQuery = $levelQuery;
    }

    /**
     * @return int
     */
    public function getLevelQuery(): int
    {
        return $this->levelQuery;
    }


}