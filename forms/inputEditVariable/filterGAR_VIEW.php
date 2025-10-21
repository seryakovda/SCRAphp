<?php


namespace forms\inputEditVariable;


class filterGAR_VIEW extends \forms\FormView
{
    private $dataArray;

    private $idInput;

    /**
     * @param mixed $idInput
     */
    public function setIdInput($idInput): void
    {
        $this->idInput = $idInput;
    }


    /**
     * @param mixed $dataArray
     */
    public function setDataArray($dataArray): void
    {
        $this->dataArray = $dataArray;
    }

    public function area()
    {
        return $this->area_to_town();
    }

    public function district()
    {
        return $this->area_to_town();
    }

    public function town()
    {
        return $this->area_to_town();
    }

    public function street()
    {
        return $this->area_to_town();
    }


    public function area_to_town()
    {
        $HTML = '';
        $num = 1;
        foreach ($this->dataArray as $key => $item){
            $caption = "{$item['TYPENAME_1']} {$item['NAME_1']}, {$item['TYPENAME']} {$item['NAME']}";
            $HTML .= $this->BTN->set($caption)
                ->nameId('btnList_'.$num)
                ->class_('btnList')
                ->height(15)->width(520)
                ->floateLeft()
                ->horizontalPosLeft()
                ->marginBottomOff()
                ->func("SelectFromListGAR('{$item['PATH']}','{$this->idInput}','{$item['NAME']}')")
                ->fontSmall()
                //->nameId(\models\ControlElements::get()->getNameMethod($this->objectFullName, __METHOD__))
                ->get();
            $num ++;
        }
        return $HTML;
    }

    public function house()
    {
        $HTML = '';
        $num = 1;
        foreach ($this->dataArray as $key => $item){
            $caption = "{$item['NAME']}";
            $HTML .= $this->BTN->set($caption)
                ->nameId('btnList_'.$num)
                ->class_('btnList')
                ->height(15)->width(520)
                ->floateLeft()
                ->horizontalPosLeft()
                ->marginBottomOff()
                ->func("SelectHouseRoom('{$item['OBJECTID']}','{$this->idInput}','{$item['NAME']}')")
                ->fontSmall()
                //->nameId(\models\ControlElements::get()->getNameMethod($this->objectFullName, __METHOD__))
                ->get();
            $num ++;
        }
        return $HTML;
    }
    public function room()
    {
        return $this->house();
    }
}