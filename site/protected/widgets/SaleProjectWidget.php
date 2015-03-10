<?php

class SaleProjectWidget extends CWidget
{
    public function init()
    {
    }

    public function run()
    {
        $c =  new CDbCriteria();
        $c->addCondition(' status = -1','AND');
        $c->order = 'id DESC';
        $c->limit = 3;
        $c->offset = 0;
        $projects = ContentProjects::model()->findAll($c);
        return $this->render('salewidget',compact('projects'));
    }
}

