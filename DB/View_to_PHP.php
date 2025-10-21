<?php
namespace DB;


class View_to_PHP extends ObjectDB_tot_PHP
{
    public function  createHandleFile()
    {
        fwrite($this->fileHandle,"<?php\r\n\r\n");
        fwrite($this->fileHandle,"namespace DB\\{$this->typeObject};\r\n\r\n\r\n");
        fwrite($this->fileHandle,"use \DB\Connection;\r\n\r\n");
        fwrite($this->fileHandle,"class $this->objectDB extends \DB\SQL\\$this->typeObject\\$this->objectDB\r\n");
        fwrite($this->fileHandle,"{\r\n");

    }

    public function addFieldsInToObject(string $ColumnName)
    {

        fwrite($this->fileHandle,chr(9)."const {$ColumnName} =  '$ColumnName';\r\n\r\n");
    }
}
