<?php
/**
 * Created by PhpStorm.
 * User: rezzalbob
 * Date: 23.11.2020
 * Time: 20:47
 */

namespace models\Standards;
/**
 * Класс определяющий стандарты рабты со справочником
 * Class ViewStandardFields
 * @package forms
 */
class FieldsForEdit
{
    const TypeFree = 'Free';
    const TypeDate = 'Date';
    const TypeMoney = 'Money';
    const InstrumentBTN = 'BTN';
    const InstrumentCheck = 'Check';
    const InstrumentSPR = 'SPR';
    private $arrayColumns = Array();
    private $columnName;
    private $nameDB;
    private $ROW;

    function __construct()
    {
        $this->useFilterOnly = false;
    }

    /**
     * Устанавливает какой справочник будет использоваться для редактирования поля
     * указывается полное имя справочника 'SPR\\\\Type_accrual', 'SPR\\\\Menu'
     *
     * @param $value
     * @return $this
     */
    public function setCatalog($value)
    {
        $this->arrayColumns[$this->columnName]['Catalog'] = $value;
        return $this;
    }

    /**
     * Устанавливает какой инструмент будет использоваться
     *
     * @return $this
     */
    public function setInstrument_Check()
    {
        $this->arrayColumns[$this->columnName]['instrument'] = $this::InstrumentCheck;
        return $this;
    }

    /**
     * Устанавливает какой инструмент будет использоваться
     *
     * @return $this
     */
    public function setInstrument_SPR()
    {
        $this->arrayColumns[$this->columnName]['instrument'] = $this::InstrumentSPR;
        return $this;
    }

    /**
     * Строку данных в БД виде массива
     *
     * @param $ROW
     * @return $this
     */
    public function setROW($ROW)
    {
        $this->ROW = $ROW;
        return $this;
    }

    /**
     * Шаблон ввода данных поля Дата
     *
     * @param string $value имя справочника
     * @return $this
     */
    public function setTypeData()
    {
        $this->arrayColumns[$this->columnName]['Type'] = $this::TypeDate;
        $this->arrayColumns[$this->columnName]['Pattern'] = '99.99.9999'; //$this::TypeDate
        return $this;
    }

    public function address_classifier()
    {
        $this->arrayColumns[$this->columnName]['Catalog'] = "address_classifier";
        return $this;
    }
    /**
     * Шаблон ввода данных поля Дата
     *
     * @param string $value имя справочника
     * @return $this
     */
    public function setTypeMoney()
    {
        $this->arrayColumns[$this->columnName]['Type'] = $this::TypeMoney;
        $this->arrayColumns[$this->columnName]['Pattern'] = '#9.99'; //$this::TypeMoney
        return $this;
    }


    /**
     * Указщывкет какое поле будет отредактировано в бд
     *
     * @param $value
     * @return $this
     */
    public function setNameFieldForEdit($value)
    {
        $this->arrayColumns[$this->columnName]['NameFieldForEdit'] = $value;
        return $this;
    }

    /**
     * Устанавлиывает имя поля, оно должно соответствовать полю базы данных
     * для которого сформируются настройки
     *
     * @param $columnName
     * @return $this
     */
    public function setName($columnName)
    {
        $this->columnName = $columnName;
        $this->init();
        return $this;
    }

    public function name($columnName)
    {
        $this->columnName = $columnName;
        return $this;
    }
    /**
     * @param string $nameField
     * @return $this
     */
    public function setFieldForDisplayText($nameField = false)
    {
        $this->arrayColumns[$this->columnName]['field'] = $nameField;
        return $this;
    }

    public function getFieldForDisplayText()
    {
        return $this->arrayColumns[$this->columnName]['field'];
    }
    /**
     * в случае если имя новое, происходит его заполение стандартными значениями
     */
    private function init()
    {
        if (!array_key_exists($this->columnName, $this->arrayColumns)) {
            $this->arrayColumns[$this->columnName]['instrument'] = $this::InstrumentBTN;
            $this->arrayColumns[$this->columnName]['field'] = $this->columnName;
            $this->arrayColumns[$this->columnName]['Caption'] = '';
            $this->arrayColumns[$this->columnName]['NameFieldForEdit'] = $this->columnName;
            $this->arrayColumns[$this->columnName]['Width'] = 250;
            $this->arrayColumns[$this->columnName]['Height'] = 25;
            $this->arrayColumns[$this->columnName]['Type'] = $this::TypeFree;

            $this->arrayColumns[$this->columnName]['readOnly'] = false;

            $this->arrayColumns[$this->columnName]['Catalog'] = false;
            $this->arrayColumns[$this->columnName]['Pattern'] = '';


            $this->arrayColumns[$this->columnName]['InToFilter'] = true;

        }
    }

    /**
     * указывает не использовать поле для построения фильтра
     *
     * @return $this
     */
    public function setNoFilter()
    {
        $this->arrayColumns[$this->columnName]['InToFilter'] = false;
        return $this;
    }

    /**
     * Устанавливает Коментарий к выводимому полю
     *
     * @param $value
     * @return $this
     */
    public function setCaption($value)
    {
        $this->arrayColumns[$this->columnName]['Caption'] = $value;
        return $this;
    }

    /**
     * Устанавливает ширину колонки для грида
     *
     * @param $value
     * @return $this
     */
    public function setWidth($value)
    {
        $this->arrayColumns[$this->columnName]['Width'] = $value;
        return $this;
    }

    /**
     * Устанавливает ширину вводимого поля дря фильтра
     *
     * @param $value
     * @return $this
     */
    public function setHeight($value)
    {
        $this->arrayColumns[$this->columnName]['Height'] = $value;
        return $this;
    }

    /**
     * Указывает что перечисляться будут только элементы учавствующие в фильтрации
     *
     * @return $this
     */
    public function readOnly()
    {
        $this->arrayColumns[$this->columnName]['readOnly'] = true;
        return $this;
    }

    /**
     * Сбрасывает указатель считывания из массива на начало
     * Сбрасывает настройку считываания для фильтра по умолчанию
     *
     */
    public function resetFetchName()
    {
        $this->useFilterOnly = false;
        reset($this->arrayColumns);
    }

    /**
     * в зависисмости от $this->useFilterOnly возвращает
     * @return mixed
     */
    public function fetchName()
    {
        $this->columnName = key($this->arrayColumns);
        next($this->arrayColumns);
        return $this->columnName;
    }

    public function nextField()
    {
        next($this->arrayColumns);
        $this->columnName = key($this->arrayColumns);
        return $this->columnName;
    }

    public function getType()
    {
        return $this->arrayColumns[$this->columnName]['Type'];
    }

    public function getPattern()
    {
        return $this->arrayColumns[$this->columnName]['Pattern'];
    }

    public function getCatalog()
    {
        return $this->arrayColumns[$this->columnName]['Catalog'];
    }
/*
    public function getNameDB($nameDB)
    {
        return $this->nameDB = $nameDB;
    }

    public function setNameDB($nameDB)
    {
        $this->nameDB = $nameDB;
        return $this;
    }
*/
    public function getNameFieldForEdit()
    {
        return $this->arrayColumns[$this->columnName]['NameFieldForEdit'];
    }


    public function getNoFilter()
    {
        return $this->arrayColumns[$this->columnName]['InToFilter'];
    }


    public function getCaption()
    {
        return $this->arrayColumns[$this->columnName]['Caption'];
    }


    public function getWidth()
    {
        return $this->arrayColumns[$this->columnName]['Width'];
    }


    public function getHeight()
    {
        return $this->arrayColumns[$this->columnName]['Height'];
    }

    public function getInstrument()
    {
        return $this->arrayColumns[$this->columnName]['instrument'];
    }

    public function  getReadOnly()
    {
        return $this->arrayColumns[$this->columnName]['readOnly'];
    }

    public function processingAccordingToType($value)
    {

        if ($this->arrayColumns[$this->columnName]['Type'] == $this::TypeDate) {
            $value = date("d.m.Y", strtotime($value));
        }
        if ($this->arrayColumns[$this->columnName]['Type'] == $this::TypeMoney) {
            $value = round($value,2);
        }
        //htmlspecialchars(addslashes($value))
        return $value;
    }


}