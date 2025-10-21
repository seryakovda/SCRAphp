<?php
/**
 * Created by PhpStorm.
 * User: rezzalbob
 * Date: 28.04.2020
 * Time: 14:03
 */

namespace forms;


abstract class FormDirElements
{
    public $ParentObject;
    public $objectName;
    public $objectFullName;
    public $objectParentName;

    public $caption;
    public $function;

    public $jobInCloseMonthForEdit;
    public $jobInCloseMonthForPayment;
    public $selectedButton;

    public $SUPERUSER;

    public $f_run;

    public $sort;
    public $blockNum = 0;

    public function __construct()
    {
        $this->defineObjectName();
        $this->initClassDefault();
        $this->initClass();
    }

    public function defineObjectName()
    {
        $this->objectFullName = get_called_class();
        $OFN = explode('\\', $this->objectFullName);
        $name = end($OFN);
        $this->objectFullName = str_replace("\\$name", "", $this->objectFullName);
        $this->objectFullName = str_replace("forms\\", "", $this->objectFullName);
        $this->objectFullName = str_replace("\\Elements", "", $this->objectFullName);
        $objectFullName = explode("\\", $this->objectFullName);
        $this->objectName = end($objectFullName);
        $this->objectParentName = str_replace("\\" . $this->objectName, "", $this->objectFullName);
        $this->objectFullName = str_replace("\\", "_", $this->objectFullName);
    }

    public function initClassDefault()
    {
        $this->caption = "Элемент управления";
        $this->function = "";
        $this->jobInCloseMonthForEdit = true;
        $this->jobInCloseMonthForPayment = true;
        $this->SUPERUSER = false;
        $this->sort = 99999;
        $this->blockNum = 0;
    }

    public function initClass()
    {
    }

    public function get()
    {
        $HTML = "";
        if ($this->right()){
            $HTML = $this->HTML();
        }
        return $HTML;
    }

    public function right(){return true;}
}