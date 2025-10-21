<?php

namespace forms\SYS\ReplacePassword;

use models\_G_session;

class MODEL extends \forms\FormsModel
{
    public function ReplacePassword($Password1)
    {
        $user = \models\User::get();
        $password = $Password1;
        $password_crypto = $user->hashPassword($password);
        $user = new \DB\Table\Users();

        $user
            ->set($user::renewPassword,'0' )
            ->set($user::password_crypto,$password_crypto )
            ->where($user::id,_G_session::id_user())
            ->update();
    }
}