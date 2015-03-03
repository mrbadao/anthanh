<?php

class DefaultController extends Controller
{
	public function actionIndex()
	{
		$search['title'] = $search['status'] = $search['pr'] = $search['highline'] =  '';
		$this->title='Manager Project | CMS An Thanh';
		$this->render('index',compact('search'));
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