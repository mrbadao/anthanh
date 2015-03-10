<?php

class PresentProjectWidget extends CWidget
{
    public function init()
    {
    }

    public function run()
    {
        $c =  new CDbCriteria();
        $c->addCondition(' pr = 1','AND');
        $c->order = 'id DESC';
        $c->limit = 5;
        $c->offset = 0;
        $projects = ContentProjects::model()->findAll($c);
        return $this->render('presentwidget',compact('projects'));
    }
}

