<?php
/**
 * Created by PhpStorm.
 * User: rezzalbob
 * Date: 28.11.2019
 * Time: 15:46
 */

namespace forms;


abstract class FormElements
{
    public $objectName;
    public $objectFullName;
    public $objectParentName;

    public $ELM;
    public $WND;
    public $BTN;

    public function __construct()
    {
        $this->defineObjectName();
    }

    public function defineObjectName()
    {
        $this->objectFullName = get_called_class();
        $this->objectFullName = str_replace("forms\\", "", $this->objectFullName);
        $this->objectFullName = str_replace("\\Elements", "", $this->objectFullName);
        $objectFullName = explode("\\", $this->objectFullName);
        $this->objectName = end($objectFullName);
        $this->objectParentName = str_replace("\\" . $this->objectName, "", $this->objectFullName);
        $this->objectFullName = str_replace("\\", "_", $this->objectFullName);
    }

}