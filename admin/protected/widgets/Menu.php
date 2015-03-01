<?php

class Menu extends CWidget
{
    public function init()
    {
    }

    public function run()
    {
        $errMes = 'Bạn không có quyền thực hiện hành động này.';
        $user = User::model()->findByPk(Yii::app()->user->getId());
        if($user == null)
            throw new CHttpException(403, $errMes);
        return $this->render('menu',compact('user'));
    }
}

