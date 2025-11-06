<?php

namespace DB\Table;

use \DB\Type;

class API_keys extends \DB\Table
{
    const key_ =  'key_';

    const name = 'name';

    const id_user = 'id_user';

    const modelExtension = 'modelExtension';

    public function initColumn($childClass = '')
    {
        // обязательно для формирования структуры массива
        parent::initColumn(__CLASS__);

        $this->declare_primaryIndex($this::key_);

        $this->declare_type($this::key_,            Type::varchar,36);
        $this->declare_type($this::name,            Type::varchar,200);
        $this->declare_type($this::modelExtension,  Type::varchar,200);
        $this->declare_type($this::id_user,         Type::int);

    }

    public function defaultRows()
    {
        return array(
            array(
                $this::key_             => '00BA4EEB-ABF1-4A33-AEAD-93D1869D3174',
                $this::name             => 'Мобильный переносной считыватель',
                $this::modelExtension   => '_mobile_SCRA_01',
                $this::id_user          => 0,
            ),

        );
    }
}
