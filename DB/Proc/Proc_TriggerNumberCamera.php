<?php

namespace DB\Proc;

class Proc_TriggerNumberCamera extends \DB\SQL\Proc\Proc_TriggerNumberCamera
{
    public function parameters($ipCamera)
    {
        return $this
	            ->set('ipCamera',$ipCamera)
            ->SQLExec_MySQL();
    }
}
