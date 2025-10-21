<?php
/**
 * Created by PhpStorm.
 * User: rezzalbob
 * Date: 23.11.2020
 * Time: 20:47
 */

namespace models\Standards;


use models\Standards\TypeFilterInput;

/**
 * Класс определяющий стандарты рабты со справочником
 * Class ViewStandardFields
 * @package forms
 */
class FieldsForView
{
    private $arrayColumns = Array();
    private $columnName;
    private $useFilterOnly;


    function __construct()
    {
        $this->useFilterOnly = false;

    }


    /**
     * в случае если имя новое происходитт его заполение стандартными значениями
     */
    private function init()
    {
        if (!array_key_exists($this->columnName, $this->arrayColumns)) {
            $this->arrayColumns[$this->columnName]['Caption'] = 'Имя поля';
            $this->arrayColumns[$this->columnName]['TypeFilterInput'] = TypeFilterInput::INPUT;
            $this->arrayColumns[$this->columnName]['WithForGreed'] = 150;
            $this->arrayColumns[$this->columnName]['InToFilter'] = true;
            $this->arrayColumns[$this->columnName]['Column'] = true;
            $this->arrayColumns[$this->columnName]['WithForFilter'] = 150;
            $this->arrayColumns[$this->columnName]['HeightForFilter'] = 40;
            $this->arrayColumns[$this->columnName]['PositionText'] = 'Column_horizontalPosLeft';

            $this->arrayColumns[$this->columnName]['TypeColumn']['name'] = false;
            $this->arrayColumns[$this->columnName]['TypeColumn']['pattern'] = false;
            $this->arrayColumns[$this->columnName]['InputPattern'] = false;
            $this->arrayColumns[$this->columnName]['Condition']['sign'] = '=';
            $this->arrayColumns[$this->columnName]['Condition']['prefix'] = '';
            $this->arrayColumns[$this->columnName]['Condition']['postfix'] = '';
            $this->arrayColumns[$this->columnName]['Column_TypeInput'] = false;
            $this->arrayColumns[$this->columnName]['Function_For_BlurEvent'] = false;
            $this->arrayColumns[$this->columnName]['onKeyUpFunction'] = false;
            $this->arrayColumns[$this->columnName]['dynamicBackgroundColor']= Array();
            $this->arrayColumns[$this->columnName]['dynamicForeColor']= Array();
            $this->arrayColumns[$this->columnName]['OnkeyupGreed'] = false;
            $this->arrayColumns[$this->columnName]['Title'] = false;
            $this->arrayColumns[$this->columnName]['HeadFunction'] = false;
            $this->arrayColumns[$this->columnName]['Focus_IN'] = false;


        }
    }

    /**
     * устанавливат дополнительныю функцию срабатывающую при получении фокуса поля филтра
     * @param $nameFunction
     * @return $this
     */
    public function setFocus_IN($nameFunction)
    {
        $this->arrayColumns[$this->columnName]['Focus_IN'] = $nameFunction;
        return $this;
    }

    /**
     * возвращает имя функции или false
     * @return mixed
     */
    public function getFocus_IN()
    {
        return $this->arrayColumns[$this->columnName]['Focus_IN'];
    }



    /**
     * при смещении ввех или низ выполняется реакция на стороне браузера т выполняется _G_reactionToRightArrow с параметрми
     * @param $objectName
     */
    public function reactionForDownArrow($objectName)
    {
        $firstIndex = false;
        $firstIndex2 = false;
        $index1 = false;
        $index2 = false;
        $index3 = false;
        $index = 1;

        foreach ($this->arrayColumns as $key => $item){
            if ($item['InToFilter'] !== false){ // если элемент участвует в фильтрации

                if ($firstIndex === false) // если первый индекс ещё не заполнен
                    $firstIndex = $key;

                $index3 = $index2;
                $index2 = $index1;
                $index1 = $key;

                if ( ($index2 !== false) && ($firstIndex2 === false) )
                    $firstIndex2 = $key;

                if ($index3 !== false){
                    $this->arrayColumns[$index2]['onKeyUpFunction'] = '_G_reactionToRightArrow("#' . $index1 . '_' . $objectName . '","#' . $index3 . '_' . $objectName . '",event)';
                }
            }
        }

        $this->arrayColumns[$firstIndex]['onKeyUpFunction'] = '_G_reactionToRightArrow("#' . $firstIndex2 . '_' . $objectName . '","#' . $index1 . '_' . $objectName . '",event)';

        $this->arrayColumns[$index1]['onKeyUpFunction'] = '_G_reactionToRightArrow("#' . $firstIndex . '_' . $objectName . '","#' . $index2 . '_' . $objectName . '",event)';

    }

    /**
     * говорит о том что в данном поле будут выводиться картинки согласно списка через точку с запятой
     * @param string $request
     * @param int $size
     * @param bool $square
     * @return $this
     */
    public function setTypeColumn_ListImage($request = '',
                                            $size = 50,
                                            $square = true)
    {
        $this->arrayColumns[$this->columnName]['Column_TypeInput'] = 'Column_ListImage';
        $this->arrayColumns[$this->columnName]['Column_ListImage_size'] = $size;
        $this->arrayColumns[$this->columnName]['Column_ListImage_square'] = $square;
        $this->arrayColumns[$this->columnName]['Column_ListImage_request'] = $request;
        return $this;
    }

    public function setHeadFunction($nameFunction)
    {
        $this->arrayColumns[$this->columnName]['HeadFunction'] = $nameFunction;
        return $this;
    }

    public function getHeadFunction()
    {
        return $this->arrayColumns[$this->columnName]['HeadFunction'];
    }

    public function getTypeColumn_ListImage_size()
    {
        return $this->arrayColumns[$this->columnName]['Column_ListImage_size'];
    }

    public function getTypeColumn_ListImage_square()
    {
        return $this->arrayColumns[$this->columnName]['Column_ListImage_square'];
    }

    public function getTypeColumn_ListImage_request()
    {
        return $this->arrayColumns[$this->columnName]['Column_ListImage_request'];
    }


    /**
     * назначает тип ввода данных для фильтрации простое текстовое поля либо вызываемый справичник для выбора позиций
     * @param $nameClass string имя класса который долже быть вызван
     * @return $this
     */
    public function setTypeFilterInput_BTN_SPR(string $nameClass)
    {
        $this->arrayColumns[$this->columnName]['TypeFilterInput'] = TypeFilterInput::BTN_SPR;
        $this->arrayColumns[$this->columnName]['TypeFilterInput_nameClass'] = $nameClass;
        return $this;
    }


    public function getTypeFilterInput()
    {
        return $this->arrayColumns[$this->columnName]['TypeFilterInput'];
    }


    public function getClassTypeFilterInput()
    {
        return $this->arrayColumns[$this->columnName]['TypeFilterInput_nameClass'];
    }


    /**
     * @param $field string имя поля которое будет отображаться при наведении мышки на поле
     * @return $this
     */
    public function setTitleField(string $field):object
    {
        $this->arrayColumns[$this->columnName]['Title'] = $field;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitleField()
    {
        return $this->arrayColumns[$this->columnName]['Title'];
    }


    /**
     * @param string $function_and_parameters
     * Устанавливает на событие Onkeyup элемента Input указанную функцию
     * по умолчнию  используется eventGreed(this,event) которая обеспечивает навигацию по полям таблици
     * с помошью. клавиш вверх вниз и Enter
     * @return object
     */
    public function SetOnkeyup_functionForInputGreed(string $function_and_parameters):object
    {
        $this->arrayColumns[$this->columnName]['OnkeyupGreed'] = $function_and_parameters;
        return $this;
    }

    /**
     * возвращается либо функциия илибо false В блоке Input в гриде
     * @return string|bool
     */
    public function GetOnkeyup_functionForInputGreed() :string|bool
    {
        return $this->arrayColumns[$this->columnName]['OnkeyupGreed'];
    }


    /**
     * @param $if
     * @param $color
     * setDynamicBackgroundColor('$ROW["tribunal"]!=0', '#a52a4c70')
     * tribunal => Это имя столбца таблицы по которому выполняется условие
     * можно добавлять цвет из поля в обрабатываемофй таблици
     * setDynamicBackgroundColor('$ROW["tribunal"]!=0', '@ИмяПоля')
     * * @return $this
     */
    public function setDynamicBackgroundColor($condition,$hexColor) :object
    {
        $arr = array('condition' => $condition, 'hexColor' => $hexColor);
        $this->arrayColumns[$this->columnName]['dynamicBackgroundColor'][] = $arr;
        return $this;
    }

    /**
     * @param $if
     * @param $color
     * setDynamicForeColor('$ROW["tribunal"]!=0', '#a52a4c70')
     * tribunal => Это имя столбца таблицы по которому выполняется условие
     * можно добавлять цвет из Теста в обрабатываемофй таблици
     * setDynamicBackgroundColor('$ROW["tribunal"]!=0', '@ИмяПоля')
     * * @return $this
     */
    public function setDynamicForeColor($condition,$hexColor) :object
    {
        $arr = array('condition' => $condition, 'hexColor' => $hexColor);
        $this->arrayColumns[$this->columnName]['dynamicForeColor'][] = $arr;
        return $this;
    }


    /**
     * возвращает условие дял динамичного изменение цвета фона ячейки
     * @return string|bool
     */
    public function getDynamicBackgroundColor_if() :string|bool
    {
        if (count($this->arrayColumns[$this->columnName]['dynamicBackgroundColor']) == 0)
            return false;
        else
            return true;
    }

    /**
     * возвращает условие дял динамичного изменение цвета текста ячейки
     * @return string|bool
     */
    public function getDynamicForeColor_if() :string|bool
    {
        if (count($this->arrayColumns[$this->columnName]['dynamicForeColor']) == 0)
            return false;
        else
            return true;
    }


    /**
     * возвращает щеснатеричное значение цвета фона в виде текста
     * @return array|bool
     */
    public function getDynamicBackgroundColor_color():array|bool
    {
        return $this->arrayColumns[$this->columnName]['dynamicBackgroundColor'];
    }


    /**
     * возвращает щеснатеричное значение цвета текста в виде текста
     * @return array|bool
     */
    public function getDynamicForeColor_color():array|bool
    {
        return $this->arrayColumns[$this->columnName]['dynamicForeColor'];
    }


    /**
     * устаноавливает текстовое значение имени функуии выполняющейся на стороне браузера возможно с параметрами
     * при условии отпускания клавиши в ячейке
     * например '_G_reactionToRightArrow("#im_FIO","#personnel_number_FIO",event)'
     * @param string $onKeyUpFunction
     * @return $this
     */
    public function setOnKeyUpFunction(string $onKeyUpFunction) :object
    {
        $this->arrayColumns[$this->columnName]['onKeyUpFunction']  = $onKeyUpFunction;
        return $this;
    }



    /**
     * возвращает текстовое значение имени функуии выполняющейся на стороне браузера возможно с параметрами
     * при условии отпускания клавиши В блоке фильтров
     * @return string|bool
     */
    public function getOnKeyUpFunction() :string|bool
    {
        return $this->arrayColumns[$this->columnName]['onKeyUpFunction'];
    }



    /**
     * устаноавливает текстовое значение имени функуии выполняющейся на стороне браузера возможно с параметрами
     * при условии теряет фокуса ячейкой
     * function functionName(this,event)
     * @param $functionName
     * @return $this
     */
    public function setFunction_For_BlurEvent($functionName):object
    {
        $this->arrayColumns[$this->columnName]['Function_For_BlurEvent'] = $functionName;
        return $this;
    }



    /**
     * возвращает текстовое значение имени функуии выполняющейся на стороне браузера возможно с параметрами
     * при условии теряет фокуса ячейкой
     * @return string|bool
     */
    public function getFunction_For_BlurEvent() :string|bool
    {
        return $this->arrayColumns[$this->columnName]['Function_For_BlurEvent'];
    }



    /**
     * устанавливает шаблон ввода данных для функции input в ячейке
     * @param false $pattern
     * @return $this
     */
    public function setInputPattern($pattern = false):object
    {
        $this->arrayColumns[$this->columnName]['InputPattern'] = $pattern;
        return $this;
    }

    /**
     * возвращает шаблон ввода данных для функции input в ячейке
     * @return string|bool
     */
    public function getInputPattern():string|bool
    {
        return $this->arrayColumns[$this->columnName]['InputPattern'] ;
    }


    /**
     * @param string $_path_name_img путь к изоброажению которое будет выведено в ячейку
     * @param string $_path_name_callObject путь и имя объекта который будет вызван для модификации поля (ApplicationSMTS\\\\SPR_ORG)
     * @param string $_callFunction имя функции при вызове которой произойдёт модивикация поля (должно принимать ID, и newValue)
     * @param string $_displayField имя поля которое будет отображаться
     * @return object
     */
    public function setTypeColumn_buttonImg(string $_path_name_img,string $_path_name_callObject,string $_callFunction, string $_displayField):object
    {
        $this->arrayColumns[$this->columnName]['Column_TypeInput'] = 'Column_TypeButtonImg';
        $this->arrayColumns[$this->columnName]["BTN_path_name_img"] = $_path_name_img;
        $this->arrayColumns[$this->columnName]["BTN_path_name_callObject"] = $_path_name_callObject;
        $this->arrayColumns[$this->columnName]["BTN_callFunction"] = $_callFunction;
        $this->arrayColumns[$this->columnName]["BTN_displayField"] = $_displayField;
        return $this;
    }

    public function getTypeColumn_buttonImg_path_name_img():string
    {
        return $this->arrayColumns[$this->columnName]["BTN_path_name_img"];
    }
    public function getTypeColumn_buttonImg_name_callObject():string
    {
        return $this->arrayColumns[$this->columnName]["BTN_path_name_callObject"];
    }
    public function getTypeColumn_buttonImg_callFunction():string
    {
        return $this->arrayColumns[$this->columnName]["BTN_callFunction"];
    }
    public function getTypeColumn_buttonImg_displayField():string
    {
        return $this->arrayColumns[$this->columnName]["BTN_displayField"];
    }

    /**
     * определяет тип ячейки как поле для ввода
     * @return $this
     */
    public function setTypeColumn_Input():object
    {
        $this->arrayColumns[$this->columnName]['Column_TypeInput'] = 'Column_TypeInput';
        return $this;
    }



    /**
     * поределяет ячейку как отдельный check для отображения битовых полей
     * @return $this
     */
    public function setTypeColumn_Check():object
    {
        $this->arrayColumns[$this->columnName]['Column_TypeInput'] = 'Column_TypeCheck';
        return $this;
    }


    /**
     * возвращает тип ячейки
     * @return string|bool
     */
    public function getTypeColumn_Input():string|bool
    {
        return $this->arrayColumns[$this->columnName]['Column_TypeInput'];
    }


    /**
     * устанавливает выравнивание содержимого по центру в нутри ячейки
     * @return $this
     */
    public function setHorizontalPosCenter():object
    {
        $this->arrayColumns[$this->columnName]['PositionText'] = 'Column_horizontalPosCenter';
        return $this;
    }


    /**
     * устанавливает выравнивание содержимого по правому краю в нутри ячейки
     * @return $this
     */
    public function setHorizontalPosRight():object
    {
        $this->arrayColumns[$this->columnName]['PositionText'] = 'Column_horizontalPosRight';
        return $this;
    }


    /**
     * возвращает тип выравнивания содержимого в нутри ячейки
     * @return string
     */
    public function getHorizontalPos():string
    {
        return $this->arrayColumns[$this->columnName]['PositionText'];
    }



    /**
     * Устанавливает тип ячейки - Дата с предустановленным шаблоном
     *
     * @param string $pattern
     * @return $this
     */
    public function setTypeColumn_Data($pattern = 'd.m.Y'):object
    {
        $this->arrayColumns[$this->columnName]['TypeColumn']['name'] = 'Column_date_format';
        $this->arrayColumns[$this->columnName]['TypeColumn']['pattern'] = $pattern;
        return $this;
    }



    /**
     * Устанавливает тип ячейки - Числовой
     *
     * @param int $pattern
     * @return $this
     */
    public function setTypeColumn_Number(int $pattern = 2):object
    {
        $this->arrayColumns[$this->columnName]['TypeColumn']['name'] = 'Column_number_format';
        $this->arrayColumns[$this->columnName]['TypeColumn']['pattern'] = (int)$pattern;
        return $this;
    }



    /**
     * возвращает тип ячейки
     * @return string|bool
     */
    public function getTypeColumn():string|bool
    {
        return $this->arrayColumns[$this->columnName]['TypeColumn']['name'];
    }



    /**
     * возвращает шаблон типа ячейки
     * @return string
     */
    public function getPatternColumn():string|bool
    {
        return $this->arrayColumns[$this->columnName]['TypeColumn']['pattern'];
    }



    /**
     * Устанавлиывает имя поля, оно должно соответствовать полю базы данных
     * для которого сформируются настройки
     *
     * @param $columnName
     * @return $this
     */
    public function setName($columnName):object
    {
        $this->columnName = $columnName;
        $this->init();
        return $this;
    }



    /**
     * указывает не использовать поле для построения фильтра
     *
     * @return $this
     */
    public function setNoFilter():object
    {
        $this->arrayColumns[$this->columnName]['InToFilter'] = false;
        return $this;
    }



    /**
     * указывает не использовать поле для построения колонки грида
     *
     * @return $this
     */
    public function setNoColumn():object
    {
        $this->arrayColumns[$this->columnName]['Column'] = false;
        return $this;
    }



    /**
     * Устанавливает текстовое название соответствующее имени базы данных для вывода пользователю
     *
     * @param $value
     * @return $this
     */
    public function setCaption($value):object
    {
        $this->arrayColumns[$this->columnName]['Caption'] = $value;
        return $this;
    }



    /**
     * Устанавливает по какомму признаку будет произведена фильтрация
     * прификс, постфикс и знак раветсва < > != = like %like% $like like%
     *
     * @param string $sign
     * @param string $prefix
     * @param string $postfix
     * @return $this
     */
    public function setCondition($sign = '=', $prefix = '', $postfix = ''):object
    {
        $this->arrayColumns[$this->columnName]['Condition']['sign'] = $sign;
        $this->arrayColumns[$this->columnName]['Condition']['prefix'] = $prefix;
        $this->arrayColumns[$this->columnName]['Condition']['postfix'] = $postfix;
        return $this;
    }



    /**
     * Устанавливает ширину колонки для грида
     *
     * @param int $value
     * @return $this
     */
    public function setWithForGreed(int $value):object
    {
        $this->arrayColumns[$this->columnName]['WithForGreed'] = $value;
        return $this;
    }


    /**
     * Устанавливает ширину вводимого поля дря фильтра
     *
     * @param int $value
     * @return $this
     */
    public function setWithForFilter(int $value):object
    {
        $this->arrayColumns[$this->columnName]['WithForFilter'] = $value;
        return $this;
    }


    /**
     * Устанавливает высту для полия фильтра
     *
     * @param int $value
     * @return $this
     */
    public function setHeightForFilter(int $value):object
    {
        $this->arrayColumns[$this->columnName]['HeightForFilter'] = $value;
        return $this;
    }



    /**
     * Сбрасывает указатель считывания из массива на начало
     * Сбрасывает настройку считываания для фильтра по умолчанию
     * @return bool
     */
    public function resetFetchName():bool
    {
        $this->useFilterOnly = false;
        reset($this->arrayColumns);
        if (count($this->arrayColumns) == 0) return false;
        else return true;
    }


    /**
     * возвращает имя поля для формирования грида либо false в случае окончания чтения данных
     * @return false|int|string|null
     */
    public function fetchName():false|int|string|null
    {

        $this->columnName = key($this->arrayColumns);
        if (($this->columnName == "") || ($this->columnName == null)){
            next($this->arrayColumns);
            return false;
        }

        //пропускаем все поля не для грида
        while ($this->arrayColumns[$this->columnName]['Column'] === false) {
            next($this->arrayColumns);
            $this->columnName = key($this->arrayColumns);
            if ($this->columnName === false) break;
        };
        next($this->arrayColumns);
        return $this->columnName;
    }



    /**
     * возвращает имя поля для формирования фильтра
     * @return false|int|string|null
     */
    public function fetchNameForFilter():false|int|string|null
    {

        $this->columnName = key($this->arrayColumns);
        if (($this->columnName == "") || ($this->columnName == null)){
            next($this->arrayColumns);
            return false;
        }

        //пропускаем все поля не для фильтра

        while ($this->arrayColumns[$this->columnName]['InToFilter'] === false) {
            next($this->arrayColumns);
            $this->columnName = key($this->arrayColumns);
            if (($this->columnName == "") || ($this->columnName == null) || ($this->columnName === false)) break;
        };
        next($this->arrayColumns);

        if (($this->columnName == "") || ($this->columnName == null) || ($this->columnName === false)){
            return false;
        }
        return $this->columnName;

    }


    /**
     * Возвращает полностью сформированный массив стандартов
     *
     * @return array
     */
    public function getFullArrayStandard():array
    {
        return $this->arrayColumns;
    }


    /**
     * Возвращает текущуее название поля
     *
     * @return string
     */
    public function getCaption():string
    {
        return $this->arrayColumns[$this->columnName]['Caption'];
    }


    /**
     * Возвращает текущее ширину для голонки нрида
     *
     * @return int
     */
    public function getWithForGreed():int
    {
        return $this->arrayColumns[$this->columnName]['WithForGreed'];
    }


    /**
     * Возвращает текущую ширину для элемента фильтра
     *
     * @return int
     */
    public function getWithForFilter():int
    {
        return $this->arrayColumns[$this->columnName]['WithForFilter'];
    }


    /**
     * Возвращает текущю ывсоту для элемента фильтра
     *
     * @return int
     */
    public function getHeightForFilter():int
    {
        return $this->arrayColumns[$this->columnName]['HeightForFilter'];
    }


    /**
     * Возвращает массив с условиями фильтрации
     *
     * @return array
     */
    public function getCondition():array
    {
        return $this->arrayColumns[$this->columnName]['Condition'];
    }
}