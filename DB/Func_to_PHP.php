<?php
namespace DB;


use views\mPrint;

class Func_to_PHP extends ObjectDB_tot_PHP
{
    public function  createHandleFile()
    {
        fwrite($this->fileHandle,"<?php\r\n\r\n");
        fwrite($this->fileHandle,"namespace DB\\{$this->typeObject};\r\n\r\n\r\n");
        fwrite($this->fileHandle,"class $this->objectDB extends \DB\SQL\\$this->typeObject\\$this->objectDB\r\n");
        fwrite($this->fileHandle,"{\r\n");
        // построение заголовка процедуры и формирования параметров
        mPrint::R($this->columns);
        $txt = "    public function parameters(";
        foreach ($this->columns as $key => $column){
            $txt .= "$$column,";
        }
        $txt = substr($txt,0,-1);
        $txt .= ")";
        fwrite($this->fileHandle,"$txt\r\n");
        fwrite($this->fileHandle,"    {\r\n");
        fwrite($this->fileHandle,'        return false;'."\r\n");

    }

    public function  createEndFile()
    {

        fwrite($this->fileHandle,"    }\r\n");
        fwrite($this->fileHandle,"}\r\n");
        fclose($this->fileHandle);
    }
}
