<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();

	public function init()
	{
//		$this->userName = Yii::app()->user->getName();
		$this->setTitle(Yii::app()->params['appName']);
		$categories = ContentCategories::model()->findAll();
		foreach($categories as $category){
			$this->menu['items'][] = array('label'=>$category->title, 'url'=> array($category->abbr_cd));
		}
		//$this->viewPath = substr($viewPath,strpos($viewPath,'/protected'));
//            $this->action = $this->action->id;
//            Helpers::setSessionSiteId(1);

//		$this->menu['items'] = array(
//			array('label'=>'Trang chủ', 'url'=>array('/site/index')),
//			array('label'=>'Giới thiệu', 'url'=>array('/site/page', 'view'=>'about')),
//			array('label'=>'Liên hệ', 'url'=>array('/site/contact')),
//			array('label'=>'Dự án', 'url'=>array('/site/login')),
//			array('label'=>'Giao dịch', 'url'=>array('/site/login')),
//
//		);
	}

	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

	public function setTitle($title)
	{
		$this->title = $title;
	}
}