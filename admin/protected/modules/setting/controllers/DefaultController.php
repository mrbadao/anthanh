<?php

class DefaultController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionProfile(){
		$this->title='Edit Profile | CMS An Thanh';
		$contentUser = null;
		$msg = false;

		$id = Yii::app()->user->getId() : null;

		if($id == null){
			Yii::app()->request->redirect('/admin/site/logout');
		}
		var_dump('asd');die;
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

		$this->render('profile',compact('contentRecruitment', 'msg'));
	}
}