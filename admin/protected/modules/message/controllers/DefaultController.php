<?php

class DefaultController extends Controller
{
	public function beforeAction(){
		Helpers::checkAccessRule(array(), array('1', '0'));
		return true;
	}

	public function actionIndex()
	{
		$this->render('search');
	}

	public function actionView(){
		$id = isset($_GET['id']) ? $_GET['id'] : null;
		if($id==null){
			$this->redirect(array('index'));
		}

		$contentMessage = ContentMessage::model()->findByPk($id);

		if($contentMessage == null) $this->redirect(array('index'));

		$this->title='View Message | CMS An Thanh';

		$msg = isset($_GET['msg']) ? true : false;
		$this->render('view',compact('msg','contentMessage'));
	}

	public function actionEdit(){

		$this->widget('CkEditor');
		$this->title='Add Message | CMS An Thanh';
		$contentMessage = null;

		$id = isset($_GET['id']) ? $_GET['id'] : null;

		if($id != null){
			$contentMessage = ContentMessage::model()->findByPk($id);
		}

		if($contentMessage == null) $contentMessage = new ContentMessage();

		if(isset($_POST['message'])){
			if($contentMessage->getIsNewRecord()){
				$contentMessage->created = date("Y-m-d H:m:i");
			}
			$contentMessage->modified = date("Y-m-d H:m:i");
			$contentMessage->setAttributes($_POST['message']);
			$contentMessage->user_id = Yii::app()->user->getId();
			$contentMessage->thumbnail = Helpers::getFirstImg($_POST['message']['detail']);
			if($contentMessage->validate()){
				$contentMessage->save(false);
				$this->redirect(array('view','id' => $contentMessage->id, 'msg' => true));
			}

		}

		$this->render('edit',compact('contentMessage'));
	}

	public function actionDelete(){
		$id = isset($_GET['id']) ? $_GET['id'] : null;
		if($id!=null){
			ContentMessage::model()->deleteByPk($id);
		}
		$this->redirect(array('index'));
	}
}