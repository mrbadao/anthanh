<?php

class DefaultController extends Controller
{
	const SESS_KEY = '_USERMANAGER';

	public function beforeAction(){
		Helpers::checkAccessRule(array(), array('1'));
		return true;
	}

	public function actionIndex()
	{
		$this->forward('search');
	}

	public function actionSearch(){
		$this->title='Manager User | CMS An Thanh';

		$search['loginid'] = $search['name'] = $search['phone'] = '';

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
					case 'loginid':
						$c->compare($k, $v, true,'AND');
						break;
					case 'name':
						$c->compare($k, $v, true,'AND');
						break;
					case 'phone':
						$c->compare($k, $v, true,'AND');
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
		$count = User::model()->count($c);

		$nodata = ($count)?false:true;
		$c->limit = 10;
		$c->offset = $c->limit * ($page-1);
		$items = User::model()->findAll($c);
		$pages = new CPagination($count);
		$pages->pageSize = $c->limit;
		$pages->applyLimit($c);

		$this->render('index',compact('items','count','pages','search','nodata'));
	}

	public function actionView(){
		$id = isset($_GET['id']) ? $_GET['id'] : null;
		if($id==null){
			$this->redirect(array('index'));
		}

		$contentUser = User::model()->findByPk($id);

		if($contentUser == null) $this->redirect(array('index'));

		$this->title='View User | CMS An Thanh';

		$msg = isset($_GET['msg']) ? true : false;
		$this->render('view',compact('msg','contentUser'));
	}

	public function actionEdit(){
		$this->title='Add User | CMS An Thanh';
		$contentUser = null;

		$id = isset($_GET['id']) ? $_GET['id'] : null;

		if($id != null){
			$contentUser = User::model()->findByPk($id);
		}

		if($contentUser == null) $contentUser = new User();

		if(isset($_POST['user'])){
			if($contentUser->getIsNewRecord()){
				$contentUser->created = date("Y-m-d H:m:i");
			}
			$contentUser->modified = date("Y-m-d H:m:i");
			$contentUser->setAttributes($_POST['user']);
			if($contentUser->validate()){
				$contentUser->password = $contentUser->hashPassword($contentUser->password);
				$contentUser->save(false);
				$this->redirect(array('view','id' => $contentUser->id, 'msg' => true));
			}
		}

		$this->render('edit',compact('contentUser'));
	}

	public function actionDelete(){
		$id = isset($_GET['id']) ? $_GET['id'] : null;
		if($id!=null){
			User::model()->deleteByPk($id);
		}
		$this->redirect(array('index'));
	}
}