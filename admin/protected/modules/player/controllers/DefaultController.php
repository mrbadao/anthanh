<?php

class DefaultController extends Controller
{
    public function beforeAction(){
        Helpers::checkAccessRule(array(), array('1','0'));
        return true;
    }

    public function actionIndex()
    {
        $this->title='Edit Profile | CMS An Thanh';
        $contentUser = null;
        $msg = false;

        $id = Yii::app()->user->getId() ;

		if($id == null){
            Yii::app()->request->redirect('/admin/site/logout');
        }

		$contentUser = User::model()->findByPk($id);

		if($contentUser == null) Yii::app()->request->redirect('/admin/site/logout');

		if(isset($_POST['user'])){
            if($contentUser->getIsNewRecord()){
                $contentUser->created = date("Y-m-d H:m:i");
            }
            $contentUser->modified = date("Y-m-d H:m:i");
            $contentUser->setAttributes($_POST['user']);
            if($contentUser->validate()){
                $contentUser->save(false);
                $msg = true;
            }
        }

		$this->render('index',compact('contentUser', 'msg'));
    }

    public function actionChangepwd(){
        $this->title='Change Password | CMS An Thanh';
        $contentUser = null;
        $msg = false;
        $edit['new_pwd'] = $edit['confirm_pwd'] = '';
        $id = Yii::app()->user->getId() ;

        if($id == null){
            Yii::app()->request->redirect('/admin/site/logout');
        }

        $contentUser = User::model()->findByPk($id);

        if($contentUser == null) Yii::app()->request->redirect('/admin/site/logout');

        if(isset($_POST['user'])){
            $contentUser->setScenario('check_password');

            if(isset($_POST['new_pwd'])) {
                $edit['new_pwd'] = $_POST['new_pwd'];
            }
            if(isset($_POST['confirm_pwd'])) {
                $edit['confirm_pwd'] = $_POST['confirm_pwd'];
            }

            $contentUser->attributes = $edit;

            if($contentUser->validate()){
                $contentUser->password = $contentUser->hashPassword($_POST['new_pwd']);
                $contentUser->save(false);
                $msg = true;
            }
        }
        $this->render('changepwd',compact('contentUser', 'msg', 'edit'));
	}
}