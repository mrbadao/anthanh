<?php

class HighlineProjectWidget extends CWidget
{
    public function init()
    {
    }

    public function run()
    {
        $c =  new CDbCriteria();
        $c->addCondition(' highline = 1','AND');
        $c->order = 'id DESC';
        $c->limit = 3;
        $c->offset = 0;
        $projects = ContentProjects::model()->findAll($c);
        return $this->render('highlinewidget',compact('projects'));
    }
}

