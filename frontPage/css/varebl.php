<?php
session_start();

?>

<script>
    function loadscript() {
    }
</script>

<style>
    /*============================================================================*/
    /* глобальные настройки для грида*/
    /*============================================================================*/
    .GridLine_hoverForInput {
        background: var(--my-background-input-select) !important;
        color: var(--my-black-text-color)    !important;
    }

    .GridLine {
        cursor: default;
    }

    .GridLine:hover {
        /*.background-Insert-Menu*/
        /*.textColorWhite*/
    }

    .GridLine2 {
        cursor: default;
        background: #f4f9ff!important;
        background: var(--my-all-background)!important;
        color: #000000!important; /*для долбанного эксплорера*/

    }

    .GridLine2:hover {
        background: #7ab8ff!important; /*для долбанного эксплорера*/
        background: var(--my-insert-menu-background)!important;
        color: #ffffff!important; /*для долбанного эксплорера*/
        color: var(--my-white-text-color)!important;
    }

    .GridLine tr td:last-child {
        border-right: none;
    }

    .GridLine tr:last-child td {
        border-bottom: none;
    }


    .GridLine_grey {
        cursor: default;
        background-color: #e2f1fb; /*для долбанного эксплорера*/
        background-color: var(--my-all-background_gray);
    }

    .GridLine_grey:hover {
        /*.background-Insert-Menu*/
        /*.textColorWhite*/
    }
    .GridLine_grey2 {
        cursor: default;
        color: #000000!important; /*для долбанного эксплорера*/
        background-color: #e2f1fb!important; /*для долбанного эксплорера*/
        background-color: var(--my-all-background_gray)!important;
    }

    .GridLine_grey2:hover {
        background: #7ab8ff!important; /*для долбанного эксплорера*/
        background: var(--my-insert-menu-background)!important;
        color: #ffffff!important; /*для долбанного эксплорера*/
        color: var(--my-white-text-color)!important;
    }

    .GridLine_grey tr td:last-child {
        border-right: none;
    }

    .GridLine_grey tr:last-child td {
        border-bottom: none;
    }

    .GridInput {
        /*display:none;*/
        /*.textFontNormal*/
        /*.textColorBlack*/
        position: relative;
        left: 0px;
        bottom: 0px;
        outline: 0;
        outline-offset: 0;
        border: none;
        vertical-align: middle;
        /*margin-left: 10px;*/
        background-color: inherit;
    }

    .BorderGrid {
        border: 1px;
        border-style: solid;
        border-color: #97c6ff; /*для долбанного эксплорера*/
        border-color: var(--my-border-color);
    }

    .BorderGrid_td {
        padding-left: 5px; /* Поля вокруг содержимого ячеек */
        padding-top: 0px; /* Поля вокруг содержимого ячеек */
        padding-right: 5px; /* Поля вокруг содержимого ячеек */
        padding-bottom: 0px; /* Поля вокруг содержимого ячеек */
        /* Граница вокруг ячеек */
        border-top-style: solid;
        border-top-width: 1px;

        border-right-style: solid;
        border-right-width: 1px;
        border-bottom-style: none;
        border-bottom-width: initial;
        border-left-style: solid;
        border-left-width: 1px;
        border-image-source: initial;
        border-image-slice: initial;
        border-image-width: initial;
        border-image-outset: initial;
        border-image-repeat: initial;
        border-bottom: none;
        border-color: #97c6ff; /*для долбанного эксплорера*/
        border-color: var(--my-border-color);
    }

    /*============================================================================*/
    /* глобальные настройки для Окна */
    /*============================================================================*/
    .WinMain {
        /*.borderRadiusTop*/
        /*.borderRadiusBottom*/
        position: relative;
        align-content: center;
        overflow: hidden;
    }

    .WinHead {
        /*.borderBottom*/
        /*.borderRadiusTop*/
        /*.backgroundInsert*/
        /*.shadowNormal*/
        position: absolute;
        top: 0px;
        right: 0;
        left: 0px;
    }

    .WinHead1 {
        /*.textColorWhite*/
        /*.textFontBig*/
        padding-left: 40px;
        padding-right: 20px
    }

    .WinHead2 {
        /*.textColorWhite*/
        padding-left: 60px;
        padding-right: 20px
    }

    .WinHead3 {
        position: absolute;
        right: 0px;
        margin-top: -9px;
    }

    .WinContent {
        /*.shadowNormal*/
        /*.backgroundNormal*/
        position: absolute;
        top: 100px;
        margin: 0px;
        padding: 0px;
        /*height:210px;*/
        left: 0px;
    }

    /*============================================================================*/
    /* глобальные настройки для инпута*/
    /*============================================================================*/

    .MyInput {
        /*display:none;*/
        /*.textColorBlack*/
        position: absolute;
        left: 0px;
        bottom: 0px;
        width: 530px;
        outline: 0;
        outline-offset: 0;
        border: none;
        vertical-align: middle;
        margin-left: 10px;
        /*background-color: inherit;*/
        background-color: rgba(0, 0, 0, 0)

    }

    input:-webkit-autofill {
        -webkit-box-shadow: inset 0 0 0 50px var(--my-all-background) !important;
        -webkit-text-fill-color: var(--my-black-text-color) !important;
        color: #0f0 !important;
    }

    .MyInputDiv {
        /*.borderRadiusBottom*/
        /*.borderBottom*/
        overflow: hidden;
    }

    p {
        /*.textColorBlack*/
        margin: 0px;
        margin-top: 10px;
        margin-left: 15px;
    }

    /*============================================================================*/
    /*глобальные настройки для объекта кнопка*/
    /*============================================================================*/
    .MyButton {
        /*.background-Menu*/
        /*.textColorBlack*/
        /*.BorderAll*/
        text-align: center;
        cursor: pointer;
    }

    .MyButton:hover {
        /*.background-Insert-Menu*/
        /*.textColorWhite*/
    }

    .MyButtonReaOnly {
        /*.backgroundNormal*/
        /*.textColorBlack*/
        /*.BorderAll*/
        text-align: center;
        cursor: pointer;
    }


    .MyButtonRed {
        /*.textColorBlack*/
        /*.BorderAll*/
    //background: #ff101030;
        background: rgb(255, 212, 212);
        text-align: center;
        cursor: pointer;
    }

    .MyButtonRed:hover {
        /*.background-Insert-Menu*/
        /*.textColorWhite*/
    }

    .MyButtonGreen {
        /*.textColorBlack*/
        /*.BorderAll*/
    //background: #ff101030;
        background: rgb(212, 255, 214);
        text-align: center;
        cursor: pointer;
    }

    .MyButtonGreen:hover {
        /*.background-Insert-Menu*/
        /*.textColorWhite*/
    }

    .MyButtonYellow {
        /*.textColorBlack*/
        /*.BorderAll*/
    //background: #ff101030;
        background: rgb(255, 251, 212);
        text-align: center;
        cursor: pointer;
    }

    .MyButtonYellow:hover {
        /*.background-Insert-Menu*/
        /*.textColorWhite*/
    }




    /*============================================================================*/
    /*глобальные настройки для объекта вывода текста*/
    /*============================================================================*/
    .MyText {
        text-align: center;
    }

    /*============================================================================*/
    /* настройка вплывающей ошибки*/
    /*============================================================================*/
    .BlockAPP {
        margin: 0 auto;
        top: 0px;
        left: 0px;
        width: 100%;
        height: 100%;
        position: fixed;
        background-color: rgba(150, 150, 150, 0.4);
        z-index: 99999;
        backdrop-filter: blur(2px);
    }

    .MsgBlockAPP1 {
        position: absolute;
        top: 0px;
        right: 0px;
        bottom: 0px;
        left: 0px;
        margin: auto;
        padding: 0px;
        align-content: center;
    }

    .MsgWait {
        position: absolute;
        top: 0px;
        right: 0;
        bottom: 0;
        left: 0;
        margin: auto;
        padding: 0px;
        align-content: center;
        background-image: url(/frontPage/img/loading.gif);
        background-repeat: no-repeat;
    }

    .MsgBlockAPP {
        /*.borderRadiusTop*/
        /*.borderRadiusBottom*/
        /*. backgroundAlert*/
        /*. textColorWhite*/
        position: absolute;
        top: 0px;
        right: 0;
        bottom: 0;
        left: 0;
        margin: auto;
        padding: 0px;
        align-content: center;
    }

    .MsgBlockAPPTextHead {
        /*.textColorWhite*/
        /*.textFontBig*/
        position: absolute;
        left: 110px;
        top: 50px;
        height: 50px;
    }

    .MsgBlockAPPTextBody {
        /*.textColorWhite*/
        /*.textFontNormal*/
        position: absolute;
        left: 60px;
        top: 100px;
        height: 50px;
        width: 280px;
    }

    .MsgBlockAPPButton {
        /*.background-Menu*/
        /*.textColorBlack*/
        /*.borderRadiusTop*/
        /*.borderRadiusBottom*/

        position: absolute;
        left: 150px;
        top: 250px;
        height: 50px;
        width: 100px;

    }

    .MsgBlockAPPButton:hover {
        /*.backgroundInsert*/
        /*.textColorWhite*/

    }

    /*******************************************************************************************************/
    /* стили для чекбокса */
    /* Cначала обозначаем стили для IE8 и более старых версий
т.е. здесь мы немного облагораживаем стандартный чекбокс. */
    .checkbox {
        vertical-align: top;
        margin: 3px 3px 0 0;
        width: 17px;
        height: 17px;
    }

    /* Это для всех браузеров, кроме совсем старых, которые не поддерживают
    селекторы с плюсом. Показываем, что label кликабелен. */
    .checkbox + label {
        cursor: pointer;
    }

    /* Далее идет оформление чекбокса в современных браузерах, а также IE9 и выше.
    Благодаря тому, что старые браузеры не поддерживают селекторы :not и :checked,
    в них все нижеследующие стили не сработают. */

    /* Прячем оригинальный чекбокс. */
    .checkbox:not(checked) {
        position: absolute;
        opacity: 0;
    }

    .checkbox:not(checked) + label {
        position: relative; /* будем позиционировать псевдочекбокс относительно label */
        /*        padding: 0 0 0 35px; /* оставляем слева от label место под псевдочекбокс */
        padding-left: 35px; /* оставляем слева от label место под псевдочекбокс */
    }

    /* Оформление первой части чекбокса в выключенном состоянии (фон). */
    .checkbox:not(checked) + label:before {
        content: '';
        position: absolute;
        top: 0px;
        left: 0;
        width: 30px;
        height: 16px;
        border-radius: 8px;
        background: #CDD1DA;
        box-shadow: inset 0 2px 3px rgba(0, 0, 0, .2);
    }

    /* Оформление второй части чекбокса в выключенном состоянии (переключатель). */
    .checkbox:not(checked) + label:after {
        content: '';
        position: absolute;
        top: 2px;
        left: 2px;
        width: 11px;
        height: 11px;
        border-radius: 5px;
        background: #FFF;
        box-shadow: 0 2px 5px rgba(0, 0, 0, .3);
        transition: all .2s; /* анимация, чтобы чекбокс переключался плавно */
    }

    /* Меняем фон чекбокса, когда он включен. */
    .checkbox:checked + label:before {
        background: #3e75fe ;/*#02fffc*/
    }

    /* Сдвигаем переключатель чекбокса, когда он включен. */
    .checkbox:checked + label:after {
        left: 16px;
    }

    /*******************************************************************************************************/
    /*******************************************************************************************************/
    /* стили для selectBox*/

    .SelectBoxDiv select {
        /*.textFontNormal*/
        /*.BorderAll*/
        background: transparent;
        /*height: 34px;*/
        padding: 5px;
        line-height: 1;
        -webkit-appearance: none;
        width: 100%;
        font-style: normal;
    }
</style>
