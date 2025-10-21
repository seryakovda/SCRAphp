<?php


namespace DB\Table;


use DB\Type;

class security_LOG extends \DB\Table
{

    const id_user =  'id_user';

    const id = 'id';

    const JSON_request = 'JSON_request';

    const datetimeStamp = 'datetimeStamp';

    public function initColumn($childClass = '')
    {
        // обязательно для формирования структуры массива
        parent::initColumn(__CLASS__);

        $this->identifierColumn($this::id);
        $this->declare_primaryIndex($this::id);

        $this->declare_type($this::id_user,     Type::bigint);
        $this->declare_type($this::id,          Type::int);
        $this->declare_type($this::JSON_request,Type::longtext);
        $this->declare_type($this::datetimeStamp,Type::datetime);

        $this->declare_defaultValue($this::datetimeStamp,'CURRENT_TIMESTAMP');


    }
}

/*
 2388init.js:491 <br />
<b>Fatal error</b>:  Uncaught Error: Class &quot;models\_G_session&quot; not found in /var/www/html/dev-scra/models/Router.php:278
Stack trace:
#0 /var/www/html/dev-scra/models/Router.php(114): models\Router-&gt;getRenewPassword()
#1 /var/www/html/dev-scra/index_ajax.php(27): models\Router-&gt;AppRun()
#2 {main}
  thrown in <b>/var/www/html/dev-scra/models/Router.php</b> on line <b>278</b><br />
 */