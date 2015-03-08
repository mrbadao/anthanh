<?php

class ContentController extends Controller
{
	/**
	 * Declares class-based actions.
	 */

	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$page = isset($_GET['page']) ? $_GET['page'] : 1;

		$this->setTitle('Trang Chủ');
		$c = new CDbCriteria();
		$c->order ='id DESC';
		$c->select ='t.*';
		$count = ContentMessage::model()->count($c);

		$c->limit = 12;
		$c->offset = $c->limit * ($page - 1);
		$items = ContentMessage::model()->findAll($c);

		foreach($items as $item){
			$user = User::model()->findByPk($item->user_id);
			$item->user = $user;
		}

		$pages = new CPagination($count);
		$pages->pageSize = $c->limit;
		$pages->applyLimit($c);

		$this->render('index', compact('items', 'pages'));
	}

	public function actionProject()
	{
		$this->setTitle('Dự án');
		$page = isset($_GET['page']) ? $_GET['page'] : 1;

		$c = new CDbCriteria();
		$c->order ='id DESC';
		$c->select ='t.*';
		$count = ContentProjects::model()->count($c);

		$c->limit = 12;
		$c->offset = $c->limit * ($page - 1);
		$items = ContentProjects::model()->findAll($c);

		foreach($items as $item){
			$user = User::model()->findByPk($item->user_id);
			$item->user = $user;
		}

		$pages = new CPagination($count);
		$pages->pageSize = $c->limit;
		$pages->applyLimit($c);

		$this->render('project', compact('items', 'pages'));
	}


	public function actionView(){
		$id = isset($_GET['id']) ? $_GET['id'] : null;
		$cat = isset($_GET['cat']) ? $_GET['cat'] : null;

		$view = 'viewnew';
		$item= null;
		$user = '';

		$c = new CDbCriteria();
		$c->alias ='t';
		switch($cat){
			case 3:
				$view = 'viewnew';

				$item = ContentMessage::model()->findByPk($id);

				if($item){
					$this->setTitle($item->title);
					$this->breadcrumbs = array(
						'Sàn giao dịch' =>array('/content/'),
						Helpers::getNumChars($item->title,14),
					);

					$user = User::model()->findByPk($item->user_id);
				}

				break;

			case 4:
				$view = 'viewproject';
				$item = ContentProjects::model()->findByPk($id);

				if($item){
					$this->setTitle($item->title);
					$this->breadcrumbs = array(
						'Dự án' =>array('/content/project/'),
						Helpers::getNumChars($item->title,14),
					);

					$user = User::model()->findByPk($item->user_id);
				}
				break;
			case 5:
				$view = 'viewrecruitment';

				$item = ContentRecruitment::model()->findByPk($id);

				if($item){
					$this->setTitle($item->title);
					$this->breadcrumbs = array(
						'Tuyển dụng' =>array('/content/recruitment/'),
						Helpers::getNumChars($item->title,14),
					);

					$user = User::model()->findByPk($item->user_id);
				}
				break;


		}

		if($item == null) $this->redirect('index');

		$this->render($view, compact('item', 'user'));
	}

	public function actionRecruitment(){
		$this->setTitle('Tuyển dụng');
		$page = isset($_GET['page']) ? $_GET['page'] : 1;

		$c = new CDbCriteria();
		$c->order ='id DESC';
		$c->select ='t.*';
		$count = ContentRecruitment::model()->count($c);

		$c->limit = 12;
		$c->offset = $c->limit * ($page - 1);
		$items = ContentRecruitment::model()->findAll($c);

		foreach($items as $item){
			$user = User::model()->findByPk($item->user_id);
			$item->user = $user;
		}

		$pages = new CPagination($count);
		$pages->pageSize = $c->limit;
		$pages->applyLimit($c);

		$this->render('recruitment', compact('items', 'pages'));
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionAbout()
	{
		$this->setTitle('Giới thiệu');
		$this->breadcrumbs = array(
			'Giới thiệu',
		);

		$this->render('index');
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}