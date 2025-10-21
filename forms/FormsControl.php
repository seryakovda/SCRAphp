<?php
/**
 * Created by PhpStorm.
 * User: rezzalbob
 * Date: 18.11.2019
 * Time: 12:15
 */

namespace forms;

use models\Standards\TypeFilterInput;
use views\Elements\Button\Button;
use views\Elements\Input\Input;

abstract class FormsControl
{
    /**
     * @var \forms\FormView
     */
    public $VIEW;
    /**
     * @var \forms\FormsModel
     */
    public $MODEL;
    public $objectName;
    public $objectFullName;
    public $objectParentName; // Полное имя объекта SPR_Users = forms\SPR\Users
    public $jobInCloseMonthForEdit = true; //SPR = forms\SPR
    public $jobInCloseMonthForPayment = true;
    public $formWidth;
    public $formName;
    public $filterPatentFieldName;
    public $filterParentValue;
    public $filterParent;
    public $allInsertOff;
    public $ColumnID = 'id';
    public $columnName = 'name';
    public $superUserOnly;
    private $route;

    public function __construct()
    {

        $this->defineObjectName();
    }


    /**
     * @param bool $visibleHeadTable
     * @return $this
     */
    public function setVisibleHeadTable(bool $visibleHeadTable = true)
    {
        $this->VIEW->setVisibleHeadTable($visibleHeadTable) ;
        return $this;
    }

    /**
     * @param string $columnName
     * @return $this
     */
    public function setColumnName(string $columnName)
    {
        $this->columnName = $columnName;
        return $this;
    }

    /**
     * @return string
     */
    public function getColumnName(): string
    {
        return $this->columnName;
    }

    /**
     * @return string
     */
    public function getColumnID(): string
    {
        return $this->ColumnID;
    }


    /**
     * Вырубает видимость окна и заголовок
     * @return $this
     */
    public function headNone()
    {
        $this->VIEW->headNone();
        return $this;
    }
    /**
     * Формирует
     * objectFullName
     * objectName
     * objectParentName
     * запускает объявление этих же свойств в дочерних VIEW и MODEL
     */
    public function defineObjectName($addNameObject = "")
    {
        $this->superUserOnly = false;

        $this->objectFullName = get_called_class().$addNameObject;
        $this->objectFullName = str_replace("forms\\", "", $this->objectFullName);
        $this->objectFullName = str_replace("\\Control", "", $this->objectFullName);

        $objectFullName = explode("\\", $this->objectFullName);
        $this->objectName = end($objectFullName);
        $this->objectParentName = str_replace("\\" . $this->objectName, "", $this->objectFullName);
        if ($this->objectParentName == $this->objectFullName) $this->objectParentName = '';
        $this->objectParentName = str_replace("\\", "\\\\", $this->objectParentName);

        $this->objectFullName = str_replace("\\", "_", $this->objectFullName);
        $this->allInsertOff = true;

        $_SESSION["width_$this->objectFullName"] = array_key_exists("width_$this->objectFullName", $_SESSION) ? $_SESSION["width_$this->objectFullName"] : 1050;

        $this->formWidth = $_SESSION["width_$this->objectFullName"];
        $this->defineViewVariable();
        $this->defineModelVariable();
    }

    /**
     * После того как в дочернем классу былы определены MODEL и VIEW
     * необходимо передать им все основные свойства и имена формы
     *
     */
    public function defineViewVariable()
    {
        if (is_object($this->VIEW)) {
            $this->VIEW->formWidth = $this->formWidth;
            $this->VIEW->objectParentName = $this->objectParentName;
            $this->VIEW->objectName = $this->objectName;
            $this->VIEW->allInsertOff = $this->allInsertOff;
            $this->VIEW->objectFullName = $this->objectFullName;
            $this->VIEW->nameGreed = $this->objectFullName . "_greed";
            $this->VIEW->nameGreedDIV = $this->VIEW->nameGreed . "_div";
            $this->VIEW->nameEditDIV = $this->objectFullName . "_edit_div";
        }
    }

    /**
     * После того как в дочернем классу былы определены MODEL и VIEW
     * необходимо передать им все основные свойства и имена формы
     *
     */
    public function defineModelVariable()
    {

        if (is_object($this->MODEL)) {
            $_SESSION["table_$this->objectFullName"] = array_key_exists("table_$this->objectFullName", $_SESSION) ? $_SESSION["table_$this->objectFullName"] : "";
            $this->MODEL->table = $_SESSION["table_$this->objectFullName"];

            $_SESSION["OrderString_$this->objectFullName"] = array_key_exists("OrderString_$this->objectFullName", $_SESSION) ? $_SESSION["OrderString_$this->objectFullName"] : "";
            $this->MODEL->OrderString = $_SESSION["OrderString_$this->objectFullName"];

            $_SESSION["tableForEdit_$this->objectFullName"] = array_key_exists("tableForEdit_$this->objectFullName", $_SESSION) ? $_SESSION["tableForEdit_$this->objectFullName"] : "";
            $this->MODEL->tableForEdit = $_SESSION["tableForEdit_$this->objectFullName"];
        }
    }

    /**
     * Возможно это должно было помещено в интерфейс
     *
     * метод вызывается из основного маршрутизатора для выполнения дальнейших инструкций (методов Этого класса)
     */
    public function run()
    {
        /**
         * Если не суперюзер делать дальше нечего
         * superUserOnly определяется непосредственно в классе по умолчанию опеределно false в $this->defineObjectName();
         */
        if (($this->superUserOnly) && ($_SESSION["superUser"] != 1)) {
            \views\Views::MsgBlock("Внимание", "Доступ заперщён");
            return;
        }

        /**
         * анализ маршрута r1
         */
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            $this->route = empty($_GET["r1"]) ? "defaultMethod" : $_GET["r1"];
        } else {
            $this->route = empty($_POST["r1"]) ? "defaultMethod" : $_POST["r1"];
        }
        if (method_exists($this, $this->route)) {
            $runMethod = $this->route;
            $this->$runMethod();
        } else {
            $this->defaultMethod();
        };

    }

    public function defaultMethod()
    {
        $this->setFormWidth(1680);
        $this->setTable("");
    }

    /**
     * @param int $formWidth устанавливает ширину окна формы
     */
    public function setFormWidth(int $formWidth)
    {
        $this->VIEW->formWidth = $formWidth;
        $_SESSION["width_$this->objectFullName"] = $formWidth;
    }

    /**
     * @param string $table устанавливает имя таблицы(вьювера) к которой будет производится обращение
     */
    public function setTable(string $table)
    {
        $this->MODEL->table = $table;
        $_SESSION["table_$this->objectFullName"] = $table;
        $this->MODEL->tableForEdit = $table;
        $_SESSION["tableForEdit_$this->objectFullName"] = $table;
    }

    /**
     * @param string $OrderString Устанавливает Строку сортировки Указанную в переменной $table
     */
    public function setOrderString(string $OrderString)
    {
        $this->MODEL->OrderString = $OrderString;
        $_SESSION["OrderString_$this->objectFullName"] = $OrderString;

    }

    /**
     * @param string $table устанавливает имя таблицы(вьювера) к которой будет производится обращение
     */
    public function setTableForEdit(string $table)
    {
        $this->MODEL->tableForEdit = $table;
        $_SESSION["tableForEdit_$this->objectFullName"] = $table;
    }
    /**
     * ////////////////////////////////////////////////////////
     * Для взаимодействия со справочником из внешних модулей
     * ////////////////////////////////////////////////////////
     *
     * @return mixed возвращает объект PDO с данными из модели
     */
    public function getData()
    {
        $this->init();
        $this->defineTable();

        return $this->MODEL->getData();
    }


    /**
     * ////////////////////////////////////////////////////////
     * Для взаимодействия со справочником из внешних модулей
     * ////////////////////////////////////////////////////////
     *
     * @return string Возвращает HTML вестку заголовка и табличной части справочника
     */
    public function getFormForParent($CreateBottom = false)
    {
        $this->init();
        $data = $this->MODEL->getData();

        $this->VIEW->setDataGridObject($data);
        $this->VIEW->createHeadWindow();
        if ($CreateBottom !== false) {
            $this->VIEW->createBottomWindowEdit();
        }

        $this->VIEW->mainWindow();
        $ret = $this->VIEW->windowContent . $this->VIEW->includeHtmlFilter();
        return $ret;
    }

    public function set_NO_clearEditDiv($value = false)
    {
        $this->VIEW->set_NO_clearEditDiv($value);
    }

    public function init()
    {
    }

    /**
     * ////////////////////////////////////////////////////////
     * Для взаимодействия со справочником из внешних модулей
     * ////////////////////////////////////////////////////////
     *
     * @return string Возвращает HTML вестку только таблчной части
     */
    public function getFilterAndGreedForParent()
    {
        $this->init();
//        $this->defineTable();
        $data = $this->MODEL->getData();
        $this->VIEW->setDataGridObject($data);
        $this->VIEW->createHeadWindow();

        return $this->VIEW->windowContent;
    }

    /**
     * ////////////////////////////////////////////////////////
     * Для взаимодействия со справочником из внешних модулей
     * ////////////////////////////////////////////////////////
     *
     * @return string Возвращает HTML вестку только таблчной части
     */
    public function getGreedForParent()
    {
        $this->init();
//        $this->defineTable();
        $data = $this->MODEL->getData();
        return $this->VIEW->greed($data);
    }

    /**
     * ////////////////////////////////////////////////////////
     * Для взаимодействия со справочником из внешних модулей
     * ////////////////////////////////////////////////////////
     *
     * @param $callFunction_txt
     * // форма выбора для внешнего вызова в дальнейшем из HTML вызывается метод  для получения таблицы
     */
    public function formForSelect($callFunction_txt)
    {
        $this->init();
        $this->defineTable();
        $data = $this->MODEL->getData();
        $this->VIEW->formSelect = true;
        $this->VIEW->setDataGridObject($data);
        $this->VIEW->createHeadWindow();
        $this->VIEW->setCallFunctionTxt($callFunction_txt);
        $this->VIEW->createBottomWindowSelect();
        $this->VIEW->mainWindow();
        $this->VIEW->autoCenterBlock();
        $this->VIEW->printMainWindow();
    }

    /**
     * ////////////////////////////////////////////////////////
     * Для взаимодействия со справочником из внешних модулей
     * ////////////////////////////////////////////////////////
     *
     * @param $callFunction_txt
     * // форма выбора для внешнего вызова в дальнейшем из HTML вызывается метод  для получения таблицы
     */
    public function formForGreedColumn($callFunction_txt)
    {
        $nameIdCell = $_REQUEST['nameIdCell'];
        $idRowInGreed = $_REQUEST['idRowInGreed'];
        $old_idSpr = $_REQUEST['old_idSpr'];
//        $old_captionSpr = $_REQUEST['old_captionSpr'];
        $callBackFunction = $_REQUEST['callBackFunction'];
        $nameIdGreedTable = $this->VIEW->nameGreedDIV;
        $this->init();
        $this->defineTable();
        $this->setFormWidth($_REQUEST['widthColumn']);
        $data = $this->MODEL->getData();
        $this->VIEW->formSelect = true;
        $this->VIEW->setVisibleHeadTable(false);
        $this->VIEW->setDataGridObject($data);
        $this->VIEW->setCallFunctionTxt($callFunction_txt);
        $this->VIEW->setArrayChecked(array($old_idSpr));
        $this->VIEW->setLayer(2);
        $this->VIEW->setOnClickFunctionForAllTable("_GREED_callSprInCell_answer_1(\"$nameIdCell\",\"$idRowInGreed\",\"$callBackFunction\",\"$nameIdGreedTable\")");
        $this->VIEW->windowContent =  $this->VIEW->divGreed();
        $this->VIEW->printMainWindowForGreedColumn();

    }

    /**
     * ////////////////////////////////////////////////////////
     * Для взаимодействия со справочником из внешних модулей выводится в фильтре
     * ////////////////////////////////////////////////////////
     *
     * // форма выбора для внешнего вызова в дальнейшем из HTML вызывается метод  для получения таблицы
     */
    public function formForFilterBTN()
    {

        $name_BTN = $_REQUEST['name_BTN'];
        $_SESSION['width_BTN'] = $_REQUEST['width_BTN'];
        $this->init();
        $this->defineTable();
        $this->setFormWidth($_REQUEST['width_BTN']);
        $refresh_func = $_REQUEST['refresh_func'];
        $data = $this->MODEL->getData();
        $this->VIEW->setDataGridObject($data);
        if ($_REQUEST['checked'] != ''){ // если чтото имеется то преобразуем в массив
            $arrayChecked = json_decode($_REQUEST['checked']);
            $this->VIEW->setArrayChecked($arrayChecked);
        }

        $this->VIEW->windowContent = $this->VIEW->filterBlockForFilterBTN();
        $this->VIEW->windowContent = $this->VIEW->windowContent. $this->VIEW->divGreed();
        $this->VIEW->createBottomWindowSelectForFilterBTN($name_BTN,$refresh_func);
        $this->VIEW->printMainWindowForFilterBTN();
    }

    public function defineTable()
    {
    }

    public function getTableName()
    {
        $this->defineTable();
        return $this->MODEL->tableForEdit;
    }
    /**
     * ////////////////////////////////////////////////////////
     * Для взаимодействия со справочником из внешних модулей
     * ////////////////////////////////////////////////////////
     *
     * // форма выбора для внешнего вызова в дальнейшем из HTML вызывается метод  для получения таблицы
     */
    public function getIdMainGreed()
    {
        return $this->VIEW->nameGreed;
    }

    /**
     * ////////////////////////////////////////////////////////
     * Для взаимодействия со справочником из внешних модулей
     * ////////////////////////////////////////////////////////
     *
     * Отвечает за фильтрацию табличной части и вывод на frontPage
     */
    public function getListFilter()
    {
        $this->init();
        $filter = json_decode($_REQUEST['filter'], true);
        $this->MODEL->setFilter($filter);
        $data = $this->MODEL->getData();
        $this->VIEW->printElement($this->VIEW->Greed($data));
    }


    /**
     * ////////////////////////////////////////////////////////
     * Для взаимодействия со справочником из внешних модулей
     * ////////////////////////////////////////////////////////
     *
     * Отвечает за фильтрацию табличной части и вывод на frontPage
     * вызывается в момент когда нужно офильровать большой список задающего фильтра
     */
    public function getListFilter_filterBTN()
    {
        $this->init();
        $this->defineTable();
        $this->setFormWidth($_SESSION['width_BTN']);
        $filter = json_decode($_REQUEST['filter'], true);
        $this->MODEL->setFilter($filter);

        $data = $this->MODEL->getData();
        $this->VIEW->allInsertOff = false;
        $this->VIEW->printElement($this->VIEW->Greed($data));
    }
    ////
    // Устанавливает поле для идентификации записи в таблице, если оно не "ID"
    //

    /**
     * ////////////////////////////////////////////////////////
     * Для взаимодействия со справочником из внешних модулей
     * ////////////////////////////////////////////////////////
     *
     * Отвечает за фильтрацию табличной части и вывод на frontPage
     */
    public function getTXT_headSmallTitle()
    {
        return $this->VIEW->TXT_headSmallTitle;
    }

    /**
     * ////////////////////////////////////////////////////////
     * Для взаимодействия со справочником из внешних модулей
     * ////////////////////////////////////////////////////////
     *
     * Запучскает формирование формы для редактирования
     */
    public function editRow()
    {
        $_SESSION['idRowForEditInToSPR'] = $_GET['idRowForEditInToSPR'];
        $this->init();
        $this->prefixFor_editRow();
        $this->refreshEditElements();
    }
    public function prefixFor_editRow(){}



    /**
     * ////////////////////////////////////////////////////////
     * Для взаимодействия со справочником из внешних модулей
     * ////////////////////////////////////////////////////////
     *
     * удалает указанную строку
     */
    public function deleteRow()
    {
        $this->init();
        $_SESSION['idRowForEditInToSPR'] = $_GET['idRowForEditInToSPR'];
        $this->setFilter($this->ColumnID, $_SESSION['idRowForEditInToSPR']);
        $this->MODEL->deleteRow();
        $this->refreshEditElements();
    }

    /**
     * ////////////////////////////////////////////////////////
     * Для взаимодействия со справочником из внешних модулей
     * ////////////////////////////////////////////////////////
     *
     * Устанавливает функцию для двойному клику по гриду
     */
    public function setOnDblClickFunctionForAllTable($onClickFunctionForAllTable)
    {
        $this->VIEW->setOnDblClickFunctionForAllTable($onClickFunctionForAllTable);
        return $this;
    }

    /**
     * ////////////////////////////////////////////////////////
     * Для взаимодействия со справочником из внешних модулей
     * ////////////////////////////////////////////////////////
     *
     * Устанавливает функцию для клику по гриду
     */
    public function setOnClickFunctionForAllTable($onClickFunctionForAllTable)
    {
        $this->VIEW->setOnClickFunctionForAllTable($onClickFunctionForAllTable);
        return $this;
    }

    public function setFilterElementsOff()
    {
        $this->VIEW->filterElementsON = false;
        return $this;
    }

    public function setBlockNum($blockNum)
    {
        $this->VIEW->setBlockNum($blockNum);
    }

    public function refreshEditElements()
    {
        $this->init();
        $this->setFilter($this->getColumnID(), $_SESSION['idRowForEditInToSPR']);
        $data_array = $this->MODEL->getData()->fetch();

        //$this->VIEW->setMODEL($this->MODEL);
        $this->VIEW->setRowDataForEdit($data_array);
        $HTML = $this->VIEW->createElementsEdit();
        $this->VIEW->printElement($HTML);
    }

    /**
     * @param string $field поле таблици
     * @param string $value значение(фильтр)
     * @param string $znak знак ббольше меньше и т.д.
     */
    public function setFilter($field, $value, $znak = "=")
    {
        $rrow = Array();
        $rrow['field'] = $field;
        $rrow['value'] = $value;
        $rrow['znak'] = $znak;
        $this->MODEL->filter[] = $rrow;
    }

    public function setColumnID($ColumnID)
    {
        $this->ColumnID = $ColumnID;
        $this->VIEW->ColumnID = $ColumnID;

    }

    public function addData()
    {
        // varName
        // value

        $this->init();
        //$this->setFilter('id',$_SESSION['idRowForEditInToSPR']);
        $variables = json_decode($_REQUEST['variables'],true);
        $_SESSION['idRowForEditInToSPR'] = $this->MODEL->addData($variables);
        $this->refreshEditElements();
    }

    public function replaceValue()
    {
        $this->init();
        $this->defineTable();
        $this->setFilter($this->ColumnID, $_SESSION['idRowForEditInToSPR']);
        $variables = json_decode($_REQUEST['variables'],true);
        $this->MODEL->replaceValue($variables);
        $this->refreshEditElements();
    }

    public function setFilterGlobal($field, $value, $znak = "=")
    {
        $rrow = Array();
        $rrow['field'] = $field;
        $rrow['value'] = $value;
        $rrow['znak'] = $znak;
        $this->MODEL->filterGlobal[] = $rrow;
        $this->VIEW->filterGlobal[] = $rrow;
    }

    /**
     * @param bool $allInsertOff устанавливает можно ди будет выбирать все позиции справочника одновременно
     */
    public function setAllInsertOff(bool $allInsertOff)
    {
        $this->allInsertOff = $allInsertOff;
        $this->VIEW->allInsertOff = $this->allInsertOff;
    }

    public function prepareData()
    {
        $report = new  \models\Reports();
        $report->setWait($_REQUEST['wait']);
        $report->prepareReport($_REQUEST['report']);
        $manageTable = $_REQUEST['manageTable'];
        $this->MODEL->prepareData($report,$manageTable);

        $report->runCreateReport();

        print $report->getGUIDReport();
    }

    public function setTOP($TOP)
    {
        $this->MODEL->TOP = $TOP;
    }

    public function includeHtml()
    {
        return $this->VIEW->includeHtml();
    }
}