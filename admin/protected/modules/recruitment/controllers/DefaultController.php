<?php

class DefaultController extends Controller
{
	const SESS_KEY = '_RECRUITMENT';

	public function beforeAction(){
		Helpers::checkAccessRule(array(), array('1'));
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
		$count = ContentRecruitment::model()->count($c);

		$nodata = ($count)?false:true;
		$c->limit = 10;
		$c->offset = $c->limit * ($page-1);
		$items = ContentRecruitment::model()->findAll($c);
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

		$contentRecruitment = ContentRecruitment::model()->findByPk($id);

		if($contentRecruitment == null) $this->redirect(array('index'));

		$this->title='View Recruitment | CMS An Thanh';

		$msg = isset($_GET['msg']) ? true : false;
		$this->render('view',compact('msg','contentRecruitment'));
	}

	public function actionEdit(){
		$this->widget('CkEditor');
		$this->title='Add Recruitment | CMS An Thanh';
		$contentRecruitment = null;

		$id = isset($_GET['id']) ? $_GET['id'] : null;

		if($id != null){
			$contentRecruitment = ContentRecruitment::model()->findByPk($id);
		}

		if($contentRecruitment == null) $contentRecruitment = new ContentRecruitment();

		if(isset($_POST['recruitment'])){
			if($contentRecruitment->getIsNewRecord()){
				$contentRecruitment->created = date("Y-m-d H:m:i");
			}
			$contentRecruitment->modified = date("Y-m-d H:m:i");
			$contentRecruitment->setAttributes($_POST['recruitment']);
			if($contentRecruitment->validate()){
				$contentRecruitment->save(false);
				$this->redirect(array('view','id' => $contentRecruitment->id, 'msg' => true));
			}
		}

		$this->render('edit',compact('contentRecruitment'));
	}

	public function actionDelete(){
		$id = isset($_GET['id']) ? $_GET['id'] : null;
		if($id!=null){
			ContentRecruitment::model()->deleteByPk($id);
		}
		$this->redirect(array('index'));
	}
}