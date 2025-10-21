<?php


namespace DB;
/**
 * Class Type
 * @package DB
 * типы полей использумых в создании таблиц
 */

class Type
{
    const int = 'int';

    const bigint = 'bigint';

    const money = 'money';

    const nvarchar  = 'nvarchar';

    const ntext = 'ntext';

    const text = 'text';

    const varchar  = 'varchar';

    const date = 'date';

    const datetime = 'datetime';

    const image = 'image';

    const tinyint = 'tinyint';

    const longtext = 'longtext'; // MySQL

    const mediumtext = 'mediumtext'; // MySQL

    const blob = 'blob'; // MySQL

    const mediumblob = 'mediumblob'; // MySQL


    const uniqueidentifier = 'uniqueidentifier';
    /**
     * Возвращает false для типов без определения размера, либо указанный размер
     * @param $type
     * @param $size
     * @return false|mixed
     */
    static function getSizeFalseForType($type,$size)
    {
        switch ($type){
            case self::int:
            case self::bigint:
            case self::tinyint:
            case self::money:
            case self::date:
            case self::datetime:
            case self::image:
            case self::uniqueidentifier:
            case self::longtext: // MySQL
            case self::mediumtext: // MySQL
            case self::blob: // MySQL
            case self::mediumblob: // MySQL
                return false;

            default :
                return $size;
        }

    }
}

