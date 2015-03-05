<?php

class DefaultController extends Controller
{
	const SESS_KEY = '_PROJECT';

	public function beforeAction(){
		Helpers::checkAccessRule(array(), array('1'));
		return true;
	}

	public function actionIndex()
	{
		$this->forward('search');
	}

	public function actionSearch(){
		$this->title='Manager Project | CMS An Thanh';

		$search['title'] = $search['status'] = $search['highline'] =  '';
		$search['pr'] = '';

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
					case 'title':
						$c->compare($k, $v, true,'AND');
						break;
					case 'status':
						$c->compare($k, $v, false,'AND');
						break;
					case 'pr':
						$c->compare($k, $v, false,'AND');
						break;
				}
			}
		}

		$search['pr'] = isset($search['pr']) ? $search['pr'] : null;
		$search['highline'] = isset($search['highline']) ? $search['highline'] : null;

		$sess_data = $session[self::SESS_KEY];
		if(isset($_GET['page']))
			$page = $sess_data['page'] = $_GET['page'];

		else
			$page = $sess_data['page'] = 1;
		$session->add(self::SESS_KEY,$sess_data);

		$c->select = 't.*';
		$c->group = 't.id';
		$c->order = 't.id DESC';
		$count = ContentProjects::model()->count($c);

		$nodata = ($count)?false:true;
		$c->limit = 10;
		$c->offset = $c->limit * ($page-1);
		$items = ContentProjects::model()->findAll($c);

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

		$contentProject = ContentProjects::model()->findByPk($id);

		if($contentProject == null) $this->redirect(array('index'));

		$this->title='View Project | CMS An Thanh';

		$msg = isset($_GET['msg']) ? true : false;
		$this->render('view',compact('msg','contentProject'));
	}

	public function actionEdit(){
		$this->widget('CkEditor');
		$this->title='Add Project | CMS An Thanh';
		$contentProject = null;

		$id = isset($_GET['id']) ? $_GET['id'] : null;

		if($id != null){
			$contentProject = ContentProjects::model()->findByPk($id);
		}

		if($contentProject == null) $contentProject = new ContentProjects();

		if(isset($_POST['project'])){
			if($contentProject->getIsNewRecord()){
				$contentProject->created = date("Y-m-d H:m:i");
			}
			$contentProject->modified = date("Y-m-d H:m:i");
			$contentProject->setAttributes($_POST['project']);
			$contentProject->thumbnail = Helpers::getFirstImg($_POST['project']['detail']);
			if($contentProject->validate()){
				$contentProject->save(false);
				$this->redirect(array('view','id' => $contentProject->id, 'msg' => true));
			}
		}

		$this->render('edit',compact('contentProject'));
	}

	public function actionDelete(){
		$id = isset($_GET['id']) ? $_GET['id'] : null;
		if($id!=null){
			ContentProjects::model()->deleteByPk($id);
		}
		$this->redirect(array('index'));
	}
}