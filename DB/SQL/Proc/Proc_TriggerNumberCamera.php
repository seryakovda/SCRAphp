<?php

namespace DB\SQL\Proc;

use DB\ObjectDB;
use Properties\Security;

class Proc_TriggerNumberCamera extends ObjectDB
{
    public function SQL()
    {
        $objectName = $this->getName();
        // отражался корректно
        $this->SQL_QUERY=/** @lang SQL */"
            CREATE PROCEDURE $objectName(IN _ipCamera VARCHAR(16))
            BEGIN
                DECLARE f INT DEFAULT 0;
                DECLARE count_val INT DEFAULT 0;
                DECLARE ip_list TEXT;
                
                -- Устанавливаем разделитель для разбивки строки
                SET ip_list = REPLACE(_ipCamera, ' ', '');
                
                -- Удаляем свои события
                IF _ipCamera IS NOT NULL AND _ipCamera != '' THEN
                    DELETE FROM DSSL_TRIGGER_Event 
                    WHERE FIND_IN_SET(ipCamera, ip_list) > 0;
                END IF;
                
                -- Проверяем сколько событий накопилось в общем
                SET f = (SELECT COALESCE(COUNT(*), 0) FROM DSSL_TRIGGER_Event);
                
                -- В случае если больше 50 то чистим таблицу
                IF f > 50 THEN
                    DELETE FROM DSSL_TRIGGER_Event;
                END IF;
                
                SET f = 0;
                SET count_val = 0;
                
                WHILE f = 0 DO
                    -- Задержка 333 мс (аналог WAITFOR DELAY)
                    DO SLEEP(0.333);
                    
                    -- Проверяем количество записей для указанных IP
                    IF _ipCamera IS NOT NULL AND _ipCamera != '' THEN
                        SET f = (
                            SELECT COALESCE(COUNT(*), 0) 
                            FROM DSSL_TRIGGER_Event 
                            WHERE FIND_IN_SET(ipCamera, ip_list) > 0
                        );
                    ELSE
                        SET f = 1; -- Если IP не указан, выходим сразу
                    END IF;
                    
                    SET count_val = count_val + 1;
                    
                    -- Если прошло более 200 итераций (около 66 секунд), выходим
                    IF count_val > 200 THEN
                        SET f = 1;
                    END IF;
                END WHILE;
                
            END
                        ";
    }
}