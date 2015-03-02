<?php

class DefaultController extends Controller
{
	public function actionIndex()
	{
//		var_dump($this->layout);die;
		$this->render('index');
	}

	public function actionEdit(){
		$this->widget('CkEditor');
		$this->title='CMS An Thanh';
		$this->render('edit');
	}
}