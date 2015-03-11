<?php

class Menu extends CWidget
{
    public function init()
    {
    }

    public function run()
    {
        $errMes = 'Bạn không có quyền thực hiện hành động này.';

        if (Yii::app()->user->IsGuest) {
            $this->redirect('admin/site/login');
        }

        $user = User::model()->findByPk(Yii::app()->user->getId());
        if($user == null)
            throw new CHttpException(403, $errMes);
        return $this->render('menu',compact('user'));
    }
}

