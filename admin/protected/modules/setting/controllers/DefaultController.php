<?php

class DefaultController extends Controller
{
	public function actionIndex()
	{
		$this->title='Setting | CMS An Thanh';
		$msg = false;
		$contentSetting = null;

		$contentSetting = ContentSetting::model()->findByPk('1');


		if($contentSetting == null) $contentSetting = new ContentSetting();
//		var_dump($contentSetting);die;
		if(isset($_POST['setting'])){
			$contentSetting->setAttributes($_POST['setting']);
			if($contentSetting->validate()){
				$contentSetting->save(false);
//				$this->redirect(array('index', 'msg' => true));
				$msg = true;
			}
		}

		$this->render('index',compact('contentSetting', 'msg'));
	}
}