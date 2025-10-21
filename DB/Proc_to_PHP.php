<?php
namespace DB;


use Properties\Security;

class Proc_to_PHP extends ObjectDB_tot_PHP
{
    public function  createHandleFile()
    {
        fwrite($this->fileHandle,"<?php\r\n\r\n");
        fwrite($this->fileHandle,"namespace DB\\{$this->typeObject};\r\n\r\n");

        fwrite($this->fileHandle,"class $this->objectDB extends \DB\SQL\\$this->typeObject\\$this->objectDB\r\n");
        fwrite($this->fileHandle,"{\r\n");
        // построение заголовка процедуры и формирования параметров
        $txt = "    public function parameters(";
        foreach ($this->columns as $key => $column){
            if ($column == "NoParam")
                $column = $column . " = 0";
            $txt .= "$$column,";
        }
        $txt = substr($txt,0,-1);

        $txt .= ")";
        fwrite($this->fileHandle,"$txt\r\n");
        fwrite($this->fileHandle,"    {\r\n");
        fwrite($this->fileHandle,'        return $this'."\r\n");

    }

    public function addFieldsInToObject(string $ColumnName)
    {
        fwrite($this->fileHandle,chr(9)."            ->set('{$ColumnName}',$$ColumnName)\r\n");
    }

    public function  createEndFile()
    {
        if ($this->MSSQL == Security::TYPE_dB_MS_SQL)
            fwrite($this->fileHandle,"            ->SQLExec();\r\n");
        if ($this->MSSQL == Security::TYPE_dB_My_SQL)
            fwrite($this->fileHandle,"            ->SQLExec_MySQL();\r\n");
        fwrite($this->fileHandle,"    }\r\n");
        fwrite($this->fileHandle,"}\r\n");
        fclose($this->fileHandle);
    }
}
