<?php

class WebUser extends CWebUser {

    public function stayOnline(){
        $user=User::model()->find('uid=?',array(Yii::app()->user->id));
        $user->last_action = date('Y-m-d H:i:s',time());
        $user->save();
    }

}