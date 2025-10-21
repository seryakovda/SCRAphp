<?php


namespace models;


class _G_session
{
    const workstation  = 'workstation';
    const mobile = 'mobile';

    static function userPassword($value = false)
    {
        if ( $value !== false )
            $_SESSION['userPassword'] = $value;

        if (array_key_exists('userPassword',$_SESSION))
            return $_SESSION['userPassword'];
        else
            return '';
    }


    static function id_user($value = false)
    {
        if ( $value !== false )
            $_SESSION['id_user'] = $value;

        if (array_key_exists('id_user',$_SESSION))
            return $_SESSION['id_user'];
        else
            return 0;
    }

    static function id_human()
    {
        return $_SESSION['id_human'];
    }

    static function superUser()
    {
        return $_SESSION["superUser"];
    }



    static function ROOT_PATH()
    {
        return $_SERVER['DOCUMENT_ROOT'];
    }

    static function typeDevice($value = false)
    {
        if ( $value !== false )
            $_SESSION['mobile'] = $value;

        return $_SESSION['mobile'];
    }

    static function widthMobile($value = false,$minus = 250)
    {
        if ( $value !== false ) {
            if ( $_SESSION["mobile"]  == self::workstation )
                $_SESSION['widthMobile'] = $value - $minus;
            else
                $_SESSION['widthMobile'] = $value;
        }else {
            if (!array_key_exists('widthMobile',$_SESSION))
                $_SESSION['widthMobile'] = 0;
        }

        return $_SESSION['widthMobile'];
    }

    static function heightMobile($value = false,$minus = 0)
    {
        if ( $value !== false ) {
            if ( $_SESSION["mobile"]  == self::workstation )
                $_SESSION['heightMobile'] = $value - $minus;
            else
                $_SESSION['heightMobile'] = $value;
        }else {
            if (!array_key_exists('heightMobile',$_SESSION))
            $_SESSION['heightMobile'] = 0;
        }

        return $_SESSION['heightMobile'];
    }
}