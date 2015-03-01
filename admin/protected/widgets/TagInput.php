<?php
/**
 * Created by PhpStorm.
 * User: Ho
 * Date: 11/21/2014
 * Time: 11:12 AM
 */
class TagInput extends CWidget
{
    public function init()
    {
    }

    public function run()
    {
        $baseUrl = Yii::app()->request->getBaseUrl(true);
        Yii::app()->clientScript
            ->registerCssFile($baseUrl . '/css/jquery.tagsinput.css')
            ->registerScriptFile($baseUrl . '/js/taginput/jquery.tagsinput.js', CClientScript::POS_END)
            ->registerScriptFile($baseUrl . '/js/taginput/jquery.tagsinput.min.js', CClientScript::POS_END);
    }
}