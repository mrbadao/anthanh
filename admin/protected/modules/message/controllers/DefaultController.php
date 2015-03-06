<?php

class DefaultController extends Controller
{
	const SESS_KEY = '_MESSAGE';
	public function beforeAction(){
		Helpers::checkAccessRule(array(), array('1', '0'));
		return true;
	}

	public function actionIndex()
	{
		$this->forward('search');
	}

	public function actionSearch(){
		$this->title='Manager Message | CMS An Thanh';
		$role = Helpers::getRole();
		$search['title'] = $search['user_name'] = '';

		$session = Yii::app()->session;

		if(isset($_POST['search']))
		{

			if($session->contains(self::SESS_KEY))
				$session->remove(self::SESS_KEY);

			$data['search'] = $_POST['search'];
			$data['page'] = 1;
			$session->add(self::SESS_KEY, $data);
		}

		$c = new CDbCriteria();
		$c->alias = "t";
		if($role != 1){
			$c->addCondition('user_id = '.Yii::app()->user->getId(), 'AND');
		}

		$c->join = 'JOIN user as u on t.user_id = u.id';
		$c->together = true;

		if(isset($session[self::SESS_KEY]['search']))
		{

			$search = $session[self::SESS_KEY]['search'];
			foreach($search as $k => $v)
			{
				if(!isset($v) || $v === '')
				{
					continue;
				}
				switch($k)
				{
					case 'title':
						$c->compare($k, $v, true,'AND');
						break;
					case 'user_name':
						$c->compare('u.name', $v, true,'AND');
						break;
				}
			}
		}

		$sess_data = $session[self::SESS_KEY];
		if(isset($_GET['page']))
			$page = $sess_data['page'] = $_GET['page'];

		else
			$page = $sess_data['page'] = 1;
		$session->add(self::SESS_KEY,$sess_data);

		$c->select = 't.*';
		$c->group = 't.id';
		$c->order = 't.id DESC';
		$count = ContentMessage::model()->count($c);

		$nodata = ($count)?false:true;
		$c->limit = 10;
		$c->offset = $c->limit * ($page-1);
		$items = ContentMessage::model()->findAll($c);
//		var_dump($items);die;
		$pages = new CPagination($count);
		$pages->pageSize = $c->limit;
		$pages->applyLimit($c);
		$user_name = Yii::app()->user->name;
		$this->render('index',compact('items','count','pages','search','nodata', 'user_name', 'role'));
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