<?php


//echo emarinFormat(9892674) . PHP_EOL;
echo hexFormat((int)"54318") . PHP_EOL;


function hexFormat($decimal)
{
    // переводим в HEX
    $hex = strtoupper(dechex($decimal));

    // дополняем до чётного количества символов
    if (strlen($hex) % 2 != 0) {
        $hex = '0' . $hex;
    }

    // берём последние 3 байта (6 hex символов)
    $hex = substr($hex, -6);

    // разбиваем
    $byte1 = substr($hex, 0, 2);
    $byte23 = substr($hex, 2, 4);

    // переводим обратно в decimal



    return $byte1.$byte23;
}