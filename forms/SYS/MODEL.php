<?php
/**
 * Created by PhpStorm.
 * User: rezzalbob
 * Date: 25.09.2019
 * Time: 0:28
 */

namespace forms\SYS;


class MODEL
{
    public function detectKeyAPI($keyAPY)
    {
        $d = new \DB\Table\API_keys();

        return $d
            ->where($d::key_, $keyAPY)
            ->select()
            ->fetch();
    }
}